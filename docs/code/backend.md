---
tags: [code, backend, hub]
---

# Backend code map

`backend/app/` — Laravel application layer.

Related notes: [[Code Map]] · [[docs/code/frontend]] · [[docs/architecture/overview]]

## By layer

### Controller (14)

- [[docs/code/backend/AffectedPersonController]] — `backend/app\Http\Controllers\API\V1\AffectedPersonController.php`
- [[docs/code/backend/AnnouncementController]] — `backend/app\Http\Controllers\API\V1\AnnouncementController.php`
- [[docs/code/backend/AssistanceRequestController]] — `backend/app\Http\Controllers\API\V1\AssistanceRequestController.php`
- [[docs/code/backend/AuditLogController]] — `backend/app\Http\Controllers\API\V1\AuditLogController.php`
- [[docs/code/backend/AuthController]] — `backend/app\Http\Controllers\API\V1\AuthController.php`
- [[docs/code/backend/Controller]] — `backend/app\Http\Controllers\Controller.php`
- [[docs/code/backend/DashboardController]] — `backend/app\Http\Controllers\API\V1\DashboardController.php`
- [[docs/code/backend/DisasterIncidentController]] — `backend/app\Http\Controllers\API\V1\DisasterIncidentController.php`
- [[docs/code/backend/EmergencyReportController]] — `backend/app\Http\Controllers\API\V1\EmergencyReportController.php`
- [[docs/code/backend/EvacuationCenterController]] — `backend/app\Http\Controllers\API\V1\EvacuationCenterController.php`
- [[docs/code/backend/ReliefItemController]] — `backend/app\Http\Controllers\API\V1\ReliefItemController.php`
- [[docs/code/backend/ResponderController]] — `backend/app\Http\Controllers\API\V1\ResponderController.php`
- [[docs/code/backend/VolunteerAssignmentController]] — `backend/app\Http\Controllers\API\V1\VolunteerAssignmentController.php`
- [[docs/code/backend/VolunteerController]] — `backend/app\Http\Controllers\API\V1\VolunteerController.php`

### Service (12)

- [[docs/code/backend/AffectedPersonService]] — `backend/app\Services\AffectedPersonService.php`
- [[docs/code/backend/AnnouncementService]] — `backend/app\Services\AnnouncementService.php`
- [[docs/code/backend/AssistanceRequestService]] — `backend/app\Services\AssistanceRequestService.php`
- [[docs/code/backend/AuditLogService]] — `backend/app\Services\AuditLogService.php`
- [[docs/code/backend/DashboardService]] — `backend/app\Services\DashboardService.php`
- [[docs/code/backend/DisasterIncidentService]] — `backend/app\Services\DisasterIncidentService.php`
- [[docs/code/backend/EmergencyReportService]] — `backend/app\Services\EmergencyReportService.php`
- [[docs/code/backend/EvacuationCenterService]] — `backend/app\Services\EvacuationCenterService.php`
- [[docs/code/backend/ReliefItemService]] — `backend/app\Services\ReliefItemService.php`
- [[docs/code/backend/ResponderService]] — `backend/app\Services\ResponderService.php`
- [[docs/code/backend/VolunteerAssignmentService]] — `backend/app\Services\VolunteerAssignmentService.php`
- [[docs/code/backend/VolunteerService]] — `backend/app\Services\VolunteerService.php`

### Repository (12)

- [[docs/code/backend/AffectedPersonRepository]] — `backend/app\Repositories\AffectedPersonRepository.php`
- [[docs/code/backend/AnnouncementRepository]] — `backend/app\Repositories\AnnouncementRepository.php`
- [[docs/code/backend/AssistanceRequestRepository]] — `backend/app\Repositories\AssistanceRequestRepository.php`
- [[docs/code/backend/BaseRepository]] — `backend/app\Repositories\BaseRepository.php`
- [[docs/code/backend/DisasterIncidentRepository]] — `backend/app\Repositories\DisasterIncidentRepository.php`
- [[docs/code/backend/EmergencyReportRepository]] — `backend/app\Repositories\EmergencyReportRepository.php`
- [[docs/code/backend/EvacuationCenterRepository]] — `backend/app\Repositories\EvacuationCenterRepository.php`
- [[docs/code/backend/InventoryTransactionRepository]] — `backend/app\Repositories\InventoryTransactionRepository.php`
- [[docs/code/backend/ReliefItemRepository]] — `backend/app\Repositories\ReliefItemRepository.php`
- [[docs/code/backend/ResponderRepository]] — `backend/app\Repositories\ResponderRepository.php`
- [[docs/code/backend/VolunteerAssignmentRepository]] — `backend/app\Repositories\VolunteerAssignmentRepository.php`
- [[docs/code/backend/VolunteerRepository]] — `backend/app\Repositories\VolunteerRepository.php`

### Model (12)

- [[docs/code/backend/AffectedPerson]] — `backend/app\Models\AffectedPerson.php`
- [[docs/code/backend/Announcement]] — `backend/app\Models\Announcement.php`
- [[docs/code/backend/AssistanceRequest]] — `backend/app\Models\AssistanceRequest.php`
- [[docs/code/backend/DisasterIncident]] — `backend/app\Models\DisasterIncident.php`
- [[docs/code/backend/EmergencyReport]] — `backend/app\Models\EmergencyReport.php`
- [[docs/code/backend/EvacuationCenter]] — `backend/app\Models\EvacuationCenter.php`
- [[docs/code/backend/InventoryTransaction]] — `backend/app\Models\InventoryTransaction.php`
- [[docs/code/backend/ReliefItem]] — `backend/app\Models\ReliefItem.php`
- [[docs/code/backend/Responder]] — `backend/app\Models\Responder.php`
- [[docs/code/backend/User]] — `backend/app\Models\User.php`
- [[docs/code/backend/Volunteer]] — `backend/app\Models\Volunteer.php`
- [[docs/code/backend/VolunteerAssignment]] — `backend/app\Models\VolunteerAssignment.php`

### Policy (10)

- [[docs/code/backend/AffectedPersonPolicy]] — `backend/app\Policies\AffectedPersonPolicy.php`
- [[docs/code/backend/AnnouncementPolicy]] — `backend/app\Policies\AnnouncementPolicy.php`
- [[docs/code/backend/AssistanceRequestPolicy]] — `backend/app\Policies\AssistanceRequestPolicy.php`
- [[docs/code/backend/DisasterIncidentPolicy]] — `backend/app\Policies\DisasterIncidentPolicy.php`
- [[docs/code/backend/EmergencyReportPolicy]] — `backend/app\Policies\EmergencyReportPolicy.php`
- [[docs/code/backend/EvacuationCenterPolicy]] — `backend/app\Policies\EvacuationCenterPolicy.php`
- [[docs/code/backend/ReliefItemPolicy]] — `backend/app\Policies\ReliefItemPolicy.php`
- [[docs/code/backend/ResponderPolicy]] — `backend/app\Policies\ResponderPolicy.php`
- [[docs/code/backend/VolunteerAssignmentPolicy]] — `backend/app\Policies\VolunteerAssignmentPolicy.php`
- [[docs/code/backend/VolunteerPolicy]] — `backend/app\Policies\VolunteerPolicy.php`

### Request (39)

- [[docs/code/backend/AssignEvacuationCenterRequest]] — `backend/app\Http\Requests\API\V1\AssignEvacuationCenterRequest.php`
- [[docs/code/backend/AssignResponderRequest]] — `backend/app\Http\Requests\API\V1\AssignResponderRequest.php`
- [[docs/code/backend/ChangePasswordRequest]] — `backend/app\Http\Requests\API\V1\ChangePasswordRequest.php`
- [[docs/code/backend/DistributeRequest]] — `backend/app\Http\Requests\API\V1\DistributeRequest.php`
- [[docs/code/backend/ForgotPasswordRequest]] — `backend/app\Http\Requests\API\V1\ForgotPasswordRequest.php`
- [[docs/code/backend/LoginRequest]] — `backend/app\Http\Requests\API\V1\LoginRequest.php`
- [[docs/code/backend/RegisterRequest]] — `backend/app\Http\Requests\API\V1\RegisterRequest.php`
- [[docs/code/backend/RejectAssistanceRequestRequest]] — `backend/app\Http\Requests\API\V1\RejectAssistanceRequestRequest.php`
- [[docs/code/backend/ResetPasswordRequest]] — `backend/app\Http\Requests\API\V1\ResetPasswordRequest.php`
- [[docs/code/backend/StockInRequest]] — `backend/app\Http\Requests\API\V1\StockInRequest.php`
- [[docs/code/backend/StockOutRequest]] — `backend/app\Http\Requests\API\V1\StockOutRequest.php`
- [[docs/code/backend/StoreAffectedPersonRequest]] — `backend/app\Http\Requests\API\V1\StoreAffectedPersonRequest.php`
- [[docs/code/backend/StoreAnnouncementRequest]] — `backend/app\Http\Requests\API\V1\StoreAnnouncementRequest.php`
- [[docs/code/backend/StoreAssistanceRequestRequest]] — `backend/app\Http\Requests\API\V1\StoreAssistanceRequestRequest.php`
- [[docs/code/backend/StoreDisasterIncidentRequest]] — `backend/app\Http\Requests\API\V1\StoreDisasterIncidentRequest.php`
- [[docs/code/backend/StoreEmergencyReportRequest]] — `backend/app\Http\Requests\API\V1\StoreEmergencyReportRequest.php`
- [[docs/code/backend/StoreEvacuationCenterRequest]] — `backend/app\Http\Requests\API\V1\StoreEvacuationCenterRequest.php`
- [[docs/code/backend/StoreReliefItemRequest]] — `backend/app\Http\Requests\API\V1\StoreReliefItemRequest.php`
- [[docs/code/backend/StoreResponderRequest]] — `backend/app\Http\Requests\API\V1\StoreResponderRequest.php`
- [[docs/code/backend/StoreVolunteerAssignmentRequest]] — `backend/app\Http\Requests\API\V1\StoreVolunteerAssignmentRequest.php`
- [[docs/code/backend/StoreVolunteerRequest]] — `backend/app\Http\Requests\API\V1\StoreVolunteerRequest.php`
- [[docs/code/backend/UpdateAffectedPersonRequest]] — `backend/app\Http\Requests\API\V1\UpdateAffectedPersonRequest.php`
- [[docs/code/backend/UpdateAnnouncementRequest]] — `backend/app\Http\Requests\API\V1\UpdateAnnouncementRequest.php`
- [[docs/code/backend/UpdateAssistanceRequestRequest]] — `backend/app\Http\Requests\API\V1\UpdateAssistanceRequestRequest.php`
- [[docs/code/backend/UpdateCasualtiesRequest]] — `backend/app\Http\Requests\API\V1\UpdateCasualtiesRequest.php`
- [[docs/code/backend/UpdateConditionRequest]] — `backend/app\Http\Requests\API\V1\UpdateConditionRequest.php`
- [[docs/code/backend/UpdateDisasterIncidentRequest]] — `backend/app\Http\Requests\API\V1\UpdateDisasterIncidentRequest.php`
- [[docs/code/backend/UpdateDisasterStatusRequest]] — `backend/app\Http\Requests\API\V1\UpdateDisasterStatusRequest.php`
- [[docs/code/backend/UpdateEmergencyReportRequest]] — `backend/app\Http\Requests\API\V1\UpdateEmergencyReportRequest.php`
- [[docs/code/backend/UpdateEmergencyStatusRequest]] — `backend/app\Http\Requests\API\V1\UpdateEmergencyStatusRequest.php`
- [[docs/code/backend/UpdateEvacuationCenterRequest]] — `backend/app\Http\Requests\API\V1\UpdateEvacuationCenterRequest.php`
- [[docs/code/backend/UpdateOccupancyRequest]] — `backend/app\Http\Requests\API\V1\UpdateOccupancyRequest.php`
- [[docs/code/backend/UpdateProfileRequest]] — `backend/app\Http\Requests\API\V1\UpdateProfileRequest.php`
- [[docs/code/backend/UpdateReliefItemRequest]] — `backend/app\Http\Requests\API\V1\UpdateReliefItemRequest.php`
- [[docs/code/backend/UpdateResponderRequest]] — `backend/app\Http\Requests\API\V1\UpdateResponderRequest.php`
- [[docs/code/backend/UpdateResponderStatusRequest]] — `backend/app\Http\Requests\API\V1\UpdateResponderStatusRequest.php`
- [[docs/code/backend/UpdateVolunteerAssignmentRequest]] — `backend/app\Http\Requests\API\V1\UpdateVolunteerAssignmentRequest.php`
- [[docs/code/backend/UpdateVolunteerRequest]] — `backend/app\Http\Requests\API\V1\UpdateVolunteerRequest.php`
- [[docs/code/backend/UpdateVolunteerStatusRequest]] — `backend/app\Http\Requests\API\V1\UpdateVolunteerStatusRequest.php`

### Resource (28)

- [[docs/code/backend/AffectedPersonCollection]] — `backend/app\Http\Resources\API\V1\AffectedPersonCollection.php`
- [[docs/code/backend/AffectedPersonResource]] — `backend/app\Http\Resources\API\V1\AffectedPersonResource.php`
- [[docs/code/backend/AnnouncementCollection]] — `backend/app\Http\Resources\API\V1\AnnouncementCollection.php`
- [[docs/code/backend/AnnouncementResource]] — `backend/app\Http\Resources\API\V1\AnnouncementResource.php`
- [[docs/code/backend/AssistanceRequestCollection]] — `backend/app\Http\Resources\API\V1\AssistanceRequestCollection.php`
- [[docs/code/backend/AssistanceRequestResource]] — `backend/app\Http\Resources\API\V1\AssistanceRequestResource.php`
- [[docs/code/backend/AuditLogCollection]] — `backend/app\Http\Resources\API\V1\AuditLogCollection.php`
- [[docs/code/backend/AuditLogResource]] — `backend/app\Http\Resources\API\V1\AuditLogResource.php`
- [[docs/code/backend/DashboardChartsResource]] — `backend/app\Http\Resources\API\V1\DashboardChartsResource.php`
- [[docs/code/backend/DashboardMapResource]] — `backend/app\Http\Resources\API\V1\DashboardMapResource.php`
- [[docs/code/backend/DashboardStatsResource]] — `backend/app\Http\Resources\API\V1\DashboardStatsResource.php`
- [[docs/code/backend/DisasterIncidentCollection]] — `backend/app\Http\Resources\API\V1\DisasterIncidentCollection.php`
- [[docs/code/backend/DisasterIncidentResource]] — `backend/app\Http\Resources\API\V1\DisasterIncidentResource.php`
- [[docs/code/backend/EmergencyReportCollection]] — `backend/app\Http\Resources\API\V1\EmergencyReportCollection.php`
- [[docs/code/backend/EmergencyReportResource]] — `backend/app\Http\Resources\API\V1\EmergencyReportResource.php`
- [[docs/code/backend/EvacuationCenterCollection]] — `backend/app\Http\Resources\API\V1\EvacuationCenterCollection.php`
- [[docs/code/backend/EvacuationCenterResource]] — `backend/app\Http\Resources\API\V1\EvacuationCenterResource.php`
- [[docs/code/backend/InventoryTransactionCollection]] — `backend/app\Http\Resources\API\V1\InventoryTransactionCollection.php`
- [[docs/code/backend/InventoryTransactionResource]] — `backend/app\Http\Resources\API\V1\InventoryTransactionResource.php`
- [[docs/code/backend/ReliefItemCollection]] — `backend/app\Http\Resources\API\V1\ReliefItemCollection.php`
- [[docs/code/backend/ReliefItemResource]] — `backend/app\Http\Resources\API\V1\ReliefItemResource.php`
- [[docs/code/backend/ResponderCollection]] — `backend/app\Http\Resources\API\V1\ResponderCollection.php`
- [[docs/code/backend/ResponderResource]] — `backend/app\Http\Resources\API\V1\ResponderResource.php`
- [[docs/code/backend/UserResource]] — `backend/app\Http\Resources\API\V1\UserResource.php`
- [[docs/code/backend/VolunteerAssignmentCollection]] — `backend/app\Http\Resources\API\V1\VolunteerAssignmentCollection.php`
- [[docs/code/backend/VolunteerAssignmentResource]] — `backend/app\Http\Resources\API\V1\VolunteerAssignmentResource.php`
- [[docs/code/backend/VolunteerCollection]] — `backend/app\Http\Resources\API\V1\VolunteerCollection.php`
- [[docs/code/backend/VolunteerResource]] — `backend/app\Http\Resources\API\V1\VolunteerResource.php`

### Provider (1)

- [[docs/code/backend/AppServiceProvider]] — `backend/app\Providers\AppServiceProvider.php`

### Seeder (4)

- [[docs/code/backend/AdminUserSeeder]] — `backend/database/seeders\AdminUserSeeder.php`
- [[docs/code/backend/DatabaseSeeder]] — `backend/database/seeders\DatabaseSeeder.php`
- [[docs/code/backend/DemoUserSeeder]] — `backend/database/seeders\DemoUserSeeder.php`
- [[docs/code/backend/RolesAndPermissionsSeeder]] — `backend/database/seeders\RolesAndPermissionsSeeder.php`

### Factory (9)

- [[docs/code/backend/AffectedPersonFactory]] — `backend/database/factories\AffectedPersonFactory.php`
- [[docs/code/backend/AssistanceRequestFactory]] — `backend/database/factories\AssistanceRequestFactory.php`
- [[docs/code/backend/DisasterIncidentFactory]] — `backend/database/factories\DisasterIncidentFactory.php`
- [[docs/code/backend/EmergencyReportFactory]] — `backend/database/factories\EmergencyReportFactory.php`
- [[docs/code/backend/EvacuationCenterFactory]] — `backend/database/factories\EvacuationCenterFactory.php`
- [[docs/code/backend/ReliefItemFactory]] — `backend/database/factories\ReliefItemFactory.php`
- [[docs/code/backend/ResponderFactory]] — `backend/database/factories\ResponderFactory.php`
- [[docs/code/backend/UserFactory]] — `backend/database/factories\UserFactory.php`
- [[docs/code/backend/VolunteerFactory]] — `backend/database/factories\VolunteerFactory.php`
