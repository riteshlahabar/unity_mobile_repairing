<?php

namespace App\Repositories\Eloquent;

use App\Models\FestivalMessage;
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Collection;
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
use Illuminate\Pagination\LengthAwarePaginator;

class FestivalMessageRepository extends BaseRepository implements FestivalMessageRepositoryInterface
{
<<<<<<< HEAD
    public function __construct()
    {
        parent::__construct(new FestivalMessage());
    }

    public function getSentCount(): int
    {
        return FestivalMessage::where('status', 'sent')->count();
    }

    public function getFailedCount(): int
    {
        return FestivalMessage::where('status', 'failed')->count();
    }

    public function getRecentMessages(int $perPage = 20): LengthAwarePaginator
    {
        // FIXED: Removed .with('customer') - no relationship exists
        return FestivalMessage::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * REMOVED: createMessage() - replaced with createCampaign for bulk campaigns
     * public function createMessage(...) { ... }
     */

    /**
     * NEW: Bulk campaign method matching interface
     */
    public function createCampaign(
        string $campaignName, 
        string $message, 
        int $totalCustomers, 
        int $messageSent, 
        int $failedMessages, 
        string $status, 
        ?string $sentDate = null
    ): FestivalMessage {
        $message = preg_replace('#<\/?p>#', "\n", $message);
        $message = trim($message);
        return FestivalMessage::create([
            'campaign_name' => $campaignName,
            'message' => $message,
            'total_customers' => $totalCustomers,
            'message_sent' => $messageSent,
            'failed_messages' => $failedMessages,
            'status' => $status,
            'sent_date' => $sentDate ?? now()->format('Y-m-d'),
=======
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
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
        ]);
    }
}
