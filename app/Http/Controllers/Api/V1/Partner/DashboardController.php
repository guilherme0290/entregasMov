<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $partner = $request->user()?->partner;

        abort_if(! $partner || $partner->company_id !== $request->user()->company_id, 404, 'Parceiro não encontrado.');

        return $this->success([
            'active_deliveries' => $partner->deliveries()->whereNotIn('status', ['delivered', 'canceled'])->count(),
            'completed_today' => $partner->deliveries()->whereDate('delivered_at', today())->count(),
            'monthly_volume' => $partner->deliveries()->whereMonth('created_at', now()->month)->count(),
            'monthly_revenue' => $partner->deliveries()->whereMonth('created_at', now()->month)->sum('delivery_fee'),
        ], 'Resumo do parceiro carregado.');
    }
}
