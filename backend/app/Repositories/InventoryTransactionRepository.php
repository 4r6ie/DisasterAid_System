<?php

namespace App\Repositories;

use App\Models\InventoryTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryTransactionRepository extends BaseRepository
{
    public function __construct(InventoryTransaction $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['reliefItem', 'processor', 'evacuationCenter', 'disasterIncident']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('reference_number', 'like', "%{$filters['search']}%")
                    ->orWhere('notes', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['relief_item_id'])) {
            $query->where('relief_item_id', $filters['relief_item_id']);
        }

        if (!empty($filters['evacuation_center_id'])) {
            $query->where('evacuation_center_id', $filters['evacuation_center_id']);
        }

        if (!empty($filters['disaster_incident_id'])) {
            $query->where('disaster_incident_id', $filters['disaster_incident_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('transaction_date');
        }

        return $query->paginate($perPage);
    }

    public function getByReliefItem(int $reliefItemId, int $limit = 50): Collection
    {
        return $this->model->where('relief_item_id', $reliefItemId)
            ->latest('transaction_date')
            ->limit($limit)
            ->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byType = $this->model->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $totalIn = $this->model->where('type', 'in')->sum('quantity');
        $totalOut = $this->model->where('type', 'out')->sum('quantity');
        $totalDistributed = $this->model->where('type', 'distribute')->sum('quantity');

        return [
            'total' => $total,
            'by_type' => $byType,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'total_distributed' => $totalDistributed,
            'net_change' => $totalIn - $totalOut - $totalDistributed,
        ];
    }
}