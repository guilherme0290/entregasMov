<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $couriers = Courier::query()
            ->where('company_id', $request->user()->company_id)
            ->with('user')
            ->when($request->filled('availability_status'), function ($query) use ($request) {
                $query->where('availability_status', $request->string('availability_status'));
            })
            ->latest()
            ->paginate(15);

        return $this->success($couriers, 'Entregadores carregados.');
    }

    public function show(Courier $courier)
    {
        abort_unless($courier->company_id === auth()->user()->company_id, 404);

        return $this->success($courier->load(['user', 'deliveries', 'earnings']), 'Entregador carregado.');
    }
}
