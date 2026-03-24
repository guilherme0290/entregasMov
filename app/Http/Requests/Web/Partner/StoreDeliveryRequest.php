<?php

namespace App\Http\Requests\Web\Partner;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pickup_address' => ['nullable', 'string', 'max:255'],
            'pickup_number' => ['nullable', 'string', 'max:20'],
            'pickup_district' => ['nullable', 'string', 'max:255'],
            'pickup_city' => ['nullable', 'string', 'max:255'],
            'pickup_state' => ['nullable', 'string', 'size:2'],
            'pickup_zip_code' => ['nullable', 'string', 'max:10'],
            'pickup_complement' => ['nullable', 'string', 'max:255'],
            'pickup_reference' => ['nullable', 'string', 'max:255'],
            'dropoff_address' => ['required', 'string', 'max:255'],
            'dropoff_number' => ['nullable', 'string', 'max:20'],
            'dropoff_district' => ['nullable', 'string', 'max:255'],
            'dropoff_city' => ['required', 'string', 'max:255'],
            'dropoff_state' => ['required', 'string', 'size:2'],
            'dropoff_zip_code' => ['nullable', 'string', 'max:10'],
            'dropoff_complement' => ['nullable', 'string', 'max:255'],
            'dropoff_reference' => ['nullable', 'string', 'max:255'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'recipient_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'courier_payout_amount' => ['nullable', 'numeric', 'min:0'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'estimated_time_min' => ['nullable', 'integer', 'min:1'],
            'scheduled_for' => ['nullable', 'date'],
        ];
    }
}
