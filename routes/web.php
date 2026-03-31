<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'MithaqSchool API only. Use /api/v1.',
    ]);
});
