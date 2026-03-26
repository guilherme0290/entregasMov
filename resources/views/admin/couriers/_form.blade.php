@php
    $courierModel = $courier ?? null;
    $currentAvailabilityStatus = old('availability_status', $courierModel?->availability_status?->value ?? 'offline');
@endphp
<input type="hidden" name="availability_status" value="{{ $currentAvailabilityStatus }}">

<div class="mx-auto max-w-5xl space-y-6" data-courier-tabs>
    <div class="flex flex-wrap justify-center gap-3">
        <div class="inline-flex flex-wrap justify-center gap-3 rounded-[24px] bg-[#eef3ff] p-2">
            <button type="button" data-tab-trigger="personal" class="rounded-2xl bg-[#284da3] px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                <span>Dados pessoais</span>
            </button>
            <button type="button" data-tab-trigger="vehicle" class="rounded-2xl bg-white px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-[#dbe7ff] hover:text-[#284da3]">
                <span>Tipo de veiculo</span>
            </button>
            <button type="button" data-tab-trigger="access" class="rounded-2xl bg-white px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-[#dbe7ff] hover:text-[#284da3]">
                <span>Acesso ao entregador</span>
            </button>
        </div>
    </div>

    <section data-tab-panel="personal" class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Dados pessoais</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                <input
                    name="name_personal_preview"
                    value="{{ old('name', $courierModel?->user?->name) }}"
                    data-sync-group="courier-name"
                    autocomplete="name"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                >
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CPF</label>
                <input name="tax_id" value="{{ old('tax_id', $courierModel?->tax_id) }}" data-mask="cpf" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Data de nascimento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($courierModel?->birth_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Celular</label>
                <input name="phone" value="{{ old('phone', $courierModel?->user?->phone) }}" data-mask="phone" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input
                    type="email"
                    name="email_personal_preview"
                    value="{{ old('email', $courierModel?->user?->email) }}"
                    data-sync-group="courier-email"
                    autocomplete="email"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                >
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                <input
                    name="zip_code"
                    value="{{ old('zip_code', $courierModel?->zip_code) }}"
                    data-cep-input
                    data-mask="cep"
                    autocomplete="postal-code"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="00000-000"
                >
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Endereco</label>
                <input name="address" value="{{ old('address', $courierModel?->address) }}" data-address-input autocomplete="address-line1" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                <input name="district" value="{{ old('district', $courierModel?->district) }}" data-district-input autocomplete="address-level3" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                <input name="city" value="{{ old('city', $courierModel?->city) }}" data-city-input autocomplete="address-level2" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                <input name="state" value="{{ old('state', $courierModel?->state) }}" data-state-input autocomplete="address-level1" maxlength="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3 uppercase">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Numero</label>
                <input name="number" value="{{ old('number', $courierModel?->number) }}" autocomplete="address-line2" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                <input name="complement" value="{{ old('complement', $courierModel?->complement) }}" autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <a href="{{ route('admin.couriers.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Cancelar</a>
            <button type="button" data-next-tab="vehicle" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">Próximo</button>
        </div>
    </section>

    <section data-tab-panel="vehicle" class="hidden rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Tipo de veiculo</h2>
        <div class="mt-5 grid gap-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tipo de veiculo</label>
                <select name="vehicle_type" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="">Selecione</option>
                    <option value="moto" @selected(old('vehicle_type', $courierModel?->vehicle_type) === 'moto')>Moto</option>
                    <option value="carro" @selected(old('vehicle_type', $courierModel?->vehicle_type) === 'carro')>Carro</option>
                </select>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Modelo</label>
                    <input name="vehicle_model" value="{{ old('vehicle_model', $courierModel?->vehicle_model) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Placa</label>
                    <input name="vehicle_plate" value="{{ old('vehicle_plate', $courierModel?->vehicle_plate) }}" data-mask="plate" class="w-full rounded-2xl border border-slate-200 px-4 py-3 uppercase">
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Observacoes</label>
                <textarea name="notes" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $courierModel?->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="button" data-prev-tab="personal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Voltar</button>
            <button type="button" data-next-tab="access" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">Próximo</button>
        </div>
    </section>

    <section data-tab-panel="access" class="hidden rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Acesso ao entregador</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                <input
                    name="name"
                    value="{{ old('name', $courierModel?->user?->name) }}"
                    data-sync-group="courier-name"
                    autocomplete="username"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                >
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $courierModel?->user?->email) }}"
                    data-sync-group="courier-email"
                    autocomplete="email"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                >
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Senha {{ $courierModel ? '(preencha so se quiser trocar)' : '' }}</label>
                <div class="relative">
                    <input type="password" name="password" autocomplete="new-password" data-password-input class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-24">
                    <button type="button" data-password-toggle class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl px-3 py-2 text-sm font-medium text-[#284da3] transition hover:bg-slate-50">
                        Ver senha
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-3 text-sm text-slate-700 md:col-span-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $courierModel?->is_active ?? true))>
                Entregador ativo
            </label>
        </div>
        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="button" data-prev-tab="vehicle" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Voltar</button>
            <button type="submit" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">{{ $submitLabel }}</button>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabsRoot = document.querySelector('[data-courier-tabs]');

        if (tabsRoot) {
            const triggers = tabsRoot.querySelectorAll('[data-tab-trigger]');
            const panels = tabsRoot.querySelectorAll('[data-tab-panel]');
            const nextButtons = tabsRoot.querySelectorAll('[data-next-tab]');
            const prevButtons = tabsRoot.querySelectorAll('[data-prev-tab]');

            const activateTab = (tabName) => {
                triggers.forEach((trigger) => {
                    const isActive = trigger.dataset.tabTrigger === tabName;

                    trigger.classList.toggle('bg-[#284da3]', isActive);
                    trigger.classList.toggle('text-white', isActive);
                    trigger.classList.toggle('shadow-sm', isActive);
                    trigger.classList.toggle('bg-white', !isActive);
                    trigger.classList.toggle('text-slate-600', !isActive);
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabName);
                });
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => activateTab(trigger.dataset.tabTrigger));
            });

            nextButtons.forEach((button) => {
                button.addEventListener('click', () => activateTab(button.dataset.nextTab));
            });

            prevButtons.forEach((button) => {
                button.addEventListener('click', () => activateTab(button.dataset.prevTab));
            });

            activateTab('personal');
        }

        const syncGroups = document.querySelectorAll('[data-sync-group]');

        syncGroups.forEach((field) => {
            field.addEventListener('input', () => {
                const group = field.dataset.syncGroup;

                document.querySelectorAll(`[data-sync-group="${group}"]`).forEach((peer) => {
                    if (peer !== field) {
                        peer.value = field.value;
                    }
                });
            });
        });

        const cepInput = document.querySelector('[data-cep-input]');
        const addressInput = document.querySelector('[data-address-input]');
        const districtInput = document.querySelector('[data-district-input]');
        const cityInput = document.querySelector('[data-city-input]');
        const stateInput = document.querySelector('[data-state-input]');
        const maskFields = document.querySelectorAll('[data-mask]');
        const passwordInput = document.querySelector('[data-password-input]');
        const passwordToggle = document.querySelector('[data-password-toggle]');

        const applyMask = (value, type) => {
            const digitsOnly = value.replace(/\D/g, '');

            if (type === 'cpf') {
                return digitsOnly
                    .slice(0, 11)
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }

            if (type === 'phone') {
                const digits = digitsOnly.slice(0, 11);

                if (digits.length <= 10) {
                    return digits
                        .replace(/(\d{2})(\d)/, '($1) $2')
                        .replace(/(\d{4})(\d)/, '$1-$2');
                }

                return digits
                    .replace(/(\d{2})(\d)/, '($1) $2')
                    .replace(/(\d{5})(\d)/, '$1-$2');
            }

            if (type === 'cep') {
                return digitsOnly
                    .slice(0, 8)
                    .replace(/(\d{5})(\d)/, '$1-$2');
            }

            if (type === 'plate') {
                const normalized = value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 7);

                if (normalized.length <= 3) {
                    return normalized;
                }

                return `${normalized.slice(0, 3)}-${normalized.slice(3)}`;
            }

            return value;
        };

        maskFields.forEach((field) => {
            const updateMask = () => {
                field.value = applyMask(field.value, field.dataset.mask);
            };

            updateMask();
            field.addEventListener('input', updateMask);
        });

        const fillAddressByCep = async () => {
            if (!cepInput) {
                return;
            }

            const cep = cepInput.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();

                if (data.erro) {
                    return;
                }

                if (addressInput) {
                    addressInput.value = data.logradouro ?? '';
                }

                if (districtInput) {
                    districtInput.value = data.bairro ?? '';
                }

                if (cityInput) {
                    cityInput.value = data.localidade ?? '';
                }

                if (stateInput) {
                    stateInput.value = data.uf ?? '';
                }
            } catch (error) {
                console.error('Falha ao buscar CEP.', error);
            }
        };

        cepInput?.addEventListener('blur', fillAddressByCep);

        passwordToggle?.addEventListener('click', () => {
            if (!passwordInput) {
                return;
            }

            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';
            passwordToggle.textContent = showPassword ? 'Ocultar' : 'Ver senha';
        });
    });
</script>
