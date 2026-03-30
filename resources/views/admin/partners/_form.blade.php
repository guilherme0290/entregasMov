@php
    $partnerModel = $partner ?? null;
@endphp

<div class="mx-auto max-w-5xl space-y-6" data-partner-tabs>
    <div class="flex flex-wrap justify-center gap-3">
        <div class="inline-flex flex-wrap justify-center gap-3 rounded-[24px] bg-[#eef3ff] p-2">
            <button type="button" data-tab-trigger="business" class="rounded-2xl bg-[#284da3] px-5 py-3 text-sm font-medium text-white shadow-sm transition">
                Dados comerciais
            </button>
            <button type="button" data-tab-trigger="address" class="rounded-2xl bg-white px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-[#dbe7ff] hover:text-[#284da3]">
                Endereco e valores
            </button>
            <button type="button" data-tab-trigger="access" class="rounded-2xl bg-white px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-[#dbe7ff] hover:text-[#284da3]">
                Acesso ao parceiro
            </button>
        </div>
    </div>

    <section data-tab-panel="business" class="rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Dados comerciais</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CNPJ</label>
                <input name="tax_id" value="{{ old('tax_id', $partnerModel?->tax_id) }}" data-mask="document" data-cnpj-input autofocus class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome fantasia</label>
                <input name="trade_name" value="{{ old('trade_name', $partnerModel?->trade_name) }}" data-company-field="trade_name" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Razao social</label>
                <input name="company_name" value="{{ old('company_name', $partnerModel?->company_name) }}" data-company-field="company_name" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Responsavel</label>
                <input name="contact_name" value="{{ old('contact_name', $partnerModel?->contact_name) }}" data-company-field="contact_name" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Telefone do responsavel</label>
                <input name="contact_phone" value="{{ old('contact_phone', $partnerModel?->contact_phone) }}" data-company-field="contact_phone" data-mask="phone" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">E-mail financeiro</label>
                <input type="email" name="billing_email" value="{{ old('billing_email', $partnerModel?->billing_email) }}" data-company-field="billing_email" autocomplete="email" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <a href="{{ route('admin.partners.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Cancelar</a>
            <button type="button" data-next-tab="address" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">Proximo</button>
        </div>
    </section>

    <section data-tab-panel="address" class="hidden rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Endereco de coleta e valores</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">CEP</label>
                <input
                    name="pickup_zip_code"
                    value="{{ old('pickup_zip_code', $partnerModel?->pickup_zip_code) }}"
                    data-cep-input
                    data-mask="cep"
                    autocomplete="off"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="00000-000"
                >
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Numero</label>
                <input name="pickup_number" value="{{ old('pickup_number', $partnerModel?->pickup_number) }}" data-company-field="pickup_number" autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Endereco</label>
                <input name="pickup_address" value="{{ old('pickup_address', $partnerModel?->pickup_address) }}" data-company-field="pickup_address" data-address-input autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bairro</label>
                <input name="pickup_district" value="{{ old('pickup_district', $partnerModel?->pickup_district) }}" data-company-field="pickup_district" data-district-input autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Cidade</label>
                <input name="pickup_city" value="{{ old('pickup_city', $partnerModel?->pickup_city) }}" data-company-field="pickup_city" data-city-input autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">UF</label>
                <input name="pickup_state" value="{{ old('pickup_state', $partnerModel?->pickup_state) }}" data-company-field="pickup_state" data-state-input autocomplete="off" maxlength="2" class="w-full rounded-2xl border border-slate-200 px-4 py-3 uppercase">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Complemento</label>
                <input name="pickup_complement" value="{{ old('pickup_complement', $partnerModel?->pickup_complement) }}" data-company-field="pickup_complement" autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Entrega padrao</label>
                <input type="number" step="0.01" name="default_delivery_fee" value="{{ old('default_delivery_fee', $partnerModel?->default_delivery_fee) }}" autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Entrega urgente</label>
                <input type="number" step="0.01" name="urgent_delivery_fee" value="{{ old('urgent_delivery_fee', $partnerModel?->urgent_delivery_fee) }}" autocomplete="off" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Observacoes</label>
                <textarea name="notes" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('notes', $partnerModel?->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="button" data-prev-tab="business" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Voltar</button>
            <button type="button" data-next-tab="access" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">Proximo</button>
        </div>
    </section>

    <section data-tab-panel="access" class="hidden rounded-[28px] bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-950">Acesso ao parceiro</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                <input name="name" value="{{ old('name', $partnerModel?->user?->name) }}" autocomplete="username" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $partnerModel?->user?->email) }}" autocomplete="email" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Telefone</label>
                <input name="phone" value="{{ old('phone', $partnerModel?->user?->phone) }}" data-mask="phone" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Senha {{ $partnerModel ? '(preencha so se quiser trocar)' : '' }}</label>
                <div class="relative">
                    <input type="password" name="password" autocomplete="new-password" data-password-input class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-24">
                    <button type="button" data-password-toggle class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl px-3 py-2 text-sm font-medium text-[#284da3] transition hover:bg-slate-50">
                        Ver senha
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-3 text-sm text-slate-700 md:col-span-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partnerModel?->is_active ?? true))>
                Parceiro ativo
            </label>
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="button" data-prev-tab="address" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-medium text-slate-700">Voltar</button>
            <button type="submit" class="rounded-2xl bg-blue-700 px-5 py-3 text-sm font-medium text-white">{{ $submitLabel }}</button>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabsRoot = document.querySelector('[data-partner-tabs]');

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

            activateTab('business');
        }

        const cepInput = document.querySelector('[data-partner-tabs] [data-cep-input]');
        const cnpjInput = document.querySelector('[data-partner-tabs] [data-cnpj-input]');
        const addressInput = document.querySelector('[data-partner-tabs] [data-address-input]');
        const districtInput = document.querySelector('[data-partner-tabs] [data-district-input]');
        const cityInput = document.querySelector('[data-partner-tabs] [data-city-input]');
        const stateInput = document.querySelector('[data-partner-tabs] [data-state-input]');
        const maskFields = document.querySelectorAll('[data-partner-tabs] [data-mask]');
        const passwordInput = document.querySelector('[data-partner-tabs] [data-password-input]');
        const passwordToggle = document.querySelector('[data-partner-tabs] [data-password-toggle]');

        const applyMask = (value, type) => {
            const digitsOnly = value.replace(/\D/g, '');

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

            if (type === 'document') {
                return digitsOnly
                    .slice(0, 14)
                    .replace(/^(\d{2})(\d)/, '$1.$2')
                    .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                    .replace(/\.(\d{3})(\d)/, '.$1/$2')
                    .replace(/(\d{4})(\d)/, '$1-$2');
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

        const fillCompanyByCnpj = async () => {
            if (!cnpjInput) {
                return;
            }

            const cnpj = cnpjInput.value.replace(/\D/g, '');

            if (cnpj.length !== 14) {
                return;
            }

            try {
                const response = await fetch('{{ route('admin.partners.cnpj-lookup') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        tax_id: cnpj,
                    }),
                });

                const payload = await response.json();

                if (!response.ok || !payload.data) {
                    return;
                }

                Object.entries(payload.data).forEach(([field, value]) => {
                    if (!value) {
                        return;
                    }

                    const input = document.querySelector(`[data-company-field="${field}"]`);

                    if (!input || input.value.trim() !== '') {
                        return;
                    }

                    input.value = value;

                    if (input.dataset.mask) {
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            } catch (error) {
                console.error('Falha ao consultar o CNPJ.', error);
            }
        };

        cnpjInput?.addEventListener('blur', fillCompanyByCnpj);

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
