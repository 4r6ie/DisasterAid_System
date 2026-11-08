<?php

namespace App\Services;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditLogService
{
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Activity::query()->with('causer');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('description', 'like', "%{$filters['search']}%")
                    ->orWhere('event', 'like', "%{$filters['search']}%")
                    ->orWhere('subject_type', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (!empty($filters['subject_type'])) {
            $query->where('subject_type', $filters['subject_type']);
        }

        if (!empty($filters['causer_id'])) {
            $query->where('causer_id', $filters['causer_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStatistics(): array
    {
        $total = Activity::count();
        $byEvent = Activity::selectRaw('event, count(*) as count')
            ->groupBy('event')
            ->pluck('count', 'event')
            ->toArray();
        $bySubjectType = Activity::selectRaw('subject_type, count(*) as count')
            ->groupBy('subject_type')
            ->pluck('count', 'subject_type')
            ->toArray();
        $byDay = Activity::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return [
            'total' => $total,
            'by_event' => $byEvent,
            'by_subject_type' => $bySubjectType,
            'by_day' => $byDay,
        ];
    }
}