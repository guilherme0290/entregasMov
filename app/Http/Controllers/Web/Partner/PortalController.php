<?php

namespace App\Http\Controllers\Web\Partner;

use App\Enums\DeliveryStatus;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function __invoke(Request $request): View
    {
        $partner = $request->user()->partner;

        abort_if(! $partner || $partner->company_id !== $request->user()->company_id, 404, 'Parceiro não encontrado.');

        $currentTab = $request->string('tab')->toString() ?: 'new';

        $inProgressStatuses = [
            DeliveryStatus::Pending->value,
            DeliveryStatus::Available->value,
            DeliveryStatus::Accepted->value,
            DeliveryStatus::InPickup->value,
            DeliveryStatus::InTransit->value,
        ];

        $inProgressDeliveries = $partner->deliveries()
            ->with('courier.user')
            ->whereIn('status', $inProgressStatuses)
            ->latest()
            ->get();

        $historyDeliveries = $partner->deliveries()
            ->with('courier.user')
            ->whereIn('status', [DeliveryStatus::Delivered->value, DeliveryStatus::Canceled->value])
            ->latest()
            ->get();

        return view('partner.portal.index', compact(
            'partner',
            'currentTab',
            'inProgressDeliveries',
            'historyDeliveries',
        ));
    }
}
