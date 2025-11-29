<?php

namespace App\Repositories\Contracts;

use App\Models\FestivalMessage;
use Illuminate\Pagination\LengthAwarePaginator;

interface FestivalMessageRepositoryInterface extends BaseRepositoryInterface
{
    public function getSentCount(): int;
    public function getFailedCount(): int;
    public function getRecentMessages(int $perPage = 20): LengthAwarePaginator;
    
    // FIXED: No response_data parameter
    public function createCampaign(
        string $campaignName, 
        string $message, 
        int $totalCustomers, 
        int $messageSent, 
        int $failedMessages, 
        string $status, 
        ?string $sentDate = null
    ): FestivalMessage;
}
