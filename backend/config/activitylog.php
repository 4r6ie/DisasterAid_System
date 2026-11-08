<?php

return [
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),
    'default_log_name' => 'default',
    'default_auth_driver' => null,
    'subject_returns_soft_deleted_models' => false,
    'activity_model' => Spatie\Activitylog\Models\Activity::class,
    'table_name' => env('ACTIVITY_LOG_TABLE_NAME', 'audit_logs'),
    'ignore_changed_attributes' => ['updated_at', 'remember_token'],
    'log_options' => [
        'use_oldest' => false,
    ],
    'record_events' => ['created', 'updated', 'deleted'],
    'logging' => [
        'log_name' => 'audit',
        'only_logs_changes' => false,
    ],
];