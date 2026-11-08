<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EvacuationCenter extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'capacity',
        'current_occupancy',
        'status',
        'facilities',
        'contact_person',
        'contact_phone',
        'contact_email',
        'managed_by',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'facilities' => 'array',
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

    public function scopeWithAvailability($query)
    {
        return $query->whereColumn('current_occupancy', '<', 'capacity');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function affectedPersons()
    {
        return $this->hasMany(AffectedPerson::class, 'evacuation_center_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'evacuation_center_id');
    }

    public function getOccupancyPercentageAttribute(): float
    {
        if ($this->capacity === 0) {
            return 0;
        }
        return round(($this->current_occupancy / $this->capacity) * 100, 2);
    }

    public function getAvailableCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }
}