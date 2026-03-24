<?php

namespace App\Http\Controllers\Web\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Partner\StoreDeliveryRequest;
use App\Models\Delivery;
use App\Services\DeliveryWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(private readonly DeliveryWorkflowService $workflow) {}

    public function store(StoreDeliveryRequest $request): RedirectResponse
    {
        $partner = $request->user()->partner;

        abort_if(! $partner || $partner->company_id !== $request->user()->company_id, 404, 'Parceiro não encontrado.');

        $this->workflow->createForPartner($partner, $request->user(), $request->validated());

        return redirect()->route('partner.portal', ['tab' => 'progress'])
            ->with('status', 'Entrega solicitada com sucesso.');
    }

    public function cancel(Request $request, Delivery $delivery): RedirectResponse
    {
        $partner = $request->user()->partner;

        abort_unless(
            $partner
            && $partner->company_id === $request->user()->company_id
            && $delivery->partner_id === $partner->id
            && $delivery->company_id === $request->user()->company_id,
            403,
            'Entrega não pertence ao parceiro.'
        );

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->workflow->cancel($delivery, $request->user(), $validated['reason'] ?? null);

        return redirect()->route('partner.portal', ['tab' => 'progress'])
            ->with('status', 'Entrega cancelada com sucesso.');
    }
}
