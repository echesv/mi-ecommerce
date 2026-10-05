<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Procesar pago de una orden
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            $user = $request->user();
            $order = Order::where('user_id', $user->id)->findOrFail($request->order_id);

            // Validar que la orden esté pendiente
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta orden ya ha sido procesada',
                ], 422);
            }

            // Procesar pago con Stripe
            $result = $this->stripeService->processPayment(
                $order,
                $request->stripe_token,
                $request->amount
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'order' => $order->refresh(),
                        'payment' => $result['payment'],
                    ],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver estado del pago de una orden
     */
    public function show($orderId, Request $request)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)->findOrFail($orderId);
            $payment = Payment::where('order_id', $order->id)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $payment,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pago no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Reembolsar un pago
     */
    public function refund($paymentId, Request $request)
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            // Validar que pertenece al usuario
            $order = $payment->order;
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                ], 403);
            }

            $result = $this->stripeService->refundPayment($payment->stripe_payment_id);

            if ($result['success']) {
                $payment->update(['status' => 'refunded']);

                return response()->json([
                    'success' => true,
                    'message' => 'Reembolso procesado exitosamente',
                    'data' => $payment,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el reembolso',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}