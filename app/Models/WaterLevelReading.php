<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterLevelReading extends Model
{
    protected $table = 'water_level_readings';
    
    protected $fillable = [
        'sensor_id',
        'water_level',
        'temperature',
        'humidity',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'water_level' => 'float',
        'temperature' => 'float',
        'humidity' => 'float',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
