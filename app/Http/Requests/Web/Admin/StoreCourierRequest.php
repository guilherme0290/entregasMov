<?php

namespace App\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
            'tax_id' => ['required', 'string', 'max:20', 'unique:couriers,tax_id'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'district' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
            'zip_code' => ['required', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'vehicle_type' => ['required', 'string', 'in:moto,carro'],
            'vehicle_model' => ['nullable', 'string', 'max:255'],
            'vehicle_plate' => ['nullable', 'string', 'max:10'],
            'availability_status' => ['nullable', 'string', 'in:online,offline,busy,blocked'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
