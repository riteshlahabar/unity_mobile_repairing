<?php

namespace App\Services;

use App\Repositories\Contracts\SettingsRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class SettingsService
{
    public function __construct(
        protected SettingsRepositoryInterface $repository
    ) {}

    /**
     * Get all settings data
     * 
     * @return object
     */
    public function getAllSettings(): object
    {
        return $this->repository->getAllSettings();
    }

    /**
     * Update business information
     * 
     * @param array $data
     * @return array
     */
    public function updateBusinessInfo(array $data): array
    {
        $existing = $this->repository->getBusinessInfo();
        $this->repository->updateBusinessInfo($data);

        return [
            'success' => true,
            'message' => $existing 
                ? 'Business Info Updated Successfully.' 
                : 'Business Info Saved Successfully.'
        ];
    }

    /**
     * Update terms and conditions
     * 
     * @param string $termsConditions
     * @return array
     */
    public function updateTermsConditions(string $termsConditions): array
    {
        $existing = $this->repository->getGeneralInfo();
        $this->repository->updateTermsConditions($termsConditions);

        return [
            'success' => true,
            'message' => $existing 
                ? 'Terms & Conditions Updated Successfully.' 
                : 'Terms & Conditions Saved Successfully.'
        ];
    }

    /**
     * Update remarks
     * 
     * @param string $remarks
     * @return array
     */
    public function updateRemarks(string $remarks): array
    {
        $existing = $this->repository->getGeneralInfo();
        $this->repository->updateRemarks($remarks);

        return [
            'success' => true,
            'message' => $existing 
                ? 'Remarks Updated Successfully.' 
                : 'Remarks Saved Successfully.'
        ];
    }

    /**
     * Update user password
     * 
     * @param object $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return array
     */
    public function updatePassword(object $user, string $currentPassword, string $newPassword): array
    {
        // Verify current password
        if (!Hash::check($currentPassword, $user->password)) {
            return [
                'success' => false,
                'message' => 'Current password does not match.'
            ];
        }

        // Update password
        $user->password = Hash::make($newPassword);
        $user->save();

        return [
            'success' => true,
            'message' => 'Password Changed Successfully.'
        ];
    }
}
