<?php

namespace App\Http\Controllers;

use App\Services\Contracts\RevenueServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RevenueController extends Controller
{
    public function __construct(
        protected RevenueServiceInterface $revenueService
    ) {}

    /**
     * Display revenue page with PIN protection
     */
    public function index()
    {
        return view('reports/revenue/index');
    }

    /**
     * Verify PIN for revenue access
     */
   public function verifyPin(Request $request)  // ← FIXED: Added Request parameter
    {
        $request->validate([
            'pin' => 'required|string|size:4'
        ]);

        $result = $this->revenueService->verifyPin($request->pin);

        if ($result['success']) {
            session(['revenue_verified' => true]);
        }

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    /**
     * Get revenue data
     */
    public function getData(Request $request)
    {
        if (!session('revenue_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $data = $this->revenueService->getRevenueData();

        return response()->json($data, $data['success'] ? 200 : 500);
    }

    /**
     * Update profit data
     */
    public function updateProfit(Request $request)
    {
        if (!session('revenue_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'jobsheet_id' => 'required|integer|exists:job_sheets,id',
            'service_charge' => 'required|numeric|min:0',
            'spare_parts_charge' => 'required|numeric|min:0',
            'other_charge' => 'required|numeric|min:0',
        ]);

        $result = $this->revenueService->updateProfit(
            $request->jobsheet_id,
            $request->service_charge,
            $request->spare_parts_charge,
            $request->other_charge
        );

        return response()->json($result, $result['success'] ? 200 : 500);
    }
}
