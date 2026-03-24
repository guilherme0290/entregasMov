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

    <div class="mt-6 grid gap-5 xl:grid-cols-3">
        @forelse ($couriers as $courier)
            @php
                $status = $courier->availability_status->value;
                $statusLabel = match ($status) {
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'busy' => 'Ocupado',
                    'blocked' => 'Bloqueado',
                };
                $statusColor = match ($status) {
                    'online' => 'bg-emerald-400',
                    'offline' => 'bg-slate-300',
                    'busy' => 'bg-amber-400',
                    'blocked' => 'bg-rose-400',
                };
            @endphp

            <article class="rounded-[22px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#edf2ff] text-base font-semibold text-[#335ec4]">
                            {{ $courier->initials }}
                        </div>
                        <div>
                            <h2 class="text-[26px] font-semibold leading-none text-slate-900">{{ $courier->user->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $courier->user->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <span class="h-2.5 w-2.5 rounded-full {{ $statusColor }}"></span>
                        <span>{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-3 text-center text-sm">
                    <div class="rounded-2xl bg-[#f6f7fb] px-4 py-3">
                        <div class="text-slate-500">Hoje</div>
                        <div class="mt-1 text-[28px] font-semibold leading-none text-slate-900">{{ $courier->today_deliveries_count }}</div>
                    </div>
                    <div class="rounded-2xl bg-[#f6f7fb] px-4 py-3">
                        <div class="text-slate-500">Total</div>
                        <div class="mt-1 text-[28px] font-semibold leading-none text-slate-900">{{ $courier->deliveries_count }}</div>
                    </div>
                    <div class="rounded-2xl bg-[#f6f7fb] px-4 py-3">
                        <div class="text-slate-500">Avaliação</div>
                        <div class="mt-1 flex items-center justify-center gap-1 text-[26px] font-semibold leading-none text-slate-900">
                            <span class="text-[20px] text-amber-400">★</span>
                            <span>{{ $courier->display_rating }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        {{ $courier->vehicle_type ?: 'Veículo não informado' }}
                    </div>
                    <a href="{{ route('admin.couriers.edit', $courier) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Editar</a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-[22px] bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200">Nenhum entregador cadastrado.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $couriers->links() }}
    </div>
@endsection
