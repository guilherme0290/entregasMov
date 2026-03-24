@php
    $pageTitle = 'Gestão de Entregas';
@endphp
@extends('layouts.admin')

@section('content')
    <section>
        <h1 class="text-[44px] leading-none font-semibold tracking-tight text-slate-900">Gestão de Entregas</h1>
        <p class="mt-2 text-lg text-slate-500">Acompanhe todas as entregas</p>
    </section>

    <div class="mt-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <form method="GET" class="max-w-md flex-1">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <circle cx="11" cy="11" r="6"/><path d="m20 20-3.5-3.5"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar entrega..."
                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm text-slate-700 outline-none transition focus:border-blue-400"
                >
            </div>
        </form>
        <a href="{{ route('admin.deliveries.create') }}" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">Nova entrega</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-[15px] text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">ID</th>
                        <th class="px-6 py-4 font-medium">Parceiro</th>
                        <th class="px-6 py-4 font-medium">Endereço de Entrega</th>
                        <th class="px-6 py-4 font-medium">Entregador</th>
                        <th class="px-6 py-4 font-medium">Valor</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveries as $delivery)
                        @php
                            $statusKey = $delivery->status->value;
                            $statusMeta = $statusMap[$statusKey] ?? ['label' => str($statusKey)->headline(), 'class' => 'bg-slate-100 text-slate-600'];
                        @endphp
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-5 font-medium text-slate-950">{{ $delivery->code }}</td>
                            <td class="px-6 py-5 text-[15px] font-medium text-slate-800">{{ $delivery->partner->trade_name }}</td>
                            <td class="px-6 py-5 text-[15px] text-slate-500">{{ $delivery->dropoff_address }}, {{ $delivery->dropoff_number }}</td>
                            <td class="px-6 py-5 text-[15px] {{ $delivery->courier?->user ? 'text-slate-800' : 'italic text-slate-400' }}">{{ $delivery->courier?->user?->name ?? 'Não atribuído' }}</td>
                            <td class="px-6 py-5 text-[15px] font-semibold text-slate-800">R$ {{ number_format($delivery->delivery_fee, 0, ',', '.') }}</td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium {{ $statusMeta['class'] }}">
                                    <span class="h-2 w-2 rounded-full bg-current opacity-90"></span>
                                    {{ $statusMeta['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700">Abrir</a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($deliveries->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Nenhuma entrega cadastrada.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $deliveries->links() }}
        </div>
    </div>
@endsection
