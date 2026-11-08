<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\AuditLogResource;
use App\Http\Resources\API\V1\AuditLogCollection;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    public function __construct(
        protected AuditLogService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAuditLogs');
        $filters = request()->only(['search', 'event', 'subject_type', 'causer_id', 'date_from', 'date_to']);
        $perPage = $this->perPage();
        
        $logs = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new AuditLogCollection($logs))->toArray(request()),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAuditLogs');
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}