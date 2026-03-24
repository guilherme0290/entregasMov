<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ApiResponse;

    public function deliveries(Request $request)
    {
        $from = $request->date('from', now()->startOfMonth());
        $to = $request->date('to', now()->endOfMonth());
        $companyId = $request->user()->company_id;

        $summary = Delivery::query()
            ->select('status', DB::raw('count(*) as total'))
            ->where('company_id', $companyId)
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('status')
            ->get();

        return $this->success($summary, 'Relatório de entregas carregado.');
    }

    public function partners()
    {
        $summary = Partner::query()
            ->where('company_id', auth()->user()->company_id)
            ->withCount('deliveries')
            ->orderByDesc('deliveries_count')
            ->limit(10)
            ->get();

        return $this->success($summary, 'Relatório de parceiros carregado.');
    }

    public function couriers()
    {
        $summary = Courier::query()
            ->where('company_id', auth()->user()->company_id)
            ->withCount('deliveries')
            ->orderByDesc('deliveries_count')
            ->limit(10)
            ->get();

        return $this->success($summary, 'Relatório de entregadores carregado.');
    }
}
