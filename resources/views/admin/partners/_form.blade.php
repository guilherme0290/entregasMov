@php
    $partnerModel = $partner ?? null;
@endphp
<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Acesso do Parceiro</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                <input name="name" value="{{ old('name', $partnerModel?->user?->name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">E-mail</label>
                <input name="email" value="{{ old('email', $partnerModel?->user?->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Telefone</label>
                <input name="phone" value="{{ old('phone', $partnerModel?->user?->phone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Senha {{ $partnerModel ? '(preencha só se quiser trocar)' : '' }}</label>
                <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <label class="flex items-center gap-3 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partnerModel?->is_active ?? true))>
                Parceiro ativo
            </label>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Dados Comerciais</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome fantasia</label>
                <input name="trade_name" value="{{ old('trade_name', $partnerModel?->trade_name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Razão social</label>
                <input name="company_name" value="{{ old('company_name', $partnerModel?->company_name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CPF/CNPJ</label>
                <input name="tax_id" value="{{ old('tax_id', $partnerModel?->tax_id) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Responsável</label>
                <input name="contact_name" value="{{ old('contact_name', $partnerModel?->contact_name) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Telefone do responsável</label>
                <input name="contact_phone" value="{{ old('contact_phone', $partnerModel?->contact_phone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">E-mail financeiro</label>
                <input name="billing_email" value="{{ old('billing_email', $partnerModel?->billing_email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
        </div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Endereço de Coleta</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Endereço</label>
                <input name="pickup_address" value="{{ old('pickup_address', $partnerModel?->pickup_address) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Número</label>
                <input name="pickup_number" value="{{ old('pickup_number', $partnerModel?->pickup_number) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                <input name="pickup_district" value="{{ old('pickup_district', $partnerModel?->pickup_district) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                <input name="pickup_city" value="{{ old('pickup_city', $partnerModel?->pickup_city) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                <input name="pickup_state" value="{{ old('pickup_state', $partnerModel?->pickup_state) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                <input name="pickup_zip_code" value="{{ old('pickup_zip_code', $partnerModel?->pickup_zip_code) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                <input name="pickup_complement" value="{{ old('pickup_complement', $partnerModel?->pickup_complement) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
        </div>
    </div>

    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Valores</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Entrega padrão</label>
                <input type="number" step="0.01" name="default_delivery_fee" value="{{ old('default_delivery_fee', $partnerModel?->default_delivery_fee) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Entrega urgente</label>
                <input type="number" step="0.01" name="urgent_delivery_fee" value="{{ old('urgent_delivery_fee', $partnerModel?->urgent_delivery_fee) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Observações</label>
                <textarea name="notes" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $partnerModel?->notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.partners.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Cancelar</a>
    <button type="submit" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">{{ $submitLabel }}</button>
</div>
