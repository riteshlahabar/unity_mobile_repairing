<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Series;
use App\Models\MobileModel;
use App\Models\Color;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BulkMasterImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip header row if present
            if ($index === 0 && strtolower($row[0]) === 'company') continue;

            $company = trim($row[0] ?? '');
            $series = trim($row[1] ?? '');
            $model = trim($row[2] ?? '');
            $color = trim($row[3] ?? '');

            if ($company) {
                Company::firstOrCreate(['name' => $company]);
            }
            if ($series) {
                Series::firstOrCreate(['name' => $series]);
            }
            if ($model) {
                MobileModel::firstOrCreate(['name' => $model]);
            }
            if ($color) {
                Color::firstOrCreate(['name' => $color]);
            }
        }
    }
}
