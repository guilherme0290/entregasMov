<?php

namespace App\Http\Requests\Api\Courier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;
        $courierId = $this->user()?->courier?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'tax_id' => ['nullable', 'string', 'max:20', Rule::unique('couriers', 'tax_id')->ignore($courierId)],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
