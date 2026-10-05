<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'stripe_token' => 'required|string', // Token de Stripe
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'La orden es obligatoria',
            'order_id.exists' => 'Esta orden no existe',
            'stripe_token.required' => 'El token de Stripe es obligatorio',
            'amount.required' => 'El monto es obligatorio',
        ];
    }
}