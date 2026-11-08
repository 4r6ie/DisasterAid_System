<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'DisasterAid System API',
        'data' => [
            'version' => '1.0.0',
            'documentation' => url('/api/documentation'),
        ],
    ]);
});
