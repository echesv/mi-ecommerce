<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Listar órdenes del usuario autenticado
     */
    public function index(Request $request)
    {
        try {
            $orders = $request->user()->orders()->with('items.product')->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Órdenes obtenidas exitosamente',
                'data' => $orders,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener órdenes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de una orden específica
     */
    public function show($id, Request $request)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)
                ->with('items.product', 'payment')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Crear nueva orden
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $user = $request->user();
            $totalAmount = 0;
            $items = $request->items;

            // Validar que hay productos y calcular total
            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La orden debe contener al menos un producto',
                ], 422);
            }

            // Calcular total y validar stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$product->name}",
                    ], 422);
                }

                $totalAmount += $product->price * $item['quantity'];
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Crear items de la orden y actualizar stock
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // Reducir stock del producto
                $product->decrement('quantity', $item['quantity']);
            }

            $order->load('items.product');

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'data' => $order,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancelar orden
     */
    public function cancel($id, Request $request)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden cancelar órdenes pendientes',
                ], 422);
            }

            // Restaurar stock
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Orden cancelada exitosamente',
                'data' => $order,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la orden',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}