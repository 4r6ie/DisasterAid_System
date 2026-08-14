<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmergencyReport extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'emergency_type',
        'description',
        'priority',
        'status',
        'latitude',
        'longitude',
        'address',
        'people_needing_help',
        'contact_name',
        'contact_phone',
        'disaster_incident_id',
        'reported_by',
        'assigned_responder_id',
        'assigned_at',
        'resolved_at',
        'resolution_notes',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'people_needing_help' => 'integer',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeNearby($query, float $lat, float $lng, float $radiusKm = 50)
    {
        return $query->whereRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
             cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
            [$lat, $lng, $lat, $radiusKm]
        );
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function disasterIncident()
    {
        return $this->belongsTo(DisasterIncident::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedResponder()
    {
        return $this->belongsTo(Responder::class, 'assigned_responder_id');
    }
}