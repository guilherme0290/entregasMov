@php
    $courierModel = $courier ?? null;
@endphp
<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Acesso do Entregador</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                <input name="name" value="{{ old('name', $courierModel?->user?->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">E-mail</label>
                <input name="email" value="{{ old('email', $courierModel?->user?->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Telefone</label>
                <input name="phone" value="{{ old('phone', $courierModel?->user?->phone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Senha {{ $courierModel ? '(preencha só se quiser trocar)' : '' }}</label>
                <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <label class="flex items-center gap-3 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $courierModel?->is_active ?? true))>
                Entregador ativo
            </label>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Dados Pessoais</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CPF</label>
                <input name="tax_id" value="{{ old('tax_id', $courierModel?->tax_id) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Data de nascimento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($courierModel?->birth_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Status operacional</label>
                <select name="availability_status" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    @foreach (['online' => 'Online', 'offline' => 'Offline', 'busy' => 'Ocupado', 'blocked' => 'Bloqueado'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('availability_status', $courierModel?->availability_status?->value ?? 'offline') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Endereço</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Endereço</label>
                <input name="address" value="{{ old('address', $courierModel?->address) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Número</label>
                <input name="number" value="{{ old('number', $courierModel?->number) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                <input name="district" value="{{ old('district', $courierModel?->district) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                <input name="city" value="{{ old('city', $courierModel?->city) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                <input name="state" value="{{ old('state', $courierModel?->state) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                <input name="zip_code" value="{{ old('zip_code', $courierModel?->zip_code) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                <input name="complement" value="{{ old('complement', $courierModel?->complement) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Veículo</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tipo</label>
                <input name="vehicle_type" value="{{ old('vehicle_type', $courierModel?->vehicle_type) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Modelo</label>
                <input name="vehicle_model" value="{{ old('vehicle_model', $courierModel?->vehicle_model) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Placa</label>
                <input name="vehicle_plate" value="{{ old('vehicle_plate', $courierModel?->vehicle_plate) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Observações</label>
                <textarea name="notes" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $courierModel?->notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.couriers.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Cancelar</a>
    <button type="submit" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">{{ $submitLabel }}</button>
</div>
