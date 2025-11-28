<?php

namespace App\Repositories\Eloquent;

use App\Models\FestivalMessage;
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FestivalMessageRepository extends BaseRepository implements FestivalMessageRepositoryInterface
{
    public function __construct(FestivalMessage $model)
    {
        parent::__construct($model);
    }

    /**
     * Get count of successfully sent messages
     * 
     * @return int
     */
    public function getSentCount(): int
    {
        return $this->model->where('status', 'sent')->count();
    }

    /**
     * Get count of failed messages
     * 
     * @return int
     */
    public function getFailedCount(): int
    {
        return $this->model->where('status', 'failed')->count();
    }

    /**
     * Get recent festival messages with customer details
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getRecentMessages(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->with('customer')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a festival message record
     * 
     * @param int $customerId
     * @param string $message
     * @param bool $success
     * @param array $response
     * @return FestivalMessage
     */
    public function createMessage(int $customerId, string $message, bool $success, array $response): FestivalMessage
    {
        return $this->model->create([
            'customer_id' => $customerId,
            'message' => $message,
            'status' => $success ? 'sent' : 'failed',
            'response' => json_encode($response),
        ]);
    }
}
