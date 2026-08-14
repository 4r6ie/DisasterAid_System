<?php

namespace App\Repositories;

use App\Models\ReliefItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReliefItemRepository extends BaseRepository
{
    public function __construct(ReliefItem $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('category', 'like', "%{$filters['search']}%")
                    ->orWhere('location', 'like', "%{$filters['search']}%")
                    ->orWhere('batch_number', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['low_stock'])) {
            $query->where('quantity', '<=', 'min_stock');
        }

        if (!empty($filters['expiring_soon'])) {
            $days = $filters['expiring_soon'] ?? 30;
            $query->where('expiry_date', '<=', now()->addDays($days))
                ->where('expiry_date', '>=', now());
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function getLowStock(int $limit = 50): Collection
    {
        return $this->model->lowStock()->limit($limit)->get();
    }

    public function getExpiringSoon(int $days = 30, int $limit = 50): Collection
    {
        return $this->model->expiringSoon($days)->limit($limit)->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byCategory = $this->model->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
        $byStatus = $this->model->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalQuantity = $this->model->sum('quantity');
        $totalValue = $this->model->get()->sum(fn ($item) => $item->total_value);
        $lowStockCount = $this->model->lowStock()->count();
        $expiringSoonCount = $this->model->expiringSoon()->count();

        return [
            'total' => $total,
            'by_category' => $byCategory,
            'by_status' => $byStatus,
            'total_quantity' => $totalQuantity,
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
            'expiring_soon_count' => $expiringSoonCount,
        ];
    }
}