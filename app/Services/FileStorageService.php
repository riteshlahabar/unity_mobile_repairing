<?php

namespace App\Services;

use App\Models\JobSheet;
use App\Models\DevicePhoto;
use App\Services\Contracts\FileStorageServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileStorageService implements FileStorageServiceInterface
{
    public function storeDevicePhotos(JobSheet $jobSheet, array $files): void
    {
        foreach ($files as $photo) {
            $path = $photo->store('device_photos', 'public');
            
            DevicePhoto::create([
                'job_sheet_id' => $jobSheet->id,
                'photo_path' => $path,
            ]);
        }
    }

    public function deleteDevicePhotos(JobSheet $jobSheet): void
    {
        foreach ($jobSheet->devicePhotos as $photo) {
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }
            $photo->delete();
        }
    }
}
