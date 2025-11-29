<?php

namespace App\Repositories\Contracts;

use App\Models\FestivalMessage;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Collection;
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
use Illuminate\Pagination\LengthAwarePaginator;

interface FestivalMessageRepositoryInterface extends BaseRepositoryInterface
{
    public function getSentCount(): int;
    public function getFailedCount(): int;
    public function getRecentMessages(int $perPage = 20): LengthAwarePaginator;
<<<<<<< HEAD
    
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
=======
    public function createMessage(int $customerId, string $message, bool $success, array $response): FestivalMessage;
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
}
