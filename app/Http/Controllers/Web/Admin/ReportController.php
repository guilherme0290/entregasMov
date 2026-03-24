<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __invoke(): View
    {
        $companyId = auth()->user()->company_id;
        $months = collect(range(5, 0))
            ->map(function (int $offset) use ($companyId) {
                $date = now()->startOfMonth()->subMonths($offset);

                $deliveries = Delivery::query()
                    ->where('company_id', $companyId)
                    ->whereBetween('created_at', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()]);

                return [
                    'label' => $date->translatedFormat('M'),
                    'deliveries' => (clone $deliveries)->count(),
                    'revenue' => (float) (clone $deliveries)->sum('delivery_fee'),
                ];
            })
            ->values();

        $topPartners = Partner::query()
            ->where('company_id', $companyId)
            ->withCount([
                'deliveries',
                'deliveries as monthly_deliveries_count' => fn ($query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]),
            ])
            ->orderByDesc('monthly_deliveries_count')
            ->take(5)
            ->get();

        $topCouriers = Courier::query()
            ->where('company_id', $companyId)
            ->withCount('deliveries')
            ->with('user')
            ->orderByDesc('deliveries_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact('months', 'topPartners', 'topCouriers'));
    }
}
