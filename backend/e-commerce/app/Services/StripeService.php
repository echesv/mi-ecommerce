<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Procesar pago con Stripe
     */
    public function processPayment(Order $order, string $stripeToken, float $amount)
    {
        try {
            // Crear el payment intent en Stripe
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => (int)($amount * 100), // Convertir a centavos
                'currency' => 'usd',
                'payment_method' => $stripeToken,
                'confirm' => true,
                'description' => "Order #{$order->id}",
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ],
            ]);

            // Guardar en la BD
            $payment = Payment::create([
                'order_id' => $order->id,
                'stripe_payment_id' => $paymentIntent->id,
                'amount' => $amount,
                'status' => $paymentIntent->status,
                'stripe_response' => json_encode($paymentIntent->toArray()),
                'processed_at' => now(),
            ]);

            // Actualizar estado de la orden si el pago fue exitoso
            if ($paymentIntent->status === 'succeeded') {
                $order->update(['status' => 'completed']);
            }

            return [
                'success' => true,
                'payment' => $payment,
                'message' => 'Pago procesado exitosamente',
            ];

        } catch (\Exception $e) {
            // Guardar el error
            Payment::create([
                'order_id' => $order->id,
                'stripe_payment_id' => 'error_' . time(),
                'amount' => $amount,
                'status' => 'failed',
                'stripe_response' => json_encode(['error' => $e->getMessage()]),
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verificar estado del pago
     */
    public function verifyPayment(string $paymentId)
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentId);
            return $paymentIntent;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Reembolsar pago
     */
    public function refundPayment(string $paymentId)
    {
        try {
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $paymentId,
            ]);

            return [
                'success' => true,
                'refund' => $refund,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}