<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:20', 'unique:couriers,tax_id'],
            'birth_date' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'vehicle_type' => ['required', 'string', 'in:Moto,Carro,Van,moto,carro,van'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_plate' => ['required', 'string', 'max:10'],
            'cnh_front' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'cnh_back' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'proof_of_residence' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
