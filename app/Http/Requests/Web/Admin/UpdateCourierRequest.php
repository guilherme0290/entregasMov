<?php

namespace App\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courier = $this->route('courier');
        $userId = $courier?->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6'],
            'tax_id' => ['required', 'string', 'max:20', Rule::unique('couriers', 'tax_id')->ignore($courier?->id)],
            'birth_date' => ['nullable', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'vehicle_type' => ['nullable', 'string', 'max:30'],
            'vehicle_model' => ['nullable', 'string', 'max:255'],
            'vehicle_plate' => ['nullable', 'string', 'max:10'],
            'availability_status' => ['required', 'string', 'in:online,offline,busy,blocked'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
