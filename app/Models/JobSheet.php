<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobsheet_id',
        'customer_id',
        'company',
        'model',
        'color',
        'series',
        'imei',
        'problem_description',
        'status_dead',
        'status_damage',
        'status_on',
        'accessory_sim_tray',
        'accessory_sim_card',
        'accessory_memory_card',
        'accessory_mobile_cover',
        'other_accessories',
        'device_password',
        'pattern_image',
        'device_condition',
        'water_damage',
        'physical_damage',
        'technician',
        'location',
        'delivered_date',
        'delivered_time',
        'estimated_cost',
        'advance',
        'balance',
        'notes',        
        'status',
    ];

    protected $casts = [
        'status_dead' => 'boolean',
        'status_damage' => 'boolean',
        'status_on' => 'boolean',
        'accessory_sim_tray' => 'boolean',
        'accessory_sim_card' => 'boolean',
        'accessory_memory_card' => 'boolean',
        'accessory_mobile_cover' => 'boolean',
        'jobsheet_required' => 'boolean',
        'delivered_date' => 'date',
        'delivered_time' => 'datetime:H:i',
    ];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Relationship with Device Photos
    public function devicePhotos()
{
    return $this->hasMany(DevicePhoto::class, 'job_sheet_id', 'id');
}
    // Generate SERIAL JobSheet ID (JS0001, JS0002, JS0003, etc.) - CONTINUOUS
    public static function generateJobSheetId()
    {
        // Get the last jobsheet ID across ALL customers
        $lastJobSheet = self::orderBy('id', 'desc')->first();
        
        if (!$lastJobSheet) {
            return 'JS0001'; // First jobsheet ever
        }
        
        // Extract number from last jobsheet_id (JS0001 -> 1)
        $lastNumber = intval(substr($lastJobSheet->jobsheet_id, 2));
        
        // Increment and format
        $newNumber = $lastNumber + 1;
        
        return 'JS' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    // Relationship with Device Photos


}
