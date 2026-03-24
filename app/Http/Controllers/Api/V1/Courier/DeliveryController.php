<?php

namespace App\Http\Controllers\Api\V1\Courier;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Services\DeliveryWorkflowService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly DeliveryWorkflowService $workflow) {}

    public function available()
    {
        $deliveries = Delivery::query()
            ->where('company_id', auth()->user()->company_id)
            ->with('partner')
            ->whereIn('status', ['pending', 'available'])
            ->latest()
            ->paginate(15);

        return $this->success($deliveries, 'Entregas disponíveis carregadas.');
    }

    public function mine(Request $request)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $deliveries = $courier->deliveries()
            ->with('partner')
            ->latest()
            ->paginate(15);

        return $this->success($deliveries, 'Minhas entregas carregadas.');
    }

    public function show(Delivery $delivery)
    {
        abort_unless($delivery->company_id === auth()->user()->company_id, 404);

        return $this->success($delivery->load(['partner', 'courier.user', 'statusLogs.user']), 'Entrega carregada.');
    }

    public function accept(Request $request, Delivery $delivery)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $delivery = $this->workflow->accept($delivery, $courier, $request->user());

        return $this->success($delivery, 'Entrega aceita com sucesso.');
    }

    public function reject(Request $request, Delivery $delivery)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $delivery = $this->workflow->reject($delivery, $courier, $request->user(), $validated['reason'] ?? null);

        return $this->success($delivery, 'Entrega recusada com sucesso.');
    }

    public function startPickup(Request $request, Delivery $delivery)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $delivery = $this->workflow->startPickup($delivery, $courier, $request->user());

        return $this->success($delivery, 'Coleta iniciada com sucesso.');
    }

    public function startTransit(Request $request, Delivery $delivery)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $delivery = $this->workflow->startTransit($delivery, $courier, $request->user());

        return $this->success($delivery, 'Entrega saiu para destino com sucesso.');
    }

    public function complete(Request $request, Delivery $delivery)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $delivery = $this->workflow->complete($delivery, $courier, $request->user());

        return $this->success($delivery, 'Entrega finalizada com sucesso.');
    }

    public function earnings(Request $request)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        $earnings = $courier->earnings()
            ->where('company_id', $request->user()->company_id)
            ->with('delivery.partner')
            ->latest()
            ->paginate(15);

        return $this->success($earnings, 'Ganhos carregados.');
    }
}
