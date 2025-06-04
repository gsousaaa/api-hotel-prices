<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function __construct(protected ApiService $apiService)
    {
    }

    public function getRooms(Request $request)
    {
        $tokenPayload = JWTAuth::parseToken()->getPayload();

        $companyId = $tokenPayload->get('company_id');

        $rooms = $this->apiService->getCompanyRooms($companyId);

        return response()->json($rooms);
    }

    public function calculatePriceForecast(Request $request)
    {
        $tokenPayload = JWTAuth::parseToken()->getPayload();
        $companyId = $tokenPayload->get('company_id');

        if (!$request->has('effectiveDate')) {
            $request->merge([
                'effectiveDate' => Carbon::now()->addDay()->format('Y-m-d')
            ]);
        }

        $body = $request->validate([
            'occupancyRate' => 'required|integer|min:0|max:100',
            'effectiveDate' => 'required|date|date_format:Y-m-d'
        ]);

        $price = $this->apiService->calculatePriceForecast([
            'company_id' => $companyId,
            'room_id' => $request->route('id'),
            'occupancyRate' => $body['occupancyRate'],
            'effective_date' => $body['effectiveDate']
        ]);

        return response()->json($price);
    }
}