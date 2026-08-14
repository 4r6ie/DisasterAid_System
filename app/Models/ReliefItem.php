<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ReliefItem extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'category',
        'unit',
        'quantity',
        'min_stock',
        'unit_cost',
        'location',
        'expiry_date',
        'batch_number',
        'description',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_stock' => 'integer',
        'unit_cost' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', 'min_stock');
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'relief_item_id');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->diffInDays(now()) <= 30;
    }

    public function getTotalValueAttribute(): float
    {
        return $this->quantity * ($this->unit_cost ?? 0);
    }
}