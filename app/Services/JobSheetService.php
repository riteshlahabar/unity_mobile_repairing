<?php

namespace App\Services;

use App\Repositories\Contracts\JobSheetRepositoryInterface;
use App\Services\Contracts\FileStorageServiceInterface;
use App\Services\Contracts\NotificationServiceInterface;
use Illuminate\Support\Facades\Log;

class JobSheetService
{
    public function __construct(
        protected JobSheetRepositoryInterface $repository,
        protected FileStorageServiceInterface $fileStorage,
        protected NotificationServiceInterface $notification
    ) {}

    public function createJobSheet(array $validated, array $files = []): array
    {
        try {
            // Generate JobSheet ID
            $validated['jobsheet_id'] = $this->repository->generateJobSheetId();
            
            // Calculate balance
            $validated['balance'] = $validated['estimated_cost'] - ($validated['advance'] ?? 0);
            
            // Set default status
            $validated['status'] = 'in_progress';

            // Create JobSheet
            $jobSheet = $this->repository->create($validated);

            Log::info('JobSheet Created Successfully:', ['jobsheet_id' => $jobSheet->jobsheet_id]);

            // Handle device photos
            if (!empty($files)) {
                $this->fileStorage->storeDevicePhotos($jobSheet, $files);
            }

            // Send notification
            $this->notification->sendDeviceReceived($jobSheet);

            return [
                'success' => true,
                'message' => 'JobSheet created successfully!',
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'jobsheet' => $jobSheet->load('customer')
            ];

        } catch (\Exception $e) {
            Log::error('JobSheet Creation Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    public function updateJobSheet(string $jobsheetId, array $validated): array
    {
        // Calculate balance
        $validated['balance'] = $validated['estimated_cost'] - $validated['advance'];

        $jobSheet = $this->repository->update($jobsheetId, $validated);

        return [
            'success' => true,
            'message' => 'JobSheet updated successfully!',
            'jobsheet' => $jobSheet
        ];
    }

    public function markComplete(string $jobsheetId): array
    {
        $jobSheet = $this->repository->updateStatus($jobsheetId, 'completed');
        
        $this->notification->sendRepairCompleted($jobSheet);

        return [
            'success' => true,
            'message' => 'JobSheet marked as complete',
            'jobsheet' => [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'status' => $jobSheet->status,
                'balance' => $jobSheet->balance
            ]
        ];
    }

    public function markDelivered(string $jobsheetId): array
    {
        if (!$this->notification->isOTPVerified($jobsheetId)) {
            throw new \Exception('Please verify OTP first');
        }

        $jobSheet = $this->repository->updateStatus($jobsheetId, 'delivered');
        
        $this->notification->clearOTP($jobSheet);
        $this->notification->sendThankYou($jobSheet);

        return [
            'success' => true,
            'message' => 'JobSheet marked as delivered',
            'jobsheet' => [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'status' => $jobSheet->status,
                'balance' => $jobSheet->balance
            ]
        ];
    }

    public function deleteJobSheet(string $jobsheetId): void
    {
        $jobSheet = $this->repository->findByJobSheetId($jobsheetId);
        
        // Delete associated device photos
        $this->fileStorage->deleteDevicePhotos($jobSheet);
        
        // Delete jobsheet
        $this->repository->delete($jobsheetId);
    }
}
