<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\StorePartnerRequest;
use App\Http\Requests\Web\Admin\UpdatePartnerRequest;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::query()
            ->where('company_id', auth()->user()->company_id)
            ->with('user')
            ->withCount('deliveries')
            ->withCount([
                'deliveries as monthly_deliveries_count' => fn ($query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]),
            ])
            ->latest()
            ->paginate(12);

        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View
    {
        return view('admin.partners.create');
    }

    public function store(StorePartnerRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'company_id' => $request->user()->company_id,
                'name' => $request->string('name'),
                'email' => $request->string('email') ?: null,
                'phone' => $request->string('phone'),
                'password' => Hash::make($request->string('password')),
                'role' => UserRole::Partner,
                'is_active' => $request->boolean('is_active', true),
            ]);

            Partner::create([
                'user_id' => $user->id,
                'company_id' => $request->user()->company_id,
                'trade_name' => $request->string('trade_name'),
                'company_name' => $request->string('company_name') ?: null,
                'tax_id' => $request->string('tax_id'),
                'contact_name' => $request->string('contact_name'),
                'contact_phone' => $request->string('contact_phone'),
                'billing_email' => $request->string('billing_email') ?: null,
                'pickup_address' => $request->string('pickup_address'),
                'pickup_number' => $request->string('pickup_number') ?: null,
                'pickup_district' => $request->string('pickup_district') ?: null,
                'pickup_city' => $request->string('pickup_city'),
                'pickup_state' => $request->string('pickup_state'),
                'pickup_zip_code' => $request->string('pickup_zip_code') ?: null,
                'pickup_complement' => $request->string('pickup_complement') ?: null,
                'default_delivery_fee' => $request->input('default_delivery_fee'),
                'urgent_delivery_fee' => $request->input('urgent_delivery_fee'),
                'notes' => $request->string('notes') ?: null,
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('admin.partners.index')->with('status', 'Parceiro cadastrado com sucesso.');
    }

    public function edit(Partner $partner): View
    {
        abort_unless($partner->company_id === auth()->user()->company_id, 404);
        $partner->load('user');

        return view('admin.partners.edit', compact('partner'));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner): RedirectResponse
    {
        abort_unless($partner->company_id === $request->user()->company_id, 404);

        DB::transaction(function () use ($request, $partner) {
            $partner->user->update([
                'name' => $request->string('name'),
                'email' => $request->string('email') ?: null,
                'phone' => $request->string('phone'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->filled('password')) {
                $partner->user->update([
                    'password' => Hash::make($request->string('password')),
                ]);
            }

            $partner->update([
                'trade_name' => $request->string('trade_name'),
                'company_name' => $request->string('company_name') ?: null,
                'tax_id' => $request->string('tax_id'),
                'contact_name' => $request->string('contact_name'),
                'contact_phone' => $request->string('contact_phone'),
                'billing_email' => $request->string('billing_email') ?: null,
                'pickup_address' => $request->string('pickup_address'),
                'pickup_number' => $request->string('pickup_number') ?: null,
                'pickup_district' => $request->string('pickup_district') ?: null,
                'pickup_city' => $request->string('pickup_city'),
                'pickup_state' => $request->string('pickup_state'),
                'pickup_zip_code' => $request->string('pickup_zip_code') ?: null,
                'pickup_complement' => $request->string('pickup_complement') ?: null,
                'default_delivery_fee' => $request->input('default_delivery_fee'),
                'urgent_delivery_fee' => $request->input('urgent_delivery_fee'),
                'notes' => $request->string('notes') ?: null,
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('admin.partners.index')->with('status', 'Parceiro atualizado com sucesso.');
    }
}
