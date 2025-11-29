<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    protected $fillable = [
        'jobsheet_id',
        'warranty_month',
        'warranty_days',
        'previous_reference_id',
    ];

    public function jobsheet()
    {
        return $this->belongsTo(JobSheet::class, 'jobsheet_id', 'id');
    }
}
