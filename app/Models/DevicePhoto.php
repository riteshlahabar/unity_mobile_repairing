<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_sheet_id',
        'photo_path',
    ];

    // Relationship with JobSheet
    public function jobSheet()
    {
        return $this->belongsTo(JobSheet::class);
    }
}
