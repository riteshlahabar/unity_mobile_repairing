<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
<<<<<<< HEAD
use App\Models\FestivalMessage;
=======
>>>>>>> 0963cebdc0528a837022693382951a181cdac698

class FestivalMessageService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
<<<<<<< HEAD
        protected FestivalMessageRepositoryInterface $festivalRepository,
=======
        protected FestivalMessageRepositoryInterface $festivalMessageRepository,
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
        protected WhatsAppService $whatsappService
    ) {}

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'totalCustomers' => $this->customerRepository->all()->count(),
<<<<<<< HEAD
            'sentMessages' => $this->festivalRepository->getSentCount(),
            'failedMessages' => $this->festivalRepository->getFailedCount(),
=======
            'sentMessages' => $this->festivalMessageRepository->getSentCount(),
            'failedMessages' => $this->festivalMessageRepository->getFailedCount(),
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
        ];
    }

    /**
     * Send festival messages based on criteria
     * 
     * @param string $message
     * @param string $sendTo ('all' or 'selected')
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function sendFestivalMessagesByCriteria(
        string $message, 
        string $sendTo, 
        ?string $fromDate = null, 
        ?string $toDate = null
    ): array {
        try {
            // Get target customers based on criteria
            $customers = $this->getCustomersByCriteria($sendTo, $fromDate, $toDate);

            if ($customers->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No customers found matching the criteria'
                ];
            }

            $successCount = 0;
            $failCount = 0;

            foreach ($customers as $customer) {
                // Send message via WhatsApp
                $result = $this->whatsappService->sendMessage($customer->whatsapp_no, $message);

                // Save message record
<<<<<<< HEAD
                $this->festivalRepository->createMessage(
=======
                $this->festivalMessageRepository->createMessage(
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
                    $customer->id,
                    $message,
                    $result['success'],
                    $result
                );

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            }

            Log::info('Festival Messages Sent', [
                'total' => count($customers),
                'success' => $successCount,
                'failed' => $failCount
            ]);

            return [
                'success' => true,
                'message' => "Messages sent successfully! Success: {$successCount}, Failed: {$failCount}",
                'success_count' => $successCount,
                'fail_count' => $failCount,
            ];

        } catch (\Exception $e) {
            Log::error('Festival Message Send Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get customers based on criteria
     * 
     * @param string $sendTo
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return Collection
     */
    protected function getCustomersByCriteria(
        string $sendTo, 
        ?string $fromDate = null, 
        ?string $toDate = null
    ): Collection {
        if ($sendTo === 'all') {
            return $this->customerRepository->all();
        }

        // Filter by date range
        return $this->customerRepository->all()->filter(function ($customer) use ($fromDate, $toDate) {
            $createdAt = $customer->created_at->toDateString();
            return $createdAt >= $fromDate && $createdAt <= $toDate;
        });
    }

    /**
     * Get customer count by date range
     * 
     * @param string $fromDate
     * @param string $toDate
     * @return int
     */
    public function getCustomerCountByDateRange(string $fromDate, string $toDate): int
    {
        return $this->customerRepository->all()->filter(function ($customer) use ($fromDate, $toDate) {
            $createdAt = $customer->created_at->toDateString();
            return $createdAt >= $fromDate && $createdAt <= $toDate;
        })->count();
    }

    /**
     * Get customers for selection list
     */
    public function getCustomersForSelection(): Collection
    {
        return $this->customerRepository->all()
            ->sortBy('full_name')
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'customer_id' => $customer->customer_id,
                    'full_name' => $customer->full_name,
                    'contact_no' => $customer->contact_no,
                    'whatsapp_no' => $customer->whatsapp_no,
                ];
            });
    }
<<<<<<< HEAD
    
    /**
     * Persist campaign summary
     */
    protected function persistCampaign(
        string $message,
        ?string $campaignName,
        int $totalCustomers,
        array $sendResult
    ): FestivalMessage {
        $campaignData = [
            'campaign_name' => $campaignName ?? 'Campaign - ' . now()->format('Y-m-d H:i'),
            'message' => $message,
            'total_customers' => $totalCustomers,
            'message_sent' => $sendResult['sent'],
            'failed_messages' => $sendResult['failed'],
            'status' => $sendResult['status'],
            'sent_date' => now(), // always use today's date
        ];

        return $this->festivalRepository->createCampaign($campaignData);
    }

    /**
     * Send messages to customers and record campaign
     * 
     * @param string $message
     * @param string $sendTo
     * @param array $customerIds
     * @param string|null $campaignName
     * @return array
     */
   public function sendFestivalMessages(
    string $message,
    string $sendTo,
    array $customerIds,
    ?string $campaignName = null
): array {
    
    // msg formatting in json
   $plainMessage = preg_replace('/\\\\n/', "\n", strip_tags($message));
    
    $total = count($customerIds);
    $sent = 0;
    $failed = 0;

    foreach ($customerIds as $customerId) {
        $customer = \App\Models\Customer::find($customerId);
        if (!$customer || !$customer->whatsapp_no) {
            $failed++;
            continue;
        }

        try {
            $this->whatsappService->sendMessage(
                $customer->whatsapp_no,
                $plainMessage  // Use plain text without HTML tags
            );

            $sent++;
        } catch (\Throwable $e) {
            Log::error('WhatsApp send failed for customer ' . $customerId . ': ' . $e->getMessage());
            $failed++;
        }
    }

    $status = 'sent';
    if ($failed > 0 && $sent > 0) {
        $status = 'partial';
    } elseif ($sent === 0) {
        $status = 'failed';
    }

    $campaign = $this->festivalRepository->createCampaign(
        $campaignName ?? 'Campaign - ' . now()->format('Y-m-d H:i'),
        $message,  // Save original HTML version to database
        $total,
        $sent,
        $failed,
        $status,
        now()->format('Y-m-d')
    );

    return [
        'success' => true,
        'message' => "Messages sent: {$sent}, failed: {$failed}",
        'campaign_id' => $campaign->id,
        'success_count' => $sent,
        'fail_count' => $failed,
        'campaign' => $campaign,
    ];
}

=======
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
}
