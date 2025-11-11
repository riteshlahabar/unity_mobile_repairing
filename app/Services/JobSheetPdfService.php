<?php

namespace App\Services;

use App\Models\JobSheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class JobSheetPdfService
{
    /**
     * Generate JobSheet PDF
     */
    public function generate(JobSheet $jobSheet)
    {
        $pdf = Pdf::loadView('pdf.jobsheet', compact('jobSheet'));
        
        $fileName = 'JobSheet_' . $jobSheet->jobsheet_id . '.pdf';
        
        // Save to storage/app/public/jobsheets directory
        $filePath = 'jobsheets/' . $fileName;
        
        // Use Storage facade to save
        Storage::disk('public')->put($filePath, $pdf->output());

        return [
            'path' => storage_path('app/public/' . $filePath), // Full server path
            'name' => $fileName,
            'url' => url('storage/' . $filePath) // Public URL
        ];
    }
}
