<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Luego lo cambiaremos a validar que sea admin
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku|max:100',
            'image_url' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'price.required' => 'El precio es obligatorio',
            'price.numeric' => 'El precio debe ser un número',
            'sku.unique' => 'Este SKU ya existe',
            'status.in' => 'El estado debe ser active o inactive',
        ];
    }
}