<?php

namespace App\Repositories\Contracts;

use Carbon\Carbon;

interface ProfitRepositoryInterface
{
    public function getAllProfits();
    
    public function getProfitByDateRange(Carbon $startDate, Carbon $endDate): float;
    
    public function updateOrCreate(array $data);
    
    public function getByJobsheetId(int $jobsheetId);
}
