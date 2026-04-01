@php
    $pageTitle = 'Visão Geral';
    $statusLabels = [
        'pending' => 'Pendente',
        'available' => 'Disponível',
        'accepted' => 'Aceita',
        'in_pickup' => 'Coletada',
        'in_transit' => 'Em trânsito',
        'delivered' => 'Entregue',
        'canceled' => 'Cancelada',
        'rejected' => 'Recusada',
    ];
@endphp
@extends('layouts.admin')

@section('content')
    <section>
        <h1 class="text-[44px] leading-none font-semibold tracking-tight text-slate-900">Visão Geral</h1>
        <p class="mt-2 text-lg text-slate-500">Resumo das operações de hoje</p>
    </section>

    <section class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Entregas Hoje</p>
                    <p class="mt-3 text-[43px] leading-none font-semibold text-slate-900">{{ $stats['deliveries_today'] }}</p>
                    <p class="mt-3 text-sm font-medium text-emerald-500">+12% vs ontem</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="m12 3 8 4.5-8 4.5L4 7.5 12 3Z"/><path d="M4 7.5V16.5L12 21l8-4.5V7.5"/><path d="M12 12v9"/>
                    </svg>
                </div>
            </div>
        </article>

        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Entregadores Ativos</p>
                    <p class="mt-3 text-[43px] leading-none font-semibold text-slate-900">{{ $stats['active_couriers'] }}</p>
                    <p class="mt-3 text-sm text-slate-400">de {{ $stats['total_couriers'] }} cadastrados</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M10 17H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h10v11"/><path d="M10 9h5l3 3v5h-8V9Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="17.5" cy="17.5" r="1.5"/>
                    </svg>
                </div>
            </div>
        </article>

        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Concluídas</p>
                    <p class="mt-3 text-[43px] leading-none font-semibold text-slate-900">{{ $stats['completed_today'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <circle cx="12" cy="12" r="8"/><path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </article>

        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Receita Gerada</p>
                    <p class="mt-3 text-[43px] leading-none font-semibold text-slate-900">R$ {{ number_format($stats['revenue_today'], 0, ',', '.') }}</p>
                    <p class="mt-3 text-sm font-medium text-emerald-500">+8% vs ontem</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M12 3v18"/><path d="M16 7.5c0-1.7-1.8-3-4-3s-4 1.3-4 3 1.8 3 4 3 4 1.3 4 3-1.8 3-4 3-4-1.3-4-3"/>
                    </svg>
                </div>
            </div>
        </article>

        <article class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Taxa de Sucesso</p>
                    <p class="mt-3 text-[43px] leading-none font-semibold text-slate-900">{{ $stats['success_rate'] }}%</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M4 16 9 11l3 3 8-8"/><path d="M20 10V6h-4"/>
                    </svg>
                </div>
            </div>
        </article>
    </section>

    @php
        $maxMonthly = max($monthlyDeliveries->max('total'), 1);
    @endphp

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.05fr_1fr]">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[26px] font-semibold tracking-tight text-slate-900">Entregas Mensais</h2>
            <div class="mt-6 flex h-[270px] items-end gap-5 rounded-3xl border border-slate-100 px-8 pb-10 pt-6">
                @foreach ($monthlyDeliveries as $month)
                    @php
                        $height = max(($month['total'] / $maxMonthly) * 180, 24);
                    @endphp
                    <div class="flex flex-1 flex-col items-center justify-end gap-3">
                        <div class="w-full rounded-t-2xl bg-[#2b4b9a]" style="height: {{ $height }}px;"></div>
                        <span class="text-sm text-slate-400">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[26px] font-semibold tracking-tight text-slate-900">Entregas Recentes</h2>
            <div class="mt-6 space-y-3">
                @forelse ($recentDeliveries as $delivery)
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-4">
                        <div>
                            <div class="text-lg font-semibold text-slate-800">{{ $delivery->partner->trade_name }}</div>
                            <div class="text-sm text-slate-400">{{ $delivery->dropoff_address }}, {{ $delivery->dropoff_number }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-lg font-semibold text-slate-800">R$ {{ number_format($delivery->delivery_fee, 0, ',', '.') }}</div>
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-sm font-medium text-amber-500">{{ $statusLabels[$delivery->status->value] ?? str($delivery->status->value)->headline() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-500">Nenhuma entrega cadastrada ainda.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
