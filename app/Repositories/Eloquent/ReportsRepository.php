<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\JobSheet;
use App\Repositories\Contracts\ReportsRepositoryInterface;

class ReportsRepository implements ReportsRepositoryInterface
{
    /**
     * Get technician performance statistics
     * 
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getTechnicianPerformance(string $from, string $to): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->whereNotNull('technician')
            ->selectRaw('technician, COUNT(*) as count')
            ->groupBy('technician')
            ->pluck('count', 'technician')
            ->toArray();
    }

    /**
     * Get top devices (company + model combinations)
     * 
     * @param string $from
     * @param string $to
     * @param int $limit
     * @return array
     */
    public function getTopDevices(string $from, string $to, int $limit = 10): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw("CONCAT(company, ' ', model) as device, COUNT(*) as count")
            ->groupBy('company', 'model')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'device')
            ->toArray();
    }

    /**
     * Get problem breakdown statistics
     * 
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getProblemBreakdown(string $from, string $to): array
    {
        return [
            'Dead' => JobSheet::whereBetween('created_at', [$from, $to])
                ->where('status_dead', true)->count(),
            
            'Damaged' => JobSheet::whereBetween('created_at', [$from, $to])
                ->where('status_damage', true)->count(),
            
            'On with Problem' => JobSheet::whereBetween('created_at', [$from, $to])
                ->where('status_on', true)->count(),
            
            'Others' => JobSheet::whereBetween('created_at', [$from, $to])
                ->where('status_dead', false)
                ->where('status_damage', false)
                ->where('status_on', false)
                ->count(),
        ];
    }

    /**
     * Get device condition statistics
     * 
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getDeviceConditionStats(string $from, string $to): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw('device_condition, COUNT(*) as count')
            ->groupBy('device_condition')
            ->pluck('count', 'device_condition')
            ->toArray();
    }

    /**
     * Get water damage statistics (lite and full only)
     * 
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getWaterDamageStats(string $from, string $to): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->whereIn('water_damage', ['lite', 'full'])
            ->selectRaw('water_damage, COUNT(*) as count')
            ->groupBy('water_damage')
            ->pluck('count', 'water_damage')
            ->toArray();
    }

    /**
     * Get physical damage statistics (lite and full only)
     * 
     * @param string $from
     * @param string $to
     * @return array
     */
    public function getPhysicalDamageStats(string $from, string $to): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->whereIn('physical_damage', ['lite', 'full'])
            ->selectRaw('physical_damage, COUNT(*) as count')
            ->groupBy('physical_damage')
            ->pluck('count', 'physical_damage')
            ->toArray();
    }

    /**
     * Get customer flow over time
     * 
     * @param string $from
     * @param string $to
     * @param string $dateFormat MySQL date format
     * @return array
     */
    public function getCustomerFlow(string $from, string $to, string $dateFormat): array
    {
        return Customer::whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') AS label, COUNT(*) as count")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('count', 'label')
            ->toArray();
    }

    /**
     * Get revenue statistics over time
     * 
     * @param string $from
     * @param string $to
     * @param string $dateFormat MySQL date format
     * @return array
     */
    public function getRevenueStats(string $from, string $to, string $dateFormat): array
    {
        return JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') AS label, SUM(advance) as sum")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('sum', 'label')
            ->toArray();
    }
}
