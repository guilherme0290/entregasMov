<?php

namespace App\Http\Controllers\Api\V1\Courier;

use App\Models\Delivery;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $courier = $request->user()?->courier;

        abort_if(! $courier || $courier->company_id !== $request->user()->company_id, 404, 'Entregador não encontrado.');

        return $this->success([
            'availability_status' => $courier->availability_status,
            'available_deliveries' => Delivery::query()
                ->where('company_id', $request->user()->company_id)
                ->whereIn('status', ['pending', 'available'])
                ->count(),
            'deliveries_today' => $courier->deliveries()->whereDate('created_at', today())->count(),
            'earnings_today' => $courier->earnings()->whereDate('created_at', today())->sum('net_amount'),
            'unread_notifications' => $request->user()
                ->systemNotifications()
                ->where('company_id', $request->user()->company_id)
                ->whereNull('read_at')
                ->count(),
        ], 'Resumo do entregador carregado.');
    }
}
