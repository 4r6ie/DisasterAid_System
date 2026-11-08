<?php

use App\Http\Controllers\API\V1\AffectedPersonController;
use App\Http\Controllers\API\V1\AnnouncementController;
use App\Http\Controllers\API\V1\AssistanceRequestController;
use App\Http\Controllers\API\V1\AuditLogController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\DisasterIncidentController;
use App\Http\Controllers\API\V1\EmergencyReportController;
use App\Http\Controllers\API\V1\EvacuationCenterController;
use App\Http\Controllers\API\V1\ReliefItemController;
use App\Http\Controllers\API\V1\ResponderController;
use App\Http\Controllers\API\V1\VolunteerAssignmentController;
use App\Http\Controllers\API\V1\VolunteerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public endpoints
|--------------------------------------------------------------------------
|
| These routes are intentionally unauthenticated. Authorization for every
| other route is enforced inside the controllers through the policies in
| App\Policies (and the Gate abilities registered in AppServiceProvider).
|
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

/*
|--------------------------------------------------------------------------
| Authenticated endpoints
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
    });

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('charts', [DashboardController::class, 'charts'])->name('dashboard.charts');
        Route::get('map', [DashboardController::class, 'map'])->name('dashboard.map');
    });

    /*
    |----------------------------------------------------------------------
    | Disaster incidents
    |----------------------------------------------------------------------
    */
    Route::prefix('disasters')->group(function () {
        Route::get('/', [DisasterIncidentController::class, 'index'])->name('disasters.index');
        Route::post('/', [DisasterIncidentController::class, 'store'])->name('disasters.store');

        Route::get('nearby', [DisasterIncidentController::class, 'nearby'])->name('disasters.nearby');
        Route::get('statistics', [DisasterIncidentController::class, 'statistics'])->name('disasters.statistics');

        Route::get('{id}', [DisasterIncidentController::class, 'show'])->name('disasters.show');
        Route::put('{id}', [DisasterIncidentController::class, 'update'])->name('disasters.update');
        Route::delete('{id}', [DisasterIncidentController::class, 'destroy'])->name('disasters.destroy');

        Route::post('{id}/assign-responder', [DisasterIncidentController::class, 'assignResponder'])->name('disasters.assign-responder');
        Route::patch('{id}/status', [DisasterIncidentController::class, 'updateStatus'])->name('disasters.status');
        Route::patch('{id}/casualties', [DisasterIncidentController::class, 'updateCasualties'])->name('disasters.casualties');
    });

    /*
    |----------------------------------------------------------------------
    | Emergency reports
    |----------------------------------------------------------------------
    */
    Route::prefix('emergency-reports')->group(function () {
        Route::get('/', [EmergencyReportController::class, 'index'])->name('emergency-reports.index');
        Route::post('/', [EmergencyReportController::class, 'store'])->name('emergency-reports.store');

        Route::get('nearby', [EmergencyReportController::class, 'nearby'])->name('emergency-reports.nearby');
        Route::get('statistics', [EmergencyReportController::class, 'statistics'])->name('emergency-reports.statistics');

        Route::get('{id}', [EmergencyReportController::class, 'show'])->name('emergency-reports.show');
        Route::put('{id}', [EmergencyReportController::class, 'update'])->name('emergency-reports.update');
        Route::delete('{id}', [EmergencyReportController::class, 'destroy'])->name('emergency-reports.destroy');

        Route::post('{id}/assign-responder', [EmergencyReportController::class, 'assignResponder'])->name('emergency-reports.assign-responder');
        Route::patch('{id}/status', [EmergencyReportController::class, 'updateStatus'])->name('emergency-reports.status');
    });

    /*
    |----------------------------------------------------------------------
    | Assistance requests
    |----------------------------------------------------------------------
    */
    Route::prefix('assistance-requests')->group(function () {
        Route::get('/', [AssistanceRequestController::class, 'index'])->name('assistance-requests.index');
        Route::post('/', [AssistanceRequestController::class, 'store'])->name('assistance-requests.store');

        Route::get('statistics', [AssistanceRequestController::class, 'statistics'])->name('assistance-requests.statistics');

        Route::get('{id}', [AssistanceRequestController::class, 'show'])->name('assistance-requests.show');
        Route::put('{id}', [AssistanceRequestController::class, 'update'])->name('assistance-requests.update');
        Route::delete('{id}', [AssistanceRequestController::class, 'destroy'])->name('assistance-requests.destroy');

        Route::post('{id}/approve', [AssistanceRequestController::class, 'approve'])->name('assistance-requests.approve');
        Route::post('{id}/dispatch', [AssistanceRequestController::class, 'dispatch'])->name('assistance-requests.dispatch');
        Route::post('{id}/complete', [AssistanceRequestController::class, 'complete'])->name('assistance-requests.complete');
        Route::post('{id}/reject', [AssistanceRequestController::class, 'reject'])->name('assistance-requests.reject');
    });

    /*
    |----------------------------------------------------------------------
    | Evacuation centers
    |----------------------------------------------------------------------
    */
    Route::prefix('evacuation-centers')->group(function () {
        Route::get('/', [EvacuationCenterController::class, 'index'])->name('evacuation-centers.index');
        Route::post('/', [EvacuationCenterController::class, 'store'])->name('evacuation-centers.store');

        Route::get('nearby', [EvacuationCenterController::class, 'nearby'])->name('evacuation-centers.nearby');
        Route::get('statistics', [EvacuationCenterController::class, 'statistics'])->name('evacuation-centers.statistics');

        Route::get('{id}', [EvacuationCenterController::class, 'show'])->name('evacuation-centers.show');
        Route::put('{id}', [EvacuationCenterController::class, 'update'])->name('evacuation-centers.update');
        Route::delete('{id}', [EvacuationCenterController::class, 'destroy'])->name('evacuation-centers.destroy');

        Route::patch('{id}/occupancy', [EvacuationCenterController::class, 'updateOccupancy'])->name('evacuation-centers.occupancy');
    });

    /*
    |----------------------------------------------------------------------
    | Relief items and inventory
    |----------------------------------------------------------------------
    */
    Route::prefix('relief-items')->group(function () {
        Route::get('/', [ReliefItemController::class, 'index'])->name('relief-items.index');
        Route::post('/', [ReliefItemController::class, 'store'])->name('relief-items.store');

        Route::get('statistics', [ReliefItemController::class, 'statistics'])->name('relief-items.statistics');
        Route::get('low-stock', [ReliefItemController::class, 'lowStock'])->name('relief-items.low-stock');
        Route::get('expiring-soon', [ReliefItemController::class, 'expiringSoon'])->name('relief-items.expiring-soon');

        Route::get('{id}', [ReliefItemController::class, 'show'])->name('relief-items.show');
        Route::put('{id}', [ReliefItemController::class, 'update'])->name('relief-items.update');
        Route::delete('{id}', [ReliefItemController::class, 'destroy'])->name('relief-items.destroy');

        Route::post('{id}/stock-in', [ReliefItemController::class, 'stockIn'])->name('relief-items.stock-in');
        Route::post('{id}/stock-out', [ReliefItemController::class, 'stockOut'])->name('relief-items.stock-out');
        Route::post('{id}/distribute', [ReliefItemController::class, 'distribute'])->name('relief-items.distribute');
        Route::get('{id}/transactions', [ReliefItemController::class, 'transactions'])->name('relief-items.transactions');
    });

    /*
    |----------------------------------------------------------------------
    | Responders
    |----------------------------------------------------------------------
    */
    Route::prefix('responders')->group(function () {
        Route::get('/', [ResponderController::class, 'index'])->name('responders.index');
        Route::post('/', [ResponderController::class, 'store'])->name('responders.store');

        Route::get('statistics', [ResponderController::class, 'statistics'])->name('responders.statistics');

        Route::get('{id}', [ResponderController::class, 'show'])->name('responders.show');
        Route::put('{id}', [ResponderController::class, 'update'])->name('responders.update');
        Route::delete('{id}', [ResponderController::class, 'destroy'])->name('responders.destroy');

        Route::patch('{id}/status', [ResponderController::class, 'updateStatus'])->name('responders.status');
    });

    /*
    |----------------------------------------------------------------------
    | Volunteers and assignments
    |----------------------------------------------------------------------
    */
    Route::prefix('volunteers')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('volunteers.index');
        Route::post('/', [VolunteerController::class, 'store'])->name('volunteers.store');

        Route::get('statistics', [VolunteerController::class, 'statistics'])->name('volunteers.statistics');

        Route::get('{id}', [VolunteerController::class, 'show'])->name('volunteers.show');
        Route::put('{id}', [VolunteerController::class, 'update'])->name('volunteers.update');
        Route::delete('{id}', [VolunteerController::class, 'destroy'])->name('volunteers.destroy');

        Route::patch('{id}/status', [VolunteerController::class, 'updateStatus'])->name('volunteers.status');
    });

    Route::prefix('volunteer-assignments')->group(function () {
        Route::get('/', [VolunteerAssignmentController::class, 'index'])->name('volunteer-assignments.index');
        Route::post('/', [VolunteerAssignmentController::class, 'store'])->name('volunteer-assignments.store');

        Route::get('{id}', [VolunteerAssignmentController::class, 'show'])->name('volunteer-assignments.show');
        Route::put('{id}', [VolunteerAssignmentController::class, 'update'])->name('volunteer-assignments.update');
        Route::delete('{id}', [VolunteerAssignmentController::class, 'destroy'])->name('volunteer-assignments.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Affected persons
    |----------------------------------------------------------------------
    */
    Route::prefix('affected-persons')->group(function () {
        Route::get('/', [AffectedPersonController::class, 'index'])->name('affected-persons.index');
        Route::post('/', [AffectedPersonController::class, 'store'])->name('affected-persons.store');

        Route::get('statistics', [AffectedPersonController::class, 'statistics'])->name('affected-persons.statistics');

        Route::get('{id}', [AffectedPersonController::class, 'show'])->name('affected-persons.show');
        Route::put('{id}', [AffectedPersonController::class, 'update'])->name('affected-persons.update');
        Route::delete('{id}', [AffectedPersonController::class, 'destroy'])->name('affected-persons.destroy');

        Route::patch('{id}/condition', [AffectedPersonController::class, 'updateCondition'])->name('affected-persons.condition');
        Route::post('{id}/assign-center', [AffectedPersonController::class, 'assignEvacuationCenter'])->name('affected-persons.assign-center');
    });

    /*
    |----------------------------------------------------------------------
    | Announcements
    |----------------------------------------------------------------------
    */
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/', [AnnouncementController::class, 'store'])->name('announcements.store');

        Route::get('statistics', [AnnouncementController::class, 'statistics'])->name('announcements.statistics');

        Route::get('{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::put('{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        Route::post('{id}/publish', [AnnouncementController::class, 'publish'])->name('announcements.publish');
    });

    /*
    |----------------------------------------------------------------------
    | Audit logs
    |----------------------------------------------------------------------
    */
    Route::prefix('audit-logs')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('statistics', [AuditLogController::class, 'statistics'])->name('audit-logs.statistics');
    });
});
