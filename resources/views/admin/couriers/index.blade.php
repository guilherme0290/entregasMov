@php
    $pageTitle = 'Entregadores';
@endphp
@extends('layouts.admin')

@section('content')
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold tracking-tight text-slate-900">Entregadores</h1>
            <p class="mt-1 text-sm text-slate-500">Gerencie sua equipe de entregadores</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.couriers.index') }}">
                <div class="relative">
                    <select name="availability_status" onchange="this.form.submit()" class="appearance-none rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-10 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-500">
                        <option value="">Filtrar status</option>
                        <option value="online" @selected(request('availability_status') === 'online')>Online</option>
                        <option value="offline" @selected(request('availability_status') === 'offline')>Offline</option>
                        <option value="busy" @selected(request('availability_status') === 'busy')>Ocupado</option>
                        <option value="blocked" @selected(request('availability_status') === 'blocked')>Bloqueado</option>
                    </select>
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M4 6h16"/>
                            <path d="M7 12h10"/>
                            <path d="M10 18h4"/>
                        </svg>
                    </span>
                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </span>
                </div>
            </form>

            <a href="{{ route('admin.couriers.create') }}" class="rounded-2xl bg-[#2f63dd] px-5 py-3 text-sm font-medium text-white shadow-sm">Novo entregador</a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-[22px] bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Entregador</th>
                        <th class="px-6 py-4 font-medium">Telefone</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Hoje</th>
                        <th class="px-6 py-4 font-medium">Total</th>
                        <th class="px-6 py-4 font-medium">Veículo</th>
                        <th class="px-6 py-4 font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($couriers as $courier)
                        @php
                            $status = $courier->availability_status->value;
                            $statusLabel = match ($status) {
                                'online' => 'Online',
                                'offline' => 'Offline',
                                'busy' => 'Ocupado',
                                'blocked' => 'Bloqueado',
                            };
                            $statusClass = match ($status) {
                                'online' => 'bg-emerald-50 text-emerald-600',
                                'offline' => 'bg-slate-100 text-slate-600',
                                'busy' => 'bg-amber-50 text-amber-600',
                                'blocked' => 'bg-rose-50 text-rose-600',
                            };
                        @endphp
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#edf2ff] text-sm font-semibold text-[#335ec4]">
                                        {{ $courier->initials }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $courier->user->name }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $courier->user->email ?: 'Sem e-mail' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-600">{{ $courier->user->phone ?: 'Não informado' }}</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-6 py-5 text-slate-900">{{ $courier->today_deliveries_count }}</td>
                            <td class="px-6 py-5 text-slate-900">{{ $courier->deliveries_count }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $courier->vehicle_type ?: 'Não informado' }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.couriers.show', $courier) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Visualizar</a>
                                    <a href="{{ route('admin.couriers.edit', $courier) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Editar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Nenhum entregador cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $couriers->links() }}
    </div>
@endsection
