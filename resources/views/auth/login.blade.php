@extends('layouts.app')

@section('body')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#07111f] px-4 py-10">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(55,84,214,0.35),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(27,143,118,0.18),_transparent_28%)]"></div>

        <div class="relative grid w-full max-w-6xl overflow-hidden rounded-[36px] border border-white/10 bg-white shadow-[0_30px_90px_rgba(2,12,27,0.55)] lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative hidden overflow-hidden bg-[linear-gradient(160deg,#1535c0_0%,#1a2b87_50%,#09101f_100%)] p-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_18%,rgba(255,255,255,0.16),transparent_20%),radial-gradient(circle_at_82%_78%,rgba(255,255,255,0.10),transparent_18%)]"></div>

                <div class="relative">
                    <div class="inline-flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-[24px] bg-white/12 text-2xl font-semibold shadow-inner shadow-white/10 backdrop-blur-sm">E</div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.26em] text-blue-100/70">Plataforma</p>
                            <h1 class="mt-1 text-4xl font-semibold tracking-tight">EntregasMov</h1>
                        </div>
                    </div>

                    <p class="mt-10 max-w-xl text-[28px] font-semibold leading-[1.25] text-white">
                        Plataforma completa para gestão de entregas, parceiros e entregadores.
                    </p>
                </div>

                <div class="relative space-y-4">
                    <div class="rounded-[24px] border border-white/10 bg-white/7 px-5 py-4 backdrop-blur-sm">
                        <p class="text-sm font-medium text-white">Administração centralizada</p>
                        <p class="mt-1 text-sm text-blue-50/75">Cadastros, operação, atribuição de entregas e relatórios no mesmo ambiente.</p>
                    </div>
                </div>
            </section>

            <section class="bg-[#fbfcff] p-8 lg:p-14">
                <div class="mx-auto max-w-md">
                    <div class="flex items-center gap-3 lg:hidden">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[18px] bg-[#eaf0ff] text-lg font-semibold text-[#2641b5]">E</div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Plataforma</p>
                            <h1 class="text-xl font-semibold text-slate-950">EntregasMov</h1>
                        </div>
                    </div>

                    <div class="mt-8 lg:mt-0">
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-400">Acesso</p>
                        <h2 class="mt-3 text-[40px] font-semibold tracking-tight text-slate-950">Entrar no painel</h2>
                        <p class="mt-3 text-[15px] leading-7 text-slate-600">
                            Use seu e-mail ou telefone para acessar o ambiente administrativo, parceiro ou entregador.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-10 space-y-5">
                        @csrf

                        <div>
                            <label for="login" class="mb-2 block text-sm font-medium text-slate-700">E-mail ou telefone</label>
                            <input
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                class="w-full rounded-[20px] border border-slate-200 bg-white px-4 py-3.5 text-[15px] outline-none transition focus:border-[#3150d4] focus:ring-4 focus:ring-blue-100"
                                placeholder="Digite seu e-mail ou telefone"
                            />
                            @error('login')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label for="password" class="block text-sm font-medium text-slate-700">Senha</label>
                                <button type="button" id="toggle-password" class="text-sm font-medium text-slate-400 transition hover:text-slate-600">
                                    Mostrar
                                </button>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full rounded-[20px] border border-slate-200 bg-white px-4 py-3.5 text-[15px] outline-none transition focus:border-[#3150d4] focus:ring-4 focus:ring-blue-100"
                                placeholder="Digite sua senha"
                            />
                        </div>

                        <div class="flex items-center justify-between gap-4 pt-1">
                            <label class="flex items-center gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#3150d4] focus:ring-[#3150d4]">
                                Manter conectado
                            </label>
                            <span class="text-sm text-slate-400">Acesso seguro</span>
                        </div>

                        <button type="submit" class="w-full rounded-[20px] bg-[#3150d4] px-4 py-3.5 text-base font-medium text-white transition hover:bg-[#2841af]">
                            Entrar
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        (() => {
            const button = document.getElementById('toggle-password');
            const input = document.getElementById('password');

            button?.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.textContent = isPassword ? 'Ocultar' : 'Mostrar';
            });
        })();
    </script>
@endsection
