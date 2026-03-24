<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Services\DeliveryWorkflowService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly DeliveryWorkflowService $workflow) {}

    public function store(Request $request)
    {
        $partner = $request->user()?->partner;

        abort_if(! $partner || $partner->company_id !== $request->user()->company_id, 404, 'Parceiro não encontrado.');

        $data = $request->validate([
            'pickup_address' => ['nullable', 'string', 'max:255'],
            'pickup_number' => ['nullable', 'string', 'max:20'],
            'pickup_district' => ['nullable', 'string', 'max:255'],
            'pickup_city' => ['nullable', 'string', 'max:255'],
            'pickup_state' => ['nullable', 'string', 'size:2'],
            'pickup_zip_code' => ['nullable', 'string', 'max:10'],
            'pickup_complement' => ['nullable', 'string', 'max:255'],
            'pickup_reference' => ['nullable', 'string', 'max:255'],
            'dropoff_address' => ['required', 'string', 'max:255'],
            'dropoff_number' => ['nullable', 'string', 'max:20'],
            'dropoff_district' => ['nullable', 'string', 'max:255'],
            'dropoff_city' => ['required', 'string', 'max:255'],
            'dropoff_state' => ['required', 'string', 'size:2'],
            'dropoff_zip_code' => ['nullable', 'string', 'max:10'],
            'dropoff_complement' => ['nullable', 'string', 'max:255'],
            'dropoff_reference' => ['nullable', 'string', 'max:255'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'recipient_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'courier_payout_amount' => ['nullable', 'numeric', 'min:0'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'estimated_time_min' => ['nullable', 'integer', 'min:1'],
            'scheduled_for' => ['nullable', 'date'],
        ]);

        $delivery = $this->workflow->createForPartner($partner, $request->user(), $data);

        return $this->success($delivery->load(['partner', 'statusLogs']), 'Entrega criada com sucesso.', 201);
    }

    public function index(Request $request)
    {
        $partner = $request->user()?->partner;

        abort_if(! $partner || $partner->company_id !== $request->user()->company_id, 404, 'Parceiro não encontrado.');

        $deliveries = $partner->deliveries()
            ->with('courier.user')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->latest()
            ->paginate(15);

        return $this->success($deliveries, 'Entregas do parceiro carregadas.');
    }

    public function show(Request $request, Delivery $delivery)
    {
        $partner = $request->user()?->partner;

        abort_unless(
            $partner
            && $partner->company_id === $request->user()->company_id
            && $delivery->partner_id === $partner->id
            && $delivery->company_id === $request->user()->company_id,
            403,
            'Entrega não pertence ao parceiro.'
        );

        return $this->success($delivery->load(['courier.user', 'statusLogs.user']), 'Entrega carregada.');
    }

    public function cancel(Request $request, Delivery $delivery)
    {
        $partner = $request->user()?->partner;

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

        $delivery = $this->workflow->cancel($delivery, $request->user(), $validated['reason'] ?? null);

        return $this->success($delivery, 'Entrega cancelada com sucesso.');
    }
}
