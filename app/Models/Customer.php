<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'full_name',
        'address',
        'contact_no',
        'alternate_no',
        'whatsapp_no',
    ];

    // Relationship with JobSheets
    public function jobSheets()
    {
        return $this->hasMany(JobSheet::class, 'customer_id', 'customer_id');
    }

    // Generate SERIAL Customer ID (UMR0001, UMR0002, etc.)
    public static function generateCustomerId()
    {
        // Get the last customer ID
        $lastCustomer = self::orderBy('id', 'desc')->first();
        
        if (!$lastCustomer) {
            return 'UMR0001'; // First customer
        }
        
        // Extract number from last customer_id (UMR0001 -> 1)
        $lastNumber = intval(substr($lastCustomer->customer_id, 3));
        
        // Increment and format
        $newNumber = $lastNumber + 1;
        
        return 'UMR' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
