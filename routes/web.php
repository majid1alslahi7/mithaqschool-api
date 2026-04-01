<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Root route
Route::get('/', function () {
    return response()->json([
        'message' => 'MithaqSchool API only. Use /api/v1.',
    ]);
});

// ---------- DEBUG ROUTE (Temporary) ----------
Route::get('/debug-db', function () {
    try {
        DB::connection()->getPdo();
        return '✅ DB OK';
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
        ];
    }
});
