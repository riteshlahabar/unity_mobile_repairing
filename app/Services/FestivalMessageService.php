<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class FestivalMessageService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected FestivalMessageRepositoryInterface $festivalMessageRepository,
        protected WhatsAppService $whatsappService
    ) {}

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'totalCustomers' => $this->customerRepository->all()->count(),
            'sentMessages' => $this->festivalMessageRepository->getSentCount(),
            'failedMessages' => $this->festivalMessageRepository->getFailedCount(),
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
                $this->festivalMessageRepository->createMessage(
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
}
