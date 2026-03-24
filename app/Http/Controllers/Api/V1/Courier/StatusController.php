<?php

namespace App\Http\Controllers\Api\V1\Courier;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    use ApiResponse;

    public function update(Request $request)
    {
        $data = $request->validate([
            'availability_status' => ['required', 'string', 'in:online,offline,busy,blocked'],
            'current_latitude' => ['nullable', 'numeric'],
            'current_longitude' => ['nullable', 'numeric'],
        ]);

        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $courier->update([
            ...$data,
            'last_status_at' => now(),
        ]);

        return $this->success($courier->fresh(), 'Status do entregador atualizado.');
    }
}
