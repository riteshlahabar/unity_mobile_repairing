<?php

namespace App\Services;

use App\Repositories\Contracts\WhatsAppNotificationRepositoryInterface;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    public function __construct(
        protected WhatsAppNotificationRepositoryInterface $repository
    ) {}

    /**
     * Get all notifications
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNotifications()
    {
        return $this->repository->getLatest();
    }

    /**
     * Create a new notification template
     * 
     * @param array $data
     * @return array
     */
    public function createNotification(array $data): array
    {
        try {
            $notification = $this->repository->createNotification($data);

            return [
                'success' => true,
                'message' => 'Notification saved successfully!',
                'notification' => $notification
            ];

        } catch (\Exception $e) {
            Log::error('Notification save error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a notification template
     * 
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateNotification(int $id, array $data): array
    {
        try {
            $notification = $this->repository->updateNotification($id, $data);

            return [
                'success' => true,
                'message' => 'Notification updated successfully!',
                'notification' => $notification
            ];

        } catch (\Exception $e) {
            Log::error('Notification update error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a notification template
     * 
     * @param int $id
     * @return array
     */
    public function deleteNotification(int $id): array
    {
        try {
            $this->repository->deleteNotification($id);

            return [
                'success' => true,
                'message' => 'Notification deleted successfully!'
            ];

        } catch (\Exception $e) {
            Log::error('Notification delete error: ' . $e->getMessage());
            throw $e;
        }
    }
}
