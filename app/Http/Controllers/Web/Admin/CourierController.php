<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\CourierAvailabilityStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\StoreCourierRequest;
use App\Http\Requests\Web\Admin\UpdateCourierRequest;
use App\Models\Courier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CourierController extends Controller
{
    public function index(Request $request): View
    {
        $couriers = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->with('user')
            ->withCount('deliveries')
            ->withCount([
                'deliveries as today_deliveries_count' => fn ($query) => $query->whereDate('created_at', today()),
            ])
            ->when(
                $request->filled('availability_status'),
                fn ($query) => $query->where('availability_status', CourierAvailabilityStatus::from($request->string('availability_status')->toString()))
            )
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $couriers->getCollection()->transform(function (Courier $courier) {
            $initials = collect(explode(' ', $courier->user->name))
                ->filter()
                ->take(2)
                ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode('');

            $courier->setAttribute('initials', $initials);
            $courier->setAttribute(
                'display_rating',
                number_format(min(5, max(4.5, 4.5 + ($courier->deliveries_count / 1000))), 1)
            );

            return $courier;
        });

        return view('admin.couriers.index', compact('couriers'));
    }

    public function create(): View
    {
        return view('admin.couriers.create');
    }

    public function show(Courier $courier): View
    {
        abort_unless($courier->company_id === auth()->user()->company_id, 404);

        $courier->load('user');
        $courier->loadCount('deliveries');
        $courier->loadCount([
            'deliveries as today_deliveries_count' => fn ($query) => $query->whereDate('created_at', today()),
        ]);

        $courier->setAttribute(
            'display_rating',
            number_format(min(5, max(4.5, 4.5 + ($courier->deliveries_count / 1000))), 1)
        );

        return view('admin.couriers.show', compact('courier'));
    }

    public function store(StoreCourierRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'company_id' => $request->user()->company_id,
                'name' => $request->string('name'),
                'email' => $request->string('email') ?: null,
                'phone' => $request->string('phone'),
                'password' => Hash::make($request->string('password')),
                'role' => UserRole::Courier,
                'is_active' => $request->boolean('is_active', true),
            ]);

            Courier::create([
                'user_id' => $user->id,
                'company_id' => $request->user()->company_id,
                'tax_id' => $request->string('tax_id'),
                'birth_date' => $request->date('birth_date'),
                'address' => $request->string('address'),
                'number' => $request->string('number') ?: null,
                'district' => $request->string('district') ?: null,
                'city' => $request->string('city'),
                'state' => $request->string('state'),
                'zip_code' => $request->string('zip_code') ?: null,
                'complement' => $request->string('complement') ?: null,
                'notes' => $request->string('notes') ?: null,
                'vehicle_type' => $request->string('vehicle_type') ?: null,
                'vehicle_model' => $request->string('vehicle_model') ?: null,
                'vehicle_plate' => $request->string('vehicle_plate') ?: null,
                'availability_status' => CourierAvailabilityStatus::from($request->input('availability_status', CourierAvailabilityStatus::Offline->value)),
                'last_status_at' => now(),
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('admin.couriers.index')->with('status', 'Entregador cadastrado com sucesso.');
    }

    public function edit(Courier $courier): View
    {
        abort_unless($courier->company_id === auth()->user()->company_id, 404);
        $courier->load('user');

        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(UpdateCourierRequest $request, Courier $courier): RedirectResponse
    {
        abort_unless($courier->company_id === $request->user()->company_id, 404);

        DB::transaction(function () use ($request, $courier) {
            $courier->user->update([
                'name' => $request->string('name'),
                'email' => $request->string('email') ?: null,
                'phone' => $request->string('phone'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->filled('password')) {
                $courier->user->update([
                    'password' => Hash::make($request->string('password')),
                ]);
            }

            $courier->update([
                'tax_id' => $request->string('tax_id'),
                'birth_date' => $request->date('birth_date'),
                'address' => $request->string('address'),
                'number' => $request->string('number') ?: null,
                'district' => $request->string('district') ?: null,
                'city' => $request->string('city'),
                'state' => $request->string('state'),
                'zip_code' => $request->string('zip_code') ?: null,
                'complement' => $request->string('complement') ?: null,
                'notes' => $request->string('notes') ?: null,
                'vehicle_type' => $request->string('vehicle_type') ?: null,
                'vehicle_model' => $request->string('vehicle_model') ?: null,
                'vehicle_plate' => $request->string('vehicle_plate') ?: null,
                'availability_status' => CourierAvailabilityStatus::from($request->input('availability_status', $courier->availability_status->value)),
                'last_status_at' => now(),
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('admin.couriers.index')->with('status', 'Entregador atualizado com sucesso.');
    }
}
