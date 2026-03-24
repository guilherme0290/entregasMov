<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use App\Support\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __invoke()
    {
        $companyId = auth()->user()->company_id;

        return $this->success([
            'deliveries_today' => Delivery::query()->where('company_id', $companyId)->whereDate('created_at', today())->count(),
            'active_couriers' => Courier::query()->where('company_id', $companyId)->where('availability_status', 'online')->count(),
            'completed_today' => Delivery::query()->where('company_id', $companyId)->whereDate('delivered_at', today())->count(),
            'revenue_today' => Delivery::query()
                ->where('company_id', $companyId)
                ->whereDate('created_at', today())
                ->sum('delivery_fee'),
            'partners_total' => Partner::query()->where('company_id', $companyId)->count(),
        ], 'Resumo administrativo carregado.');
    }
}
