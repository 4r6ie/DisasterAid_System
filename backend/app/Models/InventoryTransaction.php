<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InventoryTransaction extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'relief_item_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'reference_number',
        'unit_cost',
        'total_cost',
        'processed_by',
        'evacuation_center_id',
        'disaster_incident_id',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reference_id' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function reliefItem()
    {
        return $this->belongsTo(ReliefItem::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function evacuationCenter()
    {
        return $this->belongsTo(EvacuationCenter::class);
    }

    public function disasterIncident()
    {
        return $this->belongsTo(DisasterIncident::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'distribute' => 'Distribution',
            'adjustment' => 'Adjustment',
            'expired' => 'Expired',
            'damaged' => 'Damaged',
            'returned' => 'Returned',
            default => ucfirst($this->type),
        };
    }
}