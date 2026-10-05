<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->route('product');

        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'price' => 'sometimes|numeric|min:0.01',
            'quantity' => 'sometimes|integer|min:0',
            'sku' => 'sometimes|string|unique:products,sku,' . $productId . '|max:100',
            'image_url' => 'nullable|url',
            'status' => 'sometimes|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'price.numeric' => 'El precio debe ser un número',
            'sku.unique' => 'Este SKU ya existe',
            'status.in' => 'El estado debe ser active o inactive',
        ];
    }
}