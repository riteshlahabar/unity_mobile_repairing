<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\SettingsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SettingsRepository implements SettingsRepositoryInterface
{
    /**
     * Get business information
     * 
     * @return object|null
     */
    public function getBusinessInfo(): ?object
    {
        return DB::table('business_info')->first();
    }

    /**
     * Get general information (terms, remarks)
     * 
     * @return object|null
     */
    public function getGeneralInfo(): ?object
    {
        return DB::table('general_info')->first();
    }

    /**
     * Get all settings merged together
     * 
     * @return object
     */
    public function getAllSettings(): object
    {
        $businessInfo = $this->getBusinessInfo();
        $generalInfo = $this->getGeneralInfo();

        // Merge both objects, prioritizing business_info
        return (object) array_merge(
            (array) $generalInfo,
            (array) $businessInfo
        );
    }

    /**
     * Update or create business information
     * 
     * @param array $data
     * @return bool
     */
    public function updateBusinessInfo(array $data): bool
    {
        $data['updated_at'] = now();
        $existing = $this->getBusinessInfo();

        if ($existing) {
            return DB::table('business_info')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['created_at'] = now();
            return DB::table('business_info')->insert($data);
        }
    }

    /**
     * Update or create terms and conditions
     * 
     * @param string $termsConditions
     * @return bool
     */
    public function updateTermsConditions(string $termsConditions): bool
    {
        $data = [
            'terms_conditions' => $termsConditions,
            'updated_at' => now()
        ];

        $existing = $this->getGeneralInfo();

        if ($existing) {
            return DB::table('general_info')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['created_at'] = now();
            return DB::table('general_info')->insert($data);
        }
    }

    /**
     * Update or create remarks
     * 
     * @param string $remarks
     * @return bool
     */
    public function updateRemarks(string $remarks): bool
    {
        $data = [
            'remarks' => $remarks,
            'updated_at' => now()
        ];

        $existing = $this->getGeneralInfo();

        if ($existing) {
            return DB::table('general_info')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['created_at'] = now();
            return DB::table('general_info')->insert($data);
        }
    }
}
