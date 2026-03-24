@php
    $pageTitle = 'Relatórios';
    $maxDeliveries = max(1, $months->max('deliveries'));
    $maxRevenue = max(1, $months->max('revenue'));
@endphp
@extends('layouts.admin')

@section('content')
    <div>
        <h1 class="text-[28px] font-semibold tracking-tight text-slate-900">Relatórios</h1>
        <p class="mt-1 text-sm text-slate-500">Análise de desempenho e métricas</p>
    </div>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <article class="rounded-[22px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[24px] font-semibold text-slate-800">Entregas por Mês</h2>

            <div class="mt-6">
                <div class="flex h-[250px] items-end gap-5 border-l border-b border-dashed border-slate-300 px-5 pb-8 pt-3">
                    @foreach ($months as $month)
                        <div class="flex flex-1 flex-col items-center justify-end gap-3">
                            <div class="relative flex h-[200px] w-full items-end justify-center">
                                <div
                                    class="w-full max-w-[92px] rounded-t-[8px] bg-[#26439a]"
                                    style="height: {{ max(12, ($month['deliveries'] / $maxDeliveries) * 190) }}px;"
                                ></div>
                            </div>
                            <span class="text-sm text-slate-500">{{ ucfirst($month['label']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>

        <article class="rounded-[22px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[24px] font-semibold text-slate-800">Receita Mensal (R$)</h2>

            <div class="mt-6">
                <div class="relative h-[258px] border-l border-b border-dashed border-slate-300 px-5 pb-8 pt-3">
                    <div class="absolute inset-x-5 bottom-8 top-3">
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="h-full w-full overflow-visible">
                            @php
                                $points = $months->values()->map(function (array $month, int $index) use ($months, $maxRevenue) {
                                    $count = max(1, $months->count() - 1);
                                    $x = $count === 0 ? 0 : ($index / $count) * 100;
                                    $y = 100 - (($month['revenue'] / $maxRevenue) * 78 + 8);
                                    return ['x' => $x, 'y' => $y];
                                });
                                $path = $points->map(fn (array $point, int $index) => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['y'])->implode(' ');
                            @endphp

                            <path d="{{ $path }}" fill="none" stroke="#37b679" stroke-width="1.2"></path>

                            @foreach ($points as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="1.6" fill="#37b679"></circle>
                            @endforeach
                        </svg>
                    </div>

                    <div class="absolute inset-x-5 bottom-0 flex justify-between">
                        @foreach ($months as $month)
                            <span class="text-sm text-slate-500">{{ ucfirst($month['label']) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <article class="rounded-[22px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[24px] font-semibold text-slate-800">Top 5 Entregadores</h2>

            <div class="mt-5 space-y-3">
                @forelse ($topCouriers as $index => $courier)
                    <div class="flex items-center justify-between rounded-2xl px-1 py-1">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#eef2ff] text-sm font-semibold text-[#4f66bc]">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-[15px] font-medium text-slate-700">{{ $courier->user->name }}</span>
                        </div>
                        <span class="text-[15px] font-semibold text-slate-700">{{ $courier->deliveries_count }} entregas</span>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-500">Sem dados.</div>
                @endforelse
            </div>
        </article>

        <article class="rounded-[22px] bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-[24px] font-semibold text-slate-800">Top 5 Parceiros (Volume Mensal)</h2>

            <div class="mt-5 space-y-3">
                @forelse ($topPartners as $index => $partner)
                    <div class="flex items-center justify-between rounded-2xl px-1 py-1">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-sm font-semibold text-emerald-500">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-[15px] font-medium text-slate-700">{{ $partner->trade_name }}</span>
                        </div>
                        <span class="text-[15px] font-semibold text-slate-700">{{ $partner->monthly_deliveries_count }}/mês</span>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-500">Sem dados.</div>
                @endforelse
            </div>
        </article>
    </section>
@endsection
