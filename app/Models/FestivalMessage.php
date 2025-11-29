<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FestivalMessage extends Model
{
    protected $table = 'festival_messages';

    protected $fillable = [
        'campaign_name',
        'sent_date',
        'message',
        'total_customers',
        'message_sent',
        'failed_messages',
        'status',
        
    ];

    protected $casts = [
        'sent_date' => 'datetime',
        'total_customers' => 'integer',
        'message_sent' => 'integer',
        'failed_messages' => 'integer',
    ];
}
