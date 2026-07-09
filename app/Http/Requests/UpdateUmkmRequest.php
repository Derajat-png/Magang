<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'business_type' => ['required', 'string', 'in:kuliner,fashion,kerajinan,jasa,pertanian,perikanan'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
        ];

        // Only super admin can change status and owner
        if (auth()->user()->isSuperAdmin()) {
            $rules['status'] = ['required', 'string', 'in:pending,active,inactive'];
            $rules['owner_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }
}
