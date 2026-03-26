@php
    $pageTitle = 'Detalhe da Entrega';
    $statusLabel = match ($delivery->status->value) {
        'pending' => 'Pendente',
        'available' => 'Disponível',
        'accepted' => 'Aceita',
        'in_pickup' => 'Coletada',
        'in_transit' => 'Em trânsito',
        'delivered' => 'Entregue',
        'canceled' => 'Cancelada',
        'rejected' => 'Recusada',
    };
@endphp
@extends('layouts.admin')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <section class="space-y-6">
            <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm text-slate-400">{{ $delivery->code }}</p>
                        <h2 class="mt-1 text-3xl font-semibold text-slate-950">{{ $delivery->partner->trade_name }}</h2>
                        <p class="mt-2 text-sm text-slate-500">Criada em {{ $delivery->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        @if ($canManageDelivery)
                            <a href="{{ route('admin.deliveries.edit', $delivery) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Editar</a>
                        @endif
                        <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm text-slate-500">Valor da entrega</div>
                        <div class="mt-1 text-2xl font-semibold text-slate-950">R$ {{ number_format($delivery->delivery_fee, 2, ',', '.') }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm text-slate-500">Pagamento do entregador</div>
                        <div class="mt-1 text-2xl font-semibold text-slate-950">R$ {{ number_format($delivery->courier_payout_amount ?? $delivery->delivery_fee, 2, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Coleta</h3>
                        <p class="mt-3 text-lg font-medium text-slate-950">{{ $delivery->pickup_address }}, {{ $delivery->pickup_number }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $delivery->pickup_district }} · {{ $delivery->pickup_city }}/{{ $delivery->pickup_state }}</p>
                        @if ($delivery->pickup_reference)
                            <p class="mt-2 text-sm text-slate-500">Ref.: {{ $delivery->pickup_reference }}</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Entrega</h3>
                        <p class="mt-3 text-lg font-medium text-slate-950">{{ $delivery->dropoff_address }}, {{ $delivery->dropoff_number }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $delivery->dropoff_district }} · {{ $delivery->dropoff_city }}/{{ $delivery->dropoff_state }}</p>
                        @if ($delivery->recipient_name)
                            <p class="mt-2 text-sm text-slate-500">Destinatário: {{ $delivery->recipient_name }} {{ $delivery->recipient_phone ? '· '.$delivery->recipient_phone : '' }}</p>
                        @endif
                    </div>
                </div>
            </article>

            <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-xl font-semibold text-slate-950">Timeline</h3>
                <div class="mt-6 space-y-4">
                    @forelse ($delivery->statusLogs as $log)
                        <div class="flex gap-4">
                            <div class="mt-1 h-3 w-3 rounded-full bg-blue-600"></div>
                            <div class="flex-1 rounded-2xl bg-slate-50 p-4">
                                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                                <div class="font-medium text-slate-950">
                                    {{ match ($log->new_status) {
                                        'pending' => 'Pendente',
                                        'available' => 'Disponível',
                                        'accepted' => 'Aceita',
                                        'in_pickup' => 'Coletada',
                                        'in_transit' => 'Em trânsito',
                                        'delivered' => 'Entregue',
                                        'canceled' => 'Cancelada',
                                        'rejected' => 'Recusada',
                                        default => $log->new_status,
                                    } }}
                                </div>
                                    <div class="text-sm text-slate-500">{{ $log->created_at?->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="mt-2 text-sm text-slate-500">Por {{ $log->user?->name ?? 'Sistema' }}</div>
                                @if ($log->notes)
                                    <div class="mt-2 text-sm text-slate-600">{{ $log->notes }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Sem movimentações registradas.</div>
                    @endforelse
                </div>
            </article>

            <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-xl font-semibold text-slate-950">Trocas de Entregador</h3>
                <div class="mt-6 space-y-4">
                    @forelse ($delivery->transfers->sortByDesc('created_at') as $transfer)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                                <div class="font-medium text-slate-950">
                                    {{ $transfer->previousCourier?->user?->name ?? 'Sem entregador' }}
                                    →
                                    {{ $transfer->newCourier?->user?->name ?? 'Sem entregador' }}
                                </div>
                                <div class="text-sm text-slate-500">{{ $transfer->created_at?->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="mt-2 text-sm text-slate-600">Motivo: {{ $transfer->reason }}</div>
                            <div class="mt-1 text-sm text-slate-500">Por {{ $transfer->transferredBy?->name ?? 'Sistema' }}</div>
                            @if ($transfer->notes)
                                <div class="mt-2 text-sm text-slate-600">{{ $transfer->notes }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Nenhuma troca registrada.</div>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="space-y-6">
            <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-xl font-semibold text-slate-950">Operação</h3>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-slate-500">Parceiro</div>
                        <div class="mt-1 font-medium text-slate-950">{{ $delivery->partner->trade_name }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-slate-500">Entregador atual</div>
                        <div class="mt-1 font-medium text-slate-950">{{ $delivery->courier?->user?->name ?? 'Não atribuído' }}</div>
                    </div>
                    @if ($delivery->earning)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="text-slate-500">Ganho gerado</div>
                            <div class="mt-1 font-medium text-slate-950">
                                R$ {{ number_format($delivery->earning->net_amount, 2, ',', '.') }} ·
                                {{ match ($delivery->earning->payment_status->value) {
                                    'pending' => 'Pendente',
                                    'released' => 'Liberado',
                                    'paid' => 'Pago',
                                    'blocked' => 'Bloqueado',
                                } }}
                            </div>
                        </div>
                    @endif
                </div>
            </article>

            @if ($canAssignCourier)
                <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-xl font-semibold text-slate-950">Atribuir Entregador</h3>
                    <p class="mt-2 text-sm text-slate-500">Use esta ação para vincular manualmente um entregador antes da aceitação pelo app.</p>

                    <form method="POST" action="{{ route('admin.deliveries.assign-courier', $delivery) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Entregador</label>
                            <select name="courier_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                                <option value="">Selecione</option>
                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier->id }}">
                                        {{ $courier->user->name }} · {{ $courier->availability_status->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-blue-700 px-4 py-3 text-sm font-medium text-white">Atribuir entregador</button>
                    </form>
                </article>
            @endif

            @if ($canManageDelivery)
                <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-xl font-semibold text-slate-950">Cancelar Entrega</h3>
                    <p class="mt-2 text-sm text-slate-500">Use esta ação para cancelar a entrega antes da coleta.</p>

                    <form method="POST" action="{{ route('admin.deliveries.cancel', $delivery) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Motivo</label>
                            <textarea name="reason" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Informe o motivo do cancelamento"></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-rose-600 px-4 py-3 text-sm font-medium text-white">Cancelar entrega</button>
                    </form>
                </article>
            @endif

            @if ($canTransferCourier)
                <article class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-xl font-semibold text-slate-950">Trocar Entregador</h3>
                    <p class="mt-2 text-sm text-slate-500">Use esta ação de contingência para transferir a corrida em andamento para outro entregador.</p>

                    <form method="POST" action="{{ route('admin.deliveries.transfer-courier', $delivery) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Novo entregador</label>
                            <select name="courier_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                                <option value="">Selecione</option>
                                @foreach ($couriers as $courier)
                                    @continue($courier->id === $delivery->courier_id)
                                    <option value="{{ $courier->id }}">
                                        {{ $courier->user->name }} · {{ $courier->availability_status->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Motivo</label>
                            <input name="reason" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Ex.: acidente, pane, mal súbito">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Observações</label>
                            <textarea name="notes" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Informe detalhes operacionais da troca"></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-amber-600 px-4 py-3 text-sm font-medium text-white">Trocar entregador</button>
                    </form>
                </article>
            @endif
        </section>
    </div>
@endsection
