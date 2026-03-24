<?php

namespace App\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourierEarningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_status' => ['required', 'string', 'in:pending,released,paid,blocked'],
        ];
    }
}
