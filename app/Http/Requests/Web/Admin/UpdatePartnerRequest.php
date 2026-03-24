<?php

namespace App\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $partner = $this->route('partner');
        $userId = $partner?->user_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6'],
            'trade_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['required', 'string', 'max:20', Rule::unique('partners', 'tax_id')->ignore($partner?->id)],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'billing_email' => ['nullable', 'email', 'max:255'],
            'pickup_address' => ['required', 'string', 'max:255'],
            'pickup_number' => ['nullable', 'string', 'max:20'],
            'pickup_district' => ['nullable', 'string', 'max:255'],
            'pickup_city' => ['required', 'string', 'max:255'],
            'pickup_state' => ['required', 'string', 'size:2'],
            'pickup_zip_code' => ['nullable', 'string', 'max:10'],
            'pickup_complement' => ['nullable', 'string', 'max:255'],
            'default_delivery_fee' => ['required', 'numeric', 'min:0'],
            'urgent_delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
