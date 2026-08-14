<?php

namespace App\Services;

use App\Models\DisasterIncident;
use App\Models\EmergencyReport;
use App\Models\AssistanceRequest;
use App\Models\EvacuationCenter;
use App\Models\ReliefItem;
use App\Models\AffectedPerson;
use App\Models\Announcement;
use App\Models\Volunteer;
use App\Models\Responder;
use Carbon\Carbon;

class DashboardService
{
    public function getStats(): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'disasters' => [
                'total' => DisasterIncident::count(),
                'active' => DisasterIncident::where('status', 'active')->count(),
                'critical' => DisasterIncident::where('severity', 'critical')->count(),
                'this_month' => DisasterIncident::where('occurred_at', '>=', $thisMonth)->count(),
            ],
            'emergencies' => [
                'total' => EmergencyReport::count(),
                'pending' => EmergencyReport::where('status', 'pending')->count(),
                'in_progress' => EmergencyReport::where('status', 'in_progress')->count(),
                'critical' => EmergencyReport::where('priority', 'critical')->count(),
            ],
            'assistance' => [
                'total' => AssistanceRequest::count(),
                'pending' => AssistanceRequest::where('status', 'pending')->count(),
                'dispatched' => AssistanceRequest::where('status', 'dispatched')->count(),
                'urgent' => AssistanceRequest::where('urgency', 'critical')->count(),
            ],
            'evacuation' => [
                'total_centers' => EvacuationCenter::count(),
                'active_centers' => EvacuationCenter::where('status', 'active')->count(),
                'total_capacity' => EvacuationCenter::sum('capacity'),
                'current_occupancy' => EvacuationCenter::sum('current_occupancy'),
            ],
            'inventory' => [
                'total_items' => ReliefItem::count(),
                'low_stock' => ReliefItem::where('quantity', '<=', 'min_stock')->count(),
                'expiring_soon' => ReliefItem::where('expiry_date', '<=', Carbon::now()->addDays(30))
                    ->where('expiry_date', '>=', Carbon::now())->count(),
                'total_value' => ReliefItem::get()->sum(fn ($item) => $item->total_value),
            ],
            'people' => [
                'affected' => AffectedPerson::count(),
                'in_evacuation' => AffectedPerson::whereNotNull('evacuation_center_id')->count(),
                'injured' => AffectedPerson::where('condition', 'injured')->count(),
                'missing' => AffectedPerson::where('condition', 'missing')->count(),
                'volunteers' => Volunteer::where('status', 'available')->count(),
                'responders' => Responder::where('status', 'available')->count(),
            ],
        ];
    }

    public function getCharts(): array
    {
        $disasterTrends = $this->getDisasterTrends(30);
        $disasterByType = $this->getDisasterByType();
        $disasterBySeverity = $this->getDisasterBySeverity();
        $emergenciesByPriority = $this->getEmergenciesByPriority();
        $assistanceByType = $this->getAssistanceByType();
        $occupancyTrend = $this->getOccupancyTrend(30);

        return [
            'disaster_trends' => $disasterTrends,
            'disaster_by_type' => $disasterByType,
            'disaster_by_severity' => $disasterBySeverity,
            'emergencies_by_priority' => $emergenciesByPriority,
            'assistance_by_type' => $assistanceByType,
            'occupancy_trend' => $occupancyTrend,
        ];
    }

    public function getMapData(): array
    {
        $disasters = DisasterIncident::select('id', 'incident_id', 'title', 'disaster_type', 'severity', 'status', 'latitude', 'longitude', 'affected_count')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'incident_id' => $d->incident_id,
                'title' => $d->title,
                'type' => 'disaster',
                'subtype' => $d->disaster_type,
                'severity' => $d->severity,
                'status' => $d->status,
                'lat' => (float) $d->latitude,
                'lng' => (float) $d->longitude,
                'count' => $d->affected_count,
            ])
            ->toArray();

        $emergencies = EmergencyReport::select('id', 'emergency_type', 'priority', 'status', 'latitude', 'longitude', 'people_needing_help')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'title' => $e->emergency_type,
                'type' => 'emergency',
                'subtype' => $e->emergency_type,
                'priority' => $e->priority,
                'status' => $e->status,
                'lat' => (float) $e->latitude,
                'lng' => (float) $e->longitude,
                'count' => $e->people_needing_help,
            ])
            ->toArray();

        $centers = EvacuationCenter::select('id', 'name', 'latitude', 'longitude', 'capacity', 'current_occupancy', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->name,
                'type' => 'evacuation_center',
                'status' => $c->status,
                'lat' => (float) $c->latitude,
                'lng' => (float) $c->longitude,
                'capacity' => $c->capacity,
                'occupancy' => $c->current_occupancy,
            ])
            ->toArray();

        return array_merge($disasters, $emergencies, $centers);
    }

    protected function getDisasterTrends(int $days): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $data = DisasterIncident::where('occurred_at', '>=', $startDate)
            ->selectRaw('DATE(occurred_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $result = [];
        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $result[$date] = 0;
        }

        foreach ($data as $item) {
            $result[$item->date] = (int) $item->count;
        }

        return $result;
    }

    protected function getDisasterByType(): array
    {
        return DisasterIncident::selectRaw('disaster_type, count(*) as count')
            ->groupBy('disaster_type')
            ->pluck('count', 'disaster_type')
            ->toArray();
    }

    protected function getDisasterBySeverity(): array
    {
        return DisasterIncident::selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();
    }

    protected function getEmergenciesByPriority(): array
    {
        return EmergencyReport::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    protected function getAssistanceByType(): array
    {
        return AssistanceRequest::selectRaw('assistance_type, count(*) as count')
            ->groupBy('assistance_type')
            ->pluck('count', 'assistance_type')
            ->toArray();
    }

    protected function getOccupancyTrend(int $days): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $data = EvacuationCenter::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, sum(current_occupancy) as occupancy, sum(capacity) as capacity')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $result = [];
        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $result[$date] = ['occupancy' => 0, 'capacity' => 0];
        }

        foreach ($data as $item) {
            $result[$item->date] = [
                'occupancy' => (int) $item->occupancy,
                'capacity' => (int) $item->capacity,
            ];
        }

        return $result;
    }
}