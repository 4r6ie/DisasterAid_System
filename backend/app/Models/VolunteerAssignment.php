<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VolunteerAssignment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'volunteer_id',
        'disaster_incident_id',
        'role',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'notes',
        'assigned_by',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function disasterIncident()
    {
        return $this->belongsTo(DisasterIncident::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}