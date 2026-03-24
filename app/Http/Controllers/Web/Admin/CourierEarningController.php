<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\CourierPaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Admin\UpdateCourierEarningRequest;
use App\Models\CourierEarning;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourierEarningController extends Controller
{
    public function index(Request $request): View
    {
        $companyId = $request->user()->company_id;

        $earnings = CourierEarning::query()
            ->where('company_id', $companyId)
            ->with(['courier.user', 'delivery.partner'])
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'pending' => CourierEarning::query()->where('company_id', $companyId)->where('payment_status', CourierPaymentStatus::Pending)->sum('net_amount'),
            'released' => CourierEarning::query()->where('company_id', $companyId)->where('payment_status', CourierPaymentStatus::Released)->sum('net_amount'),
            'paid' => CourierEarning::query()->where('company_id', $companyId)->where('payment_status', CourierPaymentStatus::Paid)->sum('net_amount'),
        ];

        return view('admin.earnings.index', compact('earnings', 'summary'));
    }

    public function update(UpdateCourierEarningRequest $request, CourierEarning $earning): RedirectResponse
    {
        abort_unless($earning->company_id === $request->user()->company_id, 404);

        $status = $request->string('payment_status')->toString();

        $earning->update([
            'payment_status' => $status,
            'released_at' => $status === CourierPaymentStatus::Released->value ? now() : $earning->released_at,
            'paid_at' => $status === CourierPaymentStatus::Paid->value ? now() : $earning->paid_at,
        ]);

        return redirect()->route('admin.earnings.index')->with('status', 'Status do ganho atualizado com sucesso.');
    }
}
