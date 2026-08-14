<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'address'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function reportedDisasters()
    {
        return $this->hasMany(DisasterIncident::class, 'reported_by');
    }

    public function reportedEmergencies()
    {
        return $this->hasMany(EmergencyReport::class, 'reported_by');
    }

    public function assistanceRequests()
    {
        return $this->hasMany(AssistanceRequest::class, 'requested_by');
    }

    public function managedEvacuationCenters()
    {
        return $this->hasMany(EvacuationCenter::class, 'managed_by');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'processed_by');
    }

    public function createdAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function registeredAffectedPersons()
    {
        return $this->hasMany(AffectedPerson::class, 'registered_by');
    }

    public function responderProfile()
    {
        return $this->hasOne(Responder::class, 'user_id');
    }
}