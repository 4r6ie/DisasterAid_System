<?php

namespace App\Repositories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementRepository extends BaseRepository
{
    public function __construct(Announcement $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['disasterIncident', 'creator']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('content', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['audience'])) {
            $query->where('audience', $filters['audience']);
        }

        if (!empty($filters['is_published'])) {
            $query->where('is_published', $filters['is_published']);
        }

        if (!empty($filters['disaster_incident_id'])) {
            $query->where('disaster_incident_id', $filters['disaster_incident_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('published_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('published_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('published_at');
        }

        return $query->paginate($perPage);
    }

    public function getActive(int $limit = 50): Collection
    {
        return $this->model->published()->latest('published_at')->limit($limit)->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byType = $this->model->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
        $byPriority = $this->model->selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
        $byAudience = $this->model->selectRaw('audience, count(*) as count')
            ->groupBy('audience')
            ->pluck('count', 'audience')
            ->toArray();

        $publishedCount = $this->model->where('is_published', true)->count();

        return [
            'total' => $total,
            'by_type' => $byType,
            'by_priority' => $byPriority,
            'by_audience' => $byAudience,
            'published_count' => $publishedCount,
            'draft_count' => $total - $publishedCount,
        ];
    }
}