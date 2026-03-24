<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $companyId = auth()->user()->company_id;

        $stats = [
            'deliveries_today' => Delivery::query()->where('company_id', $companyId)->whereDate('created_at', today())->count(),
            'active_couriers' => Courier::query()->where('company_id', $companyId)->where('availability_status', 'online')->count(),
            'total_couriers' => Courier::query()->where('company_id', $companyId)->count(),
            'completed_today' => Delivery::query()->where('company_id', $companyId)->whereDate('delivered_at', today())->count(),
            'revenue_today' => Delivery::query()->where('company_id', $companyId)->whereDate('created_at', today())->sum('delivery_fee'),
            'partners_total' => Partner::query()->where('company_id', $companyId)->count(),
        ];

        $stats['success_rate'] = $stats['deliveries_today'] > 0
            ? (int) round(($stats['completed_today'] / $stats['deliveries_today']) * 100)
            : 0;

        $recentDeliveries = Delivery::query()
            ->where('company_id', $companyId)
            ->with(['partner', 'courier.user'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyDeliveries = Collection::times(6, function (int $offset) use ($companyId) {
            $date = Carbon::now()->subMonths(5 - $offset);

            return [
                'label' => $date->translatedFormat('M'),
                'total' => Delivery::query()
                    ->where('company_id', $companyId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return view('admin.dashboard', compact('stats', 'recentDeliveries', 'monthlyDeliveries'));
    }
}
