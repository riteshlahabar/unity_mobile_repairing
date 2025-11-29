<?php

namespace App\Services\Contracts;

use App\Models\JobSheet;

interface PdfServiceInterface
{
    public function generate(JobSheet $jobSheet): mixed;
    public function download(JobSheet $jobSheet, string $fileName): mixed;
    public function stream(JobSheet $jobSheet, string $fileName): mixed;
}
