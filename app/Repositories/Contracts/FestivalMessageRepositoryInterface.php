<?php

namespace App\Repositories\Contracts;

use App\Models\FestivalMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface FestivalMessageRepositoryInterface extends BaseRepositoryInterface
{
    public function getSentCount(): int;
    public function getFailedCount(): int;
    public function getRecentMessages(int $perPage = 20): LengthAwarePaginator;
    public function createMessage(int $customerId, string $message, bool $success, array $response): FestivalMessage;
}
