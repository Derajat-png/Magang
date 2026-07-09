<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'business_type' => ['required', 'string', 'in:kuliner,fashion,kerajinan,jasa,pertanian,perikanan'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:pending,active,inactive'],
            'owner_id' => ['required', 'exists:users,id'],
        ];
    }
}
