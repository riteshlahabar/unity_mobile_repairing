<?php

namespace App\Services;

use App\Models\JobSheet;
use App\Models\Profit;
use App\Services\Contracts\RevenueServiceInterface;
use App\Repositories\Contracts\ProfitRepositoryInterface;
use App\Repositories\Contracts\JobSheetRepositoryInterface;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



=======
use Carbon\Carbon;

>>>>>>> 0963cebdc0528a837022693382951a181cdac698
class RevenueService implements RevenueServiceInterface
{
    public function __construct(
        protected ProfitRepositoryInterface $profitRepository,
        protected JobSheetRepositoryInterface $jobSheetRepository
    ) {}

    /**
     * Verify PIN for revenue access
     */
<<<<<<< HEAD
    public function verifyPin(string $pin): array  // ← EXACT SIGNATURE
    {
        $user = Auth::user();  // ← NOW WORKS
        
        \Log::info('RevenueService PIN check', [
            'user_id' => $user->id,
            'pin_mask' => substr($pin, 0, 1) . '***',
            'has_pin' => !empty($user->revenue_pin),
            'pin_length' => strlen($user->revenue_pin ?? 0)
        ]);
        
        if (empty($user->revenue_pin)) {
            return ['success' => false, 'message' => 'No PIN set'];
        }
        
        $valid = Hash::check($pin, $user->revenue_pin);
        
        return [
            'success' => $valid,
            'message' => $valid ? 'Access granted' : 'Invalid PIN'
        ];
    }
=======
    public function verifyPin(string $pin): array
{
    try {
        $user = auth()->user();

        // Get PIN from user record
        $userPin = $user->revenue_pin ?? config('services.revenue_pin', '1234');

        if ($pin === $userPin) {
            return [
                'success' => true,
                'message' => 'PIN verified successfully'
            ];
        }

        Log::warning('Invalid revenue PIN attempted', [
            'user_id' => $user->id,
            'pin' => substr($pin, 0, 1) . '***'
        ]);

        return [
            'success' => false,
            'message' => 'Invalid PIN'
        ];

    } catch (\Exception $e) {
        Log::error('PIN verification error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'PIN verification failed'
        ];
    }
}

>>>>>>> 0963cebdc0528a837022693382951a181cdac698

    /**
     * Get complete revenue data including profit summary and details
     */
    public function getRevenueData(): array
    {
        try {
            $profitSummary = $this->calculateProfitSummary();
            $deliveredJobs = $this->jobSheetRepository->getDeliveredJobsheets();
            $profitDetails = $this->profitRepository->getAllProfits();

            return [
                'success' => true,
                'todayProfit' => $profitSummary['today'],
                'weeklyProfit' => $profitSummary['weekly'],
                'monthlyProfit' => $profitSummary['monthly'],
                'yearlyProfit' => $profitSummary['yearly'],
                'profitDetails' => $profitDetails,
                'deliveredJobs' => $deliveredJobs
            ];

        } catch (\Exception $e) {
            Log::error('Error retrieving revenue data: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve revenue data'
            ];
        }
    }

    /**
     * Calculate profit summary for different time periods
     */
    private function calculateProfitSummary(): array
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        return [
            'today' => round($this->profitRepository->getProfitByDateRange($today, now()), 2),
            'weekly' => round($this->profitRepository->getProfitByDateRange($weekStart, now()), 2),
            'monthly' => round($this->profitRepository->getProfitByDateRange($monthStart, now()), 2),
            'yearly' => round($this->profitRepository->getProfitByDateRange($yearStart, now()), 2),
        ];
    }

    /**
     * Update or create profit record for jobsheet
     */
    public function updateProfit(int $jobsheetId, float $serviceCharge, float $sparePartsCharge, float $otherCharge): array
    {
        try {
            $jobSheet = $this->jobSheetRepository->getById($jobsheetId);

            if (!$jobSheet) {
                return [
                    'success' => false,
                    'message' => 'JobSheet not found'
                ];
            }

            $totalCharges = $serviceCharge + $sparePartsCharge + $otherCharge;
            $profit =  $jobSheet->estimated_cost - $totalCharges;

            $profitRecord = $this->profitRepository->updateOrCreate([
                'jobsheet_id' => $jobsheetId,
                'service_charge' => $serviceCharge,
                'spare_parts_charge' => $sparePartsCharge,
                'other_charge' => $otherCharge,
                'estimated_cost' => $jobSheet->estimated_cost,
                'profit' => $profit
            ]);

            Log::info('Profit updated successfully', [
                'jobsheet_id' => $jobsheetId,
                'profit' => $profit
            ]);

            return [
                'success' => true,
                'message' => 'Profit updated successfully',
                'profit' => $profitRecord
            ];

        } catch (\Exception $e) {
            Log::error('Error updating profit: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
