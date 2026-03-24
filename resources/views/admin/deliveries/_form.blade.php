@php
    $delivery = $delivery ?? null;
    $partnerAddressOptions = $partners->map(fn ($partner) => [
        'id' => $partner->id,
        'label' => $partner->trade_name.' - '.$partner->pickup_address.($partner->pickup_number ? ', '.$partner->pickup_number : ''),
        'pickup_address' => $partner->pickup_address,
        'pickup_number' => $partner->pickup_number,
        'pickup_district' => $partner->pickup_district,
        'pickup_city' => $partner->pickup_city,
        'pickup_state' => $partner->pickup_state,
        'pickup_zip_code' => $partner->pickup_zip_code,
        'pickup_complement' => $partner->pickup_complement,
    ]);
@endphp
<div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
    <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Configuração</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Parceiro</label>
                <select name="partner_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="">Selecione</option>
                    @foreach ($partners as $partner)
                        <option value="{{ $partner->id }}" @selected(old('partner_id', $delivery->partner_id ?? null) == $partner->id)>{{ $partner->trade_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Valor da entrega</label>
                <input type="number" step="0.01" name="delivery_fee" value="{{ old('delivery_fee', $delivery->delivery_fee ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Pagamento do entregador</label>
                <input type="number" step="0.01" name="courier_payout_amount" value="{{ old('courier_payout_amount', $delivery->courier_payout_amount ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Distância estimada (km)</label>
                <input type="number" step="0.01" name="distance_km" value="{{ old('distance_km', $delivery->distance_km ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tempo estimado (min)</label>
                <input type="number" name="estimated_time_min" value="{{ old('estimated_time_min', $delivery->estimated_time_min ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Agendada para</label>
                <input type="datetime-local" name="scheduled_for" value="{{ old('scheduled_for', $delivery && $delivery->scheduled_for ? $delivery->scheduled_for->format('Y-m-d\TH:i') : null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Observações</label>
                <textarea name="notes" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $delivery->notes ?? null) }}</textarea>
            </div>
        </div>
    </div>

    <div class="grid gap-6">
        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-950">Coleta</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Buscar endereço do parceiro</label>
                    <input id="partner_address_search" list="partner_address_options" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Digite para buscar e preencher automaticamente">
                    <datalist id="partner_address_options">
                        @foreach ($partnerAddressOptions as $partnerOption)
                            <option value="{{ $partnerOption['label'] }}"></option>
                        @endforeach
                    </datalist>
                    <p class="mt-2 text-xs text-slate-500">Use esse campo se quiser puxar automaticamente o endereço do parceiro.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Endereço</label>
                    <input id="pickup_address" name="pickup_address" value="{{ old('pickup_address', $delivery->pickup_address ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Número</label>
                    <input id="pickup_number" name="pickup_number" value="{{ old('pickup_number', $delivery->pickup_number ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                    <input id="pickup_district" name="pickup_district" value="{{ old('pickup_district', $delivery->pickup_district ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                    <input id="pickup_city" name="pickup_city" value="{{ old('pickup_city', $delivery->pickup_city ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                    <input id="pickup_state" name="pickup_state" value="{{ old('pickup_state', $delivery->pickup_state ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                    <input id="pickup_zip_code" name="pickup_zip_code" value="{{ old('pickup_zip_code', $delivery->pickup_zip_code ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                    <input id="pickup_complement" name="pickup_complement" value="{{ old('pickup_complement', $delivery->pickup_complement ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Referência</label>
                    <input name="pickup_reference" value="{{ old('pickup_reference', $delivery->pickup_reference ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
            </div>
        </div>

        <div class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold text-slate-950">Entrega</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Endereço</label>
                    <input name="dropoff_address" value="{{ old('dropoff_address', $delivery->dropoff_address ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Número</label>
                    <input name="dropoff_number" value="{{ old('dropoff_number', $delivery->dropoff_number ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                    <input name="dropoff_district" value="{{ old('dropoff_district', $delivery->dropoff_district ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                    <input name="dropoff_city" value="{{ old('dropoff_city', $delivery->dropoff_city ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                    <input name="dropoff_state" value="{{ old('dropoff_state', $delivery->dropoff_state ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                    <input name="dropoff_zip_code" value="{{ old('dropoff_zip_code', $delivery->dropoff_zip_code ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                    <input name="dropoff_complement" value="{{ old('dropoff_complement', $delivery->dropoff_complement ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Referência</label>
                    <input name="dropoff_reference" value="{{ old('dropoff_reference', $delivery->dropoff_reference ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Destinatário</label>
                    <input name="recipient_name" value="{{ old('recipient_name', $delivery->recipient_name ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Telefone do destinatário</label>
                    <input name="recipient_phone" value="{{ old('recipient_phone', $delivery->recipient_phone ?? null) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.deliveries.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Cancelar</a>
    <button type="submit" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">{{ $submitLabel }}</button>
</div>

<script>
    (() => {
        const partnerOptions = @json($partnerAddressOptions);
        const searchInput = document.getElementById('partner_address_search');

        const fields = {
            pickup_address: document.getElementById('pickup_address'),
            pickup_number: document.getElementById('pickup_number'),
            pickup_district: document.getElementById('pickup_district'),
            pickup_city: document.getElementById('pickup_city'),
            pickup_state: document.getElementById('pickup_state'),
            pickup_zip_code: document.getElementById('pickup_zip_code'),
            pickup_complement: document.getElementById('pickup_complement'),
        };

        const fillPickupFields = () => {
            const selected = partnerOptions.find((item) => item.label === searchInput.value.trim());

            if (!selected) {
                return;
            }

            Object.entries(fields).forEach(([field, input]) => {
                if (input) {
                    input.value = selected[field] ?? '';
                }
            });
        };

        searchInput?.addEventListener('change', fillPickupFields);
        searchInput?.addEventListener('blur', fillPickupFields);
    })();
</script>
