<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DashboardRepositoryInterface
{
    public function getTotalCustomers(): int;
    public function getTotalJobSheets(): int;
    public function getInProgressCount(): int;
    public function getCompletedCount(): int;
    public function getDeliveredTodayCount(): int;
    public function getNewRequestCount(): int;
    public function getRecentJobSheets(int $limit = 20): Collection;
}
