<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $partners = Partner::query()
            ->where('company_id', $request->user()->company_id)
            ->with('user')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->latest()
            ->paginate(15);

        return $this->success($partners, 'Parceiros carregados.');
    }

    public function show(Partner $partner)
    {
        abort_unless($partner->company_id === auth()->user()->company_id, 404);

        return $this->success($partner->load(['user', 'deliveries']), 'Parceiro carregado.');
    }
}
