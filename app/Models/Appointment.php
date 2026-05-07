<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
        'diagnosis',
        'treatment',
        'notes',
        'medications',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'string',
        'end_time'   => 'string',
        'status'     => 'integer',
        'duration'   => 'integer',
        'medications'=> 'array',
    ];

    // Constantes de estado
    const STATUS_SCHEDULED  = 1;
    const STATUS_COMPLETED  = 2;
    const STATUS_CANCELLED  = 3;

    public static $statusLabels = [
        self::STATUS_SCHEDULED => 'Programado',
        self::STATUS_COMPLETED => 'Completado',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

    public static $statusColors = [
        self::STATUS_SCHEDULED => 'blue',
        self::STATUS_COMPLETED => 'green',
        self::STATUS_CANCELLED => 'red',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? 'Desconocido';
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'gray';
    }

    // Relaciones
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
