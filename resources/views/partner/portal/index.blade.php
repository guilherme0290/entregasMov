@extends('layouts.partner')

@section('content')
    @if ($currentTab === 'new')
        <section class="mx-auto max-w-[736px] rounded-[20px] bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:p-8">
            <h1 class="text-[18px] font-semibold text-slate-900">Solicitar Nova Entrega</h1>

            <form method="POST" action="{{ route('partner.deliveries.store') }}" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-500">Endereço de Coleta</label>
                    <input name="pickup_address" value="{{ old('pickup_address', trim($partner->pickup_address.($partner->pickup_number ? ', '.$partner->pickup_number : ''))) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-[15px]">
                    <input type="hidden" name="pickup_number" value="{{ old('pickup_number', $partner->pickup_number) }}">
                    <input type="hidden" name="pickup_district" value="{{ old('pickup_district', $partner->pickup_district) }}">
                    <input type="hidden" name="pickup_city" value="{{ old('pickup_city', $partner->pickup_city) }}">
                    <input type="hidden" name="pickup_state" value="{{ old('pickup_state', $partner->pickup_state) }}">
                    <input type="hidden" name="pickup_zip_code" value="{{ old('pickup_zip_code', $partner->pickup_zip_code) }}">
                    <input type="hidden" name="pickup_complement" value="{{ old('pickup_complement', $partner->pickup_complement) }}">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-500">Endereço de Entrega</label>
                    <input name="dropoff_address" value="{{ old('dropoff_address') }}" placeholder="Rua, número, bairro" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-[15px]">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-500">Observações</label>
                    <textarea name="notes" rows="3" placeholder="Instruções adicionais..." class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-[15px]">{{ old('notes') }}</textarea>
                </div>

                <input type="hidden" name="dropoff_number" value="{{ old('dropoff_number') }}">
                <input type="hidden" name="dropoff_district" value="{{ old('dropoff_district') }}">
                <input type="hidden" name="dropoff_city" value="{{ old('dropoff_city', $partner->pickup_city) }}">
                <input type="hidden" name="dropoff_state" value="{{ old('dropoff_state', $partner->pickup_state) }}">
                <input type="hidden" name="dropoff_zip_code" value="{{ old('dropoff_zip_code') }}">
                <input type="hidden" name="recipient_name" value="{{ old('recipient_name') }}">
                <input type="hidden" name="recipient_phone" value="{{ old('recipient_phone') }}">
                <input type="hidden" name="courier_payout_amount" value="{{ old('courier_payout_amount', $partner->default_delivery_fee) }}">
                <input type="hidden" name="scheduled_for" value="{{ old('scheduled_for') }}">

                <button type="submit" class="w-full rounded-2xl bg-[#284da3] px-4 py-4 text-sm font-medium text-white">Solicitar Entrega</button>
            </form>
        </section>
    @endif

    @if ($currentTab === 'progress')
        <section class="mx-auto max-w-[800px] space-y-4">
            @forelse ($inProgressDeliveries as $delivery)
                @php
                    $statusConfig = match ($delivery->status->value) {
                        'pending', 'available' => ['label' => 'Pendente', 'class' => 'bg-amber-50 text-amber-500', 'dot' => 'bg-amber-300'],
                        'accepted' => ['label' => 'Aceita', 'class' => 'bg-blue-50 text-blue-600', 'dot' => 'bg-blue-500'],
                        'in_pickup' => ['label' => 'Coletada', 'class' => 'bg-blue-50 text-blue-600', 'dot' => 'bg-blue-500'],
                        'in_transit' => ['label' => 'Em trânsito', 'class' => 'bg-blue-50 text-blue-600', 'dot' => 'bg-blue-500'],
                        default => ['label' => ucfirst($delivery->status->value), 'class' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'],
                    };
                @endphp

                <article class="rounded-[20px] bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-[18px] font-semibold leading-tight text-slate-900">
                                {{ $delivery->dropoff_address }}{{ $delivery->dropoff_number ? ', '.$delivery->dropoff_number : '' }}
                            </h2>
                            @if ($delivery->courier?->user)
                                <p class="mt-2 text-sm text-slate-500">
                                    Entregador: <span class="font-medium text-slate-700">{{ $delivery->courier->user->name }}</span>
                                </p>
                            @endif
                            <p class="mt-2 text-sm text-slate-500">
                                Valor: <span class="font-semibold text-slate-900">R$ {{ number_format($delivery->delivery_fee, 0, ',', '.') }}</span>
                            </p>
                        </div>

                        <span class="{{ $statusConfig['class'] }} inline-flex items-center gap-2 self-start rounded-full px-3 py-1 text-xs font-medium">
                            <span class="h-2 w-2 rounded-full {{ $statusConfig['dot'] }}"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                </article>
            @empty
                <div class="rounded-[20px] bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-slate-200">Nenhuma entrega em andamento.</div>
            @endforelse
        </section>
    @endif

    @if ($currentTab === 'history')
        <section class="overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-medium">Data</th>
                            <th class="px-6 py-4 font-medium">Endereço</th>
                            <th class="px-6 py-4 font-medium">Entregador</th>
                            <th class="px-6 py-4 font-medium">Valor</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historyDeliveries as $delivery)
                            <tr class="border-t border-slate-100">
                                <td class="px-6 py-5">{{ $delivery->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-5">{{ $delivery->dropoff_address }}, {{ $delivery->dropoff_number }}</td>
                                <td class="px-6 py-5">{{ $delivery->courier?->user?->name ?? 'Não atribuído' }}</td>
                                <td class="px-6 py-5">R$ {{ number_format($delivery->delivery_fee, 2, ',', '.') }}</td>
                                <td class="px-6 py-5">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $delivery->status->value }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Nenhuma entrega no histórico.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
