<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobsheet_id',
        'service_charge',
        'spare_parts_charge',
        'other_charge',
        'estimated_cost',
        'profit'
    ];

    public function jobSheet()
    {
        return $this->belongsTo(JobSheet::class, 'jobsheet_id', 'id');
    }
}
