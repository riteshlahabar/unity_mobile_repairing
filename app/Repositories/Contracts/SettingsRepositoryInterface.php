<?php

namespace App\Repositories\Contracts;

interface SettingsRepositoryInterface
{
    public function getBusinessInfo(): ?object;
    public function getGeneralInfo(): ?object;
    public function getAllSettings(): object;
    public function updateBusinessInfo(array $data): bool;
    public function updateTermsConditions(string $termsConditions): bool;
    public function updateRemarks(string $remarks): bool;
}
