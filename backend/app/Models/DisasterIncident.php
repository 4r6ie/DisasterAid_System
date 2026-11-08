<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DisasterIncident extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'incident_id',
        'disaster_type',
        'title',
        'description',
        'severity',
        'status',
        'latitude',
        'longitude',
        'address',
        'city',
        'province',
        'affected_count',
        'injured_count',
        'missing_count',
        'deceased_count',
        'rescued_count',
        'occurred_at',
        'resolved_at',
        'reported_by',
        'assigned_responder_id',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'affected_count' => 'integer',
        'injured_count' => 'integer',
        'missing_count' => 'integer',
        'deceased_count' => 'integer',
        'rescued_count' => 'integer',
        'occurred_at' => 'datetime',
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

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedResponder()
    {
        return $this->belongsTo(Responder::class, 'assigned_responder_id');
    }

    public function emergencies()
    {
        return $this->hasMany(EmergencyReport::class, 'disaster_incident_id');
    }

    public function assistanceRequests()
    {
        return $this->hasMany(AssistanceRequest::class, 'disaster_incident_id');
    }

    public function affectedPersons()
    {
        return $this->hasMany(AffectedPerson::class, 'disaster_incident_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'disaster_incident_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'disaster_incident_id');
    }

    public function volunteerAssignments()
    {
        return $this->hasMany(VolunteerAssignment::class, 'disaster_incident_id');
    }

    public function getTotalCasualtiesAttribute(): int
    {
        return $this->affected_count + $this->injured_count + $this->missing_count + $this->deceased_count;
    }
}