@php
    $pageTitle = 'Ganhos dos Entregadores';
@endphp
@extends('layouts.admin')

@section('content')
    <section class="grid gap-4 md:grid-cols-3">
        <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Pendentes</p>
            <p class="mt-3 text-3xl font-semibold text-slate-950">R$ {{ number_format($summary['pending'], 2, ',', '.') }}</p>
        </article>
        <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Liberados</p>
            <p class="mt-3 text-3xl font-semibold text-slate-950">R$ {{ number_format($summary['released'], 2, ',', '.') }}</p>
        </article>
        <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Pagos</p>
            <p class="mt-3 text-3xl font-semibold text-slate-950">R$ {{ number_format($summary['paid'], 2, ',', '.') }}</p>
        </article>
    </section>

    <div class="mt-6 flex items-center justify-between gap-4">
        <form method="GET">
            <select name="payment_status" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                <option value="">Todos os status</option>
                @foreach (['pending', 'released', 'paid', 'blocked'] as $status)
                    <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <button type="submit" class="ml-2 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700">Filtrar</button>
        </form>
    </div>

    <div class="mt-6 overflow-hidden rounded-[28px] bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Entrega</th>
                        <th class="px-6 py-4 font-medium">Entregador</th>
                        <th class="px-6 py-4 font-medium">Parceiro</th>
                        <th class="px-6 py-4 font-medium">Valor</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($earnings as $earning)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-5 font-medium text-slate-950">{{ $earning->delivery->code }}</td>
                            <td class="px-6 py-5">{{ $earning->courier->user->name }}</td>
                            <td class="px-6 py-5">{{ $earning->delivery->partner->trade_name }}</td>
                            <td class="px-6 py-5">R$ {{ number_format($earning->net_amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $earning->payment_status->value }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <form method="POST" action="{{ route('admin.earnings.update', $earning) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="payment_status" class="rounded-2xl border border-slate-200 px-3 py-2 text-sm">
                                        @foreach (['pending', 'released', 'paid', 'blocked'] as $status)
                                            <option value="{{ $status }}" @selected($earning->payment_status->value === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700">Salvar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">Nenhum ganho gerado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $earnings->links() }}
        </div>
    </div>
@endsection
