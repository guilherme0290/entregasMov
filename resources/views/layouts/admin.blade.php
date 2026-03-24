@extends('layouts.app')

@section('body')
    @php
        $adminMenu = [
            ['route' => 'admin.dashboard', 'label' => 'Painel Geral', 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'dashboard'],
            ['route' => 'admin.deliveries.index', 'label' => 'Entregas', 'active' => request()->routeIs('admin.deliveries.*'), 'icon' => 'box'],
            ['route' => 'admin.couriers.index', 'label' => 'Entregadores', 'active' => request()->routeIs('admin.couriers.*'), 'icon' => 'truck'],
            ['route' => 'admin.partners.index', 'label' => 'Parceiros', 'active' => request()->routeIs('admin.partners.*'), 'icon' => 'building'],
            ['route' => 'admin.reports.index', 'label' => 'Relatórios', 'active' => request()->routeIs('admin.reports.*'), 'icon' => 'chart'],
            ['route' => 'admin.earnings.index', 'label' => 'Ganhos', 'active' => request()->routeIs('admin.earnings.*'), 'icon' => 'coin'],
        ];
    @endphp

    @php
        $iconMap = [
            'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>',
            'box' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="m12 3 8 4.5-8 4.5L4 7.5 12 3Z"/><path d="M4 7.5V16.5L12 21l8-4.5V7.5"/><path d="M12 12v9"/></svg>',
            'truck' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M10 17H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h10v11"/><path d="M10 9h5l3 3v5h-8V9Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="17.5" cy="17.5" r="1.5"/></svg>',
            'building' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 21V7a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v14"/><path d="M14 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v17"/><path d="M8 10h2"/><path d="M8 14h2"/><path d="M8 18h2"/><path d="M17 8h1"/><path d="M17 12h1"/><path d="M17 16h1"/></svg>',
            'chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 19h16"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/></svg>',
            'coin' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><circle cx="12" cy="12" r="8"/><path d="M9.5 9.5c0-.8.9-1.5 2.5-1.5s2.5.7 2.5 1.5-.9 1.5-2.5 1.5-2.5.7-2.5 1.5.9 1.5 2.5 1.5 2.5-.7 2.5-1.5"/><path d="M12 7.5v9"/></svg>',
        ];
    @endphp

    <div class="flex min-h-screen bg-[#f5f7fb]">
        <aside class="hidden w-[216px] flex-col bg-[#181f2d] px-4 py-5 text-white lg:flex">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#2f63dd]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5">
                        <path d="M3 7.5 12 3l9 4.5v9L12 21 3 16.5v-9Z"/>
                        <path d="M8 12h8"/>
                        <path d="M8 15h4"/>
                    </svg>
                </div>
                <span class="text-[17px] font-semibold tracking-tight">EntregasMov</span>
            </a>

            <div class="mt-12 px-3 text-xs font-medium uppercase tracking-[0.16em] text-slate-500">Menu Principal</div>

            <nav class="mt-4 space-y-1 text-[15px]">
                @foreach ($adminMenu as $item)
                    <a href="{{ route($item['route']) }}" class="{{ $item['active'] ? 'bg-[#232c3f] text-[#3d73f0]' : 'text-slate-300 hover:bg-[#202838] hover:text-white' }} flex items-center gap-3 rounded-xl px-3 py-3 transition">
                        <span class="opacity-90">{!! $iconMap[$item['icon']] !!}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto px-2">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-800 px-4 py-3 text-left text-sm text-slate-300 transition hover:bg-[#202838] hover:text-white">
                    Sair
                </button>
            </form>
        </aside>

        <main class="flex-1">
            <header class="border-b border-slate-200 bg-white px-6 py-4 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 text-slate-700">
                            <rect x="5" y="4" width="14" height="16" rx="2"/>
                            <path d="M9 8h6"/>
                        </svg>
                        <span class="text-[17px] font-semibold text-slate-800">Painel Administrativo</span>
                    </div>
                    <div class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                        {{ auth()->user()->name }}
                    </div>
                </div>
            </header>

        <div class="p-6 lg:p-8">

            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
        </main>
    </div>
@endsection
