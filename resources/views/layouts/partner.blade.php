@extends('layouts.app')

@section('body')
    <div class="min-h-screen bg-[#f5f7fb]">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
                <div class="w-12"></div>

                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#eef2ff] text-[#315fc9]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path d="M6 21V7a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14"/>
                            <path d="M14 21V4a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v17"/>
                            <path d="M9 10h2"/>
                            <path d="M9 14h2"/>
                            <path d="M16.5 8h.01"/>
                            <path d="M16.5 12h.01"/>
                            <path d="M16.5 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[17px] font-semibold text-slate-900">{{ auth()->user()->partner?->trade_name ?? auth()->user()->name }}</div>
                        <div class="text-sm text-slate-500">Portal do Parceiro</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" title="Sair">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <path d="M10 17l5-5-5-5"/>
                            <path d="M15 12H3"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="border-t border-slate-200">
                <nav class="mx-auto flex max-w-7xl items-center justify-center gap-10 overflow-x-auto px-4 text-sm lg:px-8">
                    <a href="{{ route('partner.portal', ['tab' => 'new']) }}" class="{{ ($currentTab ?? '') === 'new' ? 'border-[#284da3] text-[#284da3]' : 'border-transparent text-slate-500' }} flex items-center gap-2 border-b-2 px-2 py-4 font-medium">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        <span>Nova Entrega</span>
                    </a>
                    <a href="{{ route('partner.portal', ['tab' => 'progress']) }}" class="{{ ($currentTab ?? '') === 'progress' ? 'border-[#284da3] text-[#284da3]' : 'border-transparent text-slate-500' }} flex items-center gap-2 border-b-2 px-2 py-4 font-medium">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="m12 3 8 4.5-8 4.5L4 7.5 12 3Z"/>
                            <path d="M4 7.5V16.5L12 21l8-4.5V7.5"/>
                            <path d="M12 12v9"/>
                        </svg>
                        <span>Em Andamento</span>
                    </a>
                    <a href="{{ route('partner.portal', ['tab' => 'history']) }}" class="{{ ($currentTab ?? '') === 'history' ? 'border-[#284da3] text-[#284da3]' : 'border-transparent text-slate-500' }} flex items-center gap-2 border-b-2 px-2 py-4 font-medium">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <circle cx="12" cy="12" r="8"/>
                            <path d="M12 8v5l3 2"/>
                        </svg>
                        <span>Histórico</span>
                    </a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-5 lg:px-8">
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
        </main>
    </div>
@endsection
