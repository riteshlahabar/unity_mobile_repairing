<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FestivalMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'message',
        'status',
        'response',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
