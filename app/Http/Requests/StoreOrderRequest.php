<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Pesanan harus memilih minimal 1 produk.',
            'items.min' => 'Pesanan harus memilih minimal 1 produk.',
            'items.*.product_id.required' => 'Produk harus dipilih.',
            'items.*.product_id.exists' => 'Produk tidak valid.',
            'items.*.qty.required' => 'Jumlah (qty) wajib diisi.',
            'items.*.qty.integer' => 'Jumlah harus berupa angka.',
            'items.*.qty.min' => 'Jumlah minimal 1.',
        ];
    }
}
