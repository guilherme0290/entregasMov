<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReceitaCnpjService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerLookupController extends Controller
{
    public function __invoke(Request $request, ReceitaCnpjService $receitaCnpjService): JsonResponse
    {
        $request->validate([
            'tax_id' => ['required', 'string'],
        ]);

        return response()->json([
            'data' => $receitaCnpjService->lookup($request->string('tax_id')->toString()),
        ]);
    }
}
