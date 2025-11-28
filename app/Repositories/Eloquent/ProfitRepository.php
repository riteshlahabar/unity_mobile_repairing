<?php

namespace App\Repositories\Eloquent;

use App\Models\Profit;
use App\Repositories\Contracts\ProfitRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitRepository implements ProfitRepositoryInterface
{
    /**
     * Get all profits with relationships
     */
    public function getAllProfits()
    {
        return Profit::with('jobSheet.customer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($profit) {
                $profit->total_charges = $profit->service_charge + $profit->spare_parts_charge + $profit->other_charge;
                $profit->profit = $profit->jobSheet->estimated_cost - $profit->total_charges;
                return $profit;
            });
    }

    /**
     * Calculate profit for date range
     */
    public function getProfitByDateRange(Carbon $startDate, Carbon $endDate): float
    {
        return Profit::whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('estimated_cost - (service_charge + spare_parts_charge + other_charge)'));
    }

    /**
     * Update or create profit record
     */
    public function updateOrCreate(array $data)
    {
        return Profit::updateOrCreate(
            ['jobsheet_id' => $data['jobsheet_id']],
            $data
        );
    }

    /**
     * Get profit by jobsheet ID
     */
    public function getByJobsheetId(int $jobsheetId)
    {
        return Profit::with('jobSheet.customer')
            ->where('jobsheet_id', $jobsheetId)
            ->first();
    }
}
