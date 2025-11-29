<?php

namespace App\Services\Contracts;

use App\Models\JobSheet;

interface FileStorageServiceInterface
{
    public function storeDevicePhotos(JobSheet $jobSheet, array $files): void;
    public function deleteDevicePhotos(JobSheet $jobSheet): void;
}
