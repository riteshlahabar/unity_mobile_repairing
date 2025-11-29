<?php

namespace App\Repositories\Eloquent;

use App\Models\WhatsAppNotification;
use App\Repositories\Contracts\WhatsAppNotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WhatsAppNotificationRepository extends BaseRepository implements WhatsAppNotificationRepositoryInterface
{
    public function __construct(WhatsAppNotification $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all notifications ordered by latest
     * 
     * @return Collection
     */
    public function getLatest(): Collection
    {
        return $this->model->latest()->get();
    }

    /**
     * Create a new notification
     * 
     * @param array $data
     * @return WhatsAppNotification
     */
    public function createNotification(array $data): WhatsAppNotification
    {
        return $this->model->create([
            'title' => $data['title'],
            'message' => $data['message'],
        ]);
    }

    /**
     * Update a notification
     * 
     * @param int $id
     * @param array $data
     * @return WhatsAppNotification
     */
    public function updateNotification(int $id, array $data): WhatsAppNotification
    {
        $notification = $this->findOrFail($id);
        
        $notification->update([
            'title' => $data['title'],
            'message' => $data['message'],
        ]);

        return $notification->fresh();
    }

    /**
     * Delete a notification
     * 
     * @param int $id
     * @return bool
     */
    public function deleteNotification(int $id): bool
    {
        $notification = $this->findOrFail($id);
        return $notification->delete();
    }
}
