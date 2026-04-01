<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'ok' => true,
        'message' => 'API de portal-cuentas operativa'
    ]);
});
