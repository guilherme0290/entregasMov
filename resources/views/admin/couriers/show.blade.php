@php
    $pageTitle = 'Detalhes do Entregador';
    $statusLabel = match ($courier->availability_status->value) {
        'online' => 'Online',
        'offline' => 'Offline',
        'busy' => 'Ocupado',
        'blocked' => 'Bloqueado',
    };
@endphp
@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm text-slate-400">Entregador</p>
                    <h1 class="mt-1 text-3xl font-semibold text-slate-950">{{ $courier->user->name }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $courier->user->email ?: 'Sem e-mail' }} · {{ $courier->user->phone ?: 'Sem telefone' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">{{ $statusLabel }}</span>
                    <a href="{{ route('admin.couriers.edit', $courier) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Editar</a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-sm text-slate-500">Entregas hoje</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-950">{{ $courier->today_deliveries_count }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-sm text-slate-500">Entregas totais</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-950">{{ $courier->deliveries_count }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="text-sm text-slate-500">Avaliação</div>
                    <div class="mt-1 text-2xl font-semibold text-slate-950">{{ $courier->display_rating }}</div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-950">Acesso</h2>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Status do cadastro</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->is_active ? 'Ativo' : 'Inativo' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Status operacional</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $statusLabel }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Atualizado em</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->last_status_at?->format('d/m/Y H:i') ?: 'Sem registro' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-950">Dados pessoais</h2>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">CPF</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->tax_id ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Data de nascimento</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->birth_date?->format('d/m/Y') ?: 'Não informada' }}</dd>
                    </div>
                </dl>
            </section>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-950">Endereço</h2>
                <dl class="mt-5 grid gap-4 md:grid-cols-2 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4 md:col-span-2">
                        <dt class="text-slate-500">Endereço</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->address ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Número</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->number ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Bairro</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->district ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Cidade</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->city ?: 'Não informada' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">UF</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->state ?: 'Não informada' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">CEP</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->zip_code ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Complemento</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->complement ?: 'Não informado' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-950">Veículo</h2>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Tipo</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->vehicle_type ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Modelo</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->vehicle_model ?: 'Não informado' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Placa</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->vehicle_plate ?: 'Não informada' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-slate-500">Observações</dt>
                        <dd class="mt-1 font-medium text-slate-950">{{ $courier->notes ?: 'Sem observações' }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
@endsection
