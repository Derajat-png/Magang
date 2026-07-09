<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $umkmId = auth()->user()->isSuperAdmin() ? $this->input('umkm_id') : auth()->user()->umkm_id;

        return [
            'umkm_id' => [auth()->user()->isSuperAdmin() ? 'required' : 'nullable', 'exists:umkms,id'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) use ($umkmId) {
                    $query->whereNull('umkm_id')->orWhere('umkm_id', $umkmId);
                })
            ],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'sku' => [
                'required',
                'string',
                Rule::unique('products')->where(function ($query) use ($umkmId) {
                    return $query->where('umkm_id', $umkmId)->whereNull('deleted_at');
                })
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique' => 'SKU ini sudah digunakan untuk produk lain di UMKM ini.',
            'price.min' => 'Harga produk minimal Rp 100.',
            'stock.min' => 'Stok minimal 0.',
        ];
    }
}
