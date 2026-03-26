<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\AssignCourierRequest;
use App\Http\Requests\Api\Admin\TransferCourierRequest;
use App\Models\Courier;
use App\Models\Delivery;
use App\Services\DeliveryWorkflowService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly DeliveryWorkflowService $workflow) {}

    public function index(Request $request)
    {
        $deliveries = Delivery::query()
            ->where('company_id', $request->user()->company_id)
            ->with(['partner', 'courier.user'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status'));
            })
            ->latest()
            ->paginate(15);

        return $this->success($deliveries, 'Entregas carregadas.');
    }

    public function show(Delivery $delivery)
    {
        abort_unless($delivery->company_id === auth()->user()->company_id, 404);

        return $this->success(
            $delivery->load([
                'partner.user',
                'courier.user',
                'statusLogs.user',
                'earning',
                'transfers.previousCourier.user',
                'transfers.newCourier.user',
                'transfers.transferredBy',
            ]),
            'Entrega carregada.'
        );
    }

    public function assignCourier(AssignCourierRequest $request, Delivery $delivery)
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);

        $courier = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('courier_id'));

        $delivery = $this->workflow->assignCourier($delivery, $courier, $request->user());

        return $this->success($delivery, 'Entregador atribuído com sucesso.');
    }

    public function transferCourier(TransferCourierRequest $request, Delivery $delivery)
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);

        $courier = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('courier_id'));

        $delivery = $this->workflow->transferCourier(
            $delivery,
            $courier,
            $request->user(),
            $request->string('reason')->toString(),
            $request->filled('notes') ? $request->string('notes')->toString() : null,
        );

        return $this->success($delivery, 'Entregador trocado com sucesso.');
    }
}
