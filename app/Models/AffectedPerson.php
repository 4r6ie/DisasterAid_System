<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AffectedPerson extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'disaster_incident_id',
        'name',
        'age',
        'gender',
        'contact_phone',
        'address',
        'condition',
        'medical_notes',
        'family_contact_name',
        'family_contact_phone',
        'evacuation_center_id',
        'evacuated_at',
        'registered_by',
        'notes',
    ];

    protected $casts = [
        'age' => 'integer',
        'evacuated_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeByCondition($query, string $condition)
    {
        return $query->where('condition', $condition);
    }

    public function disasterIncident()
    {
        return $this->belongsTo(DisasterIncident::class);
    }

    public function evacuationCenter()
    {
        return $this->belongsTo(EvacuationCenter::class);
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'safe' => 'Safe',
            'injured' => 'Injured',
            'critical' => 'Critical',
            'missing' => 'Missing',
            'deceased' => 'Deceased',
            'rescued' => 'Rescued',
            default => ucfirst($this->condition),
        };
    }
}