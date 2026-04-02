<?php

namespace App\Http\Controllers\Api\V1\Courier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Courier\UpdateProfileRequest;
use App\Http\Requests\Api\Courier\UpdateVehicleRequest;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use ApiResponse;

    public function updateProfile(UpdateProfileRequest $request)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        DB::transaction(function () use ($request, $courier) {
            $request->user()->update([
                'name' => $request->string('name')->toString(),
                'email' => $request->filled('email') ? $request->string('email')->toString() : null,
                'phone' => $request->string('phone')->toString(),
            ]);

            $courier->update([
                'tax_id' => $request->filled('tax_id') ? $request->string('tax_id')->toString() : $courier->tax_id,
                'birth_date' => $request->date('birth_date') ?? $courier->birth_date,
                'address' => $request->filled('address') ? $request->string('address')->toString() : $courier->address,
                'number' => $request->filled('number') ? $request->string('number')->toString() : $courier->number,
                'district' => $request->filled('district') ? $request->string('district')->toString() : $courier->district,
                'city' => $request->filled('city') ? $request->string('city')->toString() : $courier->city,
                'state' => $request->filled('state') ? Str::upper($request->string('state')->toString()) : $courier->state,
                'zip_code' => $request->filled('zip_code') ? $request->string('zip_code')->toString() : $courier->zip_code,
                'complement' => $request->filled('complement') ? $request->string('complement')->toString() : $courier->complement,
                'notes' => $request->filled('notes') ? $request->string('notes')->toString() : $courier->notes,
            ]);
        });

        return $this->success([
            'user' => $request->user()->fresh()->loadMissing(['company', 'partner', 'courier']),
        ], 'Perfil atualizado com sucesso.');
    }

    public function updateVehicle(UpdateVehicleRequest $request)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $courier->update([
            'vehicle_type' => Str::lower($request->string('type')->toString()),
            'vehicle_model' => $request->string('model')->toString(),
            'vehicle_plate' => Str::upper($request->string('plate')->toString()),
        ]);

        return $this->success([
            'user' => $request->user()->fresh()->loadMissing(['company', 'partner', 'courier']),
        ], 'Veículo atualizado com sucesso.');
    }
}
