<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'umkm_id' => [auth()->user()->isSuperAdmin() ? 'required' : 'nullable', 'exists:umkms,id'],
        ];
    }
}
