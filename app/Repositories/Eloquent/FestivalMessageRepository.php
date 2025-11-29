<?php

namespace App\Repositories\Eloquent;

use App\Models\FestivalMessage;
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class FestivalMessageRepository extends BaseRepository implements FestivalMessageRepositoryInterface
{
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
        ]);
    }
}
