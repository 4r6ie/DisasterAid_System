<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Responder extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'specialization',
        'rank',
        'status',
        'address',
        'date_of_birth',
        'certifications',
        'equipment',
        'user_id',
        'hired_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'certifications' => 'array',
        'equipment' => 'array',
        'hired_at' => 'date',
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

    public function scopeBySpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedDisasters()
    {
        return $this->hasMany(DisasterIncident::class, 'assigned_responder_id');
    }

    public function assignedEmergencies()
    {
        return $this->hasMany(EmergencyReport::class, 'assigned_responder_id');
    }

    public function hasCertification(string $certification): bool
    {
        return in_array($certification, $this->certifications ?? []);
    }
}