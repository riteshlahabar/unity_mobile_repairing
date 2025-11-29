<?php

namespace App\Services;

use App\Models\JobSheet;
use App\Services\Contracts\PdfServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class JobSheetPdfService implements PdfServiceInterface
{
    /**
     * Generate PDF and save to storage
     * Used for: Email, WhatsApp, archiving
     * Returns: Array with file path and name
     */
    public function generate(JobSheet $jobSheet): array
    {
        try {
            // Load PDF view with jobsheet data
            $pdf = Pdf::loadView('pdf.jobsheet', [
                'jobSheet' => $jobSheet,
                'businessInfo' => $this->getBusinessInfo(),
                'generalInfo' => $this->getGeneralInfo(),
            ]);

            // Create storage directory if not exists
            Storage::makeDirectory('public/jobsheets');

            // Set filename
            $fileName = 'jobsheet_' . $jobSheet->jobsheet_id . '.pdf';
            $filePath = storage_path('app/public/jobsheets/' . $fileName);

            // Save PDF to storage
            $pdf->save($filePath);

            return [
                'success' => true,
                'path' => $filePath,
                'name' => $fileName,
                'url' => asset('storage/jobsheets/' . $fileName),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Stream PDF directly to browser (for printing)
     * Used for: Direct browser print/download
     * Returns: Response object with PDF stream
     */
   public function stream(JobSheet $jobSheet, string $fileName): mixed
{
    try {
        // Load PDF view with jobsheet data
        $pdf = Pdf::loadView('pdf.jobsheet', [
            'jobSheet' => $jobSheet,
            'businessInfo' => $this->getBusinessInfo(),
            'generalInfo' => $this->getGeneralInfo(),
        ]);

        // Stream to browser for printing
        return $pdf->stream($fileName);
    } catch (\Exception $e) {
        \Log::error('PDF Stream Error: ' . $e->getMessage());
        throw $e;
    }
}


    /**
     * Download PDF (alternative to stream for download button)
     */
   public function download(JobSheet $jobSheet, string $fileName): mixed
{
    try {
        $pdf = Pdf::loadView('pdf.jobsheet', [
            'jobSheet' => $jobSheet,
            'businessInfo' => $this->getBusinessInfo(),
            'generalInfo' => $this->getGeneralInfo(),
        ]);

        return $pdf->download($fileName);
    } catch (\Exception $e) {
        \Log::error('PDF Download Error: ' . $e->getMessage());
        throw $e;
    }
}


    /**
     * Get business information for PDF
     */
    private function getBusinessInfo()
    {
        return (object) [
            'business_name' => config('app.business_name', 'Unity Mobiles & Repairing Lab'),
            'address' => config('app.business_address', 'Amravati, India'),
            'mobile_number' => config('app.business_phone', '9876543210'),
        ];
    }

    /**
     * Get general information for PDF
     */
    private function getGeneralInfo()
    {
        return (object) [
            'terms_conditions' => 'Terms and conditions text here',
            'remarks' => 'General remarks',
        ];
    }
}
