<?php

namespace App\Http\Requests\Api\Courier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:Moto,Carro,Van,moto,carro,van'],
            'model' => ['required', 'string', 'max:255'],
            'plate' => ['required', 'string', 'max:10'],
        ];
    }
}
