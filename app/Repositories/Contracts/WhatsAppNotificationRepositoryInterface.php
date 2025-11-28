<?php

namespace App\Repositories\Contracts;

use App\Models\WhatsAppNotification;
use Illuminate\Database\Eloquent\Collection;

interface WhatsAppNotificationRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatest(): Collection;
    public function createNotification(array $data): WhatsAppNotification;
    public function updateNotification(int $id, array $data): WhatsAppNotification;
    public function deleteNotification(int $id): bool;
}
