<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\DeliveryRequestSource;
use App\Enums\DeliveryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\AssignCourierRequest;
use App\Http\Requests\Web\Admin\StoreDeliveryRequest;
use App\Http\Requests\Web\Admin\TransferCourierRequest;
use App\Http\Requests\Web\Admin\UpdateDeliveryRequest;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use App\Services\DeliveryWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function __construct(private readonly DeliveryWorkflowService $workflow) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $deliveries = Delivery::query()
            ->where('company_id', $request->user()->company_id)
            ->with(['partner', 'courier.user'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('dropoff_address', 'like', "%{$search}%")
                        ->orWhereHas('partner', fn ($partnerQuery) => $partnerQuery->where('trade_name', 'like', "%{$search}%"))
                        ->orWhereHas('courier.user', fn ($courierQuery) => $courierQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusMap = [
            'pending' => ['label' => 'Pendente', 'class' => 'bg-amber-50 text-amber-500'],
            'accepted' => ['label' => 'Aceita', 'class' => 'bg-blue-50 text-blue-600'],
            'in_pickup' => ['label' => 'Coletada', 'class' => 'bg-blue-50 text-blue-600'],
            'in_transit' => ['label' => 'Em trânsito', 'class' => 'bg-blue-50 text-blue-600'],
            'delivered' => ['label' => 'Entregue', 'class' => 'bg-emerald-50 text-emerald-600'],
            'canceled' => ['label' => 'Cancelada', 'class' => 'bg-slate-100 text-slate-500'],
        ];

        return view('admin.deliveries.index', compact('deliveries', 'statusMap'));
    }

    public function create(): View
    {
        $partners = Partner::query()
            ->where('company_id', auth()->user()->company_id)
            ->active()
            ->orderBy('trade_name')
            ->get();

        return view('admin.deliveries.create', compact('partners'));
    }

    public function edit(Delivery $delivery): View
    {
        abort_unless($delivery->company_id === auth()->user()->company_id, 404);
        abort_unless($this->canManageDelivery($delivery), 422, 'Esta entrega não pode mais ser editada.');

        $partners = Partner::query()
            ->where('company_id', auth()->user()->company_id)
            ->where(function ($query) use ($delivery) {
                $query->where('is_active', true)
                    ->orWhereKey($delivery->partner_id);
            })
            ->orderBy('trade_name')
            ->get();

        return view('admin.deliveries.edit', compact('delivery', 'partners'));
    }

    public function store(StoreDeliveryRequest $request): RedirectResponse
    {
        $partner = Partner::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('partner_id'));

        $this->workflow->createForPartner($partner, $request->user(), [
            ...$request->validated(),
            'request_source' => DeliveryRequestSource::Manual,
        ]);

        return redirect()->route('admin.deliveries.index')->with('status', 'Entrega cadastrada com sucesso.');
    }

    public function update(UpdateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);
        abort_unless($this->canManageDelivery($delivery), 422, 'Esta entrega não pode mais ser editada.');

        $partner = Partner::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('partner_id'));

        $delivery->update([
            ...$request->validated(),
            'partner_id' => $partner->id,
        ]);

        return redirect()->route('admin.deliveries.show', $delivery)->with('status', 'Entrega atualizada com sucesso.');
    }

    public function show(Delivery $delivery): View
    {
        abort_unless($delivery->company_id === auth()->user()->company_id, 404);
        $delivery->load([
            'partner.user',
            'courier.user',
            'statusLogs.user',
            'earning',
            'transfers.previousCourier.user',
            'transfers.newCourier.user',
            'transfers.transferredBy',
        ]);

        $couriers = Courier::query()
            ->where('company_id', auth()->user()->company_id)
            ->with('user')
            ->where('is_active', true)
            ->orderByDesc('availability_status')
            ->get();

        $canAssignCourier = in_array($delivery->status, [DeliveryStatus::Pending, DeliveryStatus::Available], true);
        $canManageDelivery = $this->canManageDelivery($delivery);
        $canTransferCourier = in_array($delivery->status, [DeliveryStatus::Accepted, DeliveryStatus::InPickup, DeliveryStatus::InTransit], true);

        return view('admin.deliveries.show', compact('delivery', 'couriers', 'canAssignCourier', 'canManageDelivery', 'canTransferCourier'));
    }

    public function assignCourier(AssignCourierRequest $request, Delivery $delivery): RedirectResponse
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);

        $courier = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('courier_id'));

        $this->workflow->assignCourier($delivery, $courier, $request->user());

        return redirect()->route('admin.deliveries.show', $delivery)->with('status', 'Entregador atribuído com sucesso.');
    }

    public function cancel(Request $request, Delivery $delivery): RedirectResponse
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);
        abort_unless($this->canManageDelivery($delivery), 422, 'Esta entrega não pode ser cancelada.');

        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->workflow->cancel($delivery, $request->user(), $request->input('reason'));

        return redirect()->route('admin.deliveries.show', $delivery)->with('status', 'Entrega cancelada com sucesso.');
    }

    public function transferCourier(TransferCourierRequest $request, Delivery $delivery): RedirectResponse
    {
        abort_unless($delivery->company_id === $request->user()->company_id, 404);

        $courier = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($request->integer('courier_id'));

        $this->workflow->transferCourier(
            $delivery,
            $courier,
            $request->user(),
            $request->string('reason')->toString(),
            $request->filled('notes') ? $request->string('notes')->toString() : null,
        );

        return redirect()->route('admin.deliveries.show', $delivery)->with('status', 'Entregador trocado com sucesso.');
    }

    private function canManageDelivery(Delivery $delivery): bool
    {
        return in_array($delivery->status, [DeliveryStatus::Pending, DeliveryStatus::Available, DeliveryStatus::Accepted], true);
    }
}
