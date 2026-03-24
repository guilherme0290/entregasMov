@php
    $pageTitle = 'Empresas Parceiras';
@endphp
@extends('layouts.admin')

@section('content')
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold tracking-tight text-slate-900">Empresas Parceiras</h1>
            <p class="mt-1 text-sm text-slate-500">Gerencie suas empresas parceiras e valores</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="rounded-2xl bg-[#2f63dd] px-5 py-3 text-sm font-medium text-white shadow-sm">Novo parceiro</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-[22px] bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-white text-[15px] text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Empresa</th>
                        <th class="px-6 py-4 font-medium">Endereço</th>
                        <th class="px-6 py-4 font-medium">Entrega Padrão</th>
                        <th class="px-6 py-4 font-medium">Entrega Urgente</th>
                        <th class="px-6 py-4 font-medium">Vol. Mensal</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#eef2ff] text-[#315fc9]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                            <path d="M6 21V7a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14"/>
                                            <path d="M14 21V4a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v17"/>
                                            <path d="M9 10h2"/>
                                            <path d="M9 14h2"/>
                                            <path d="M16.5 8h.01"/>
                                            <path d="M16.5 12h.01"/>
                                            <path d="M16.5 16h.01"/>
                                        </svg>
                                    </div>
                                    <div class="font-medium text-[15px] text-slate-900">{{ $partner->trade_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-[15px] text-slate-500">
                                    {{ $partner->pickup_address }}{{ $partner->pickup_number ? ', '.$partner->pickup_number : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 font-semibold text-slate-900">R$ {{ number_format($partner->default_delivery_fee, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 font-semibold text-slate-900">R$ {{ number_format($partner->urgent_delivery_fee, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-[15px] text-slate-900">{{ $partner->monthly_deliveries_count }}</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full {{ $partner->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} px-3 py-1 text-xs font-medium">
                                    {{ $partner->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Nenhum parceiro cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $partners->links() }}
        </div>
    </div>
@endsection
