<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'time_slot',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
