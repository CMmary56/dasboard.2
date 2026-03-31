<?php

use App\Http\Controllers\CuentaController;
use Illuminate\Support\Facades\Route;

// Ruta temporal sin autenticación para probar primero.
// Una vez que valides el CRUD, usa Route::middleware('auth:sanctum')->group(...)
Route::apiResource('cuentas', CuentaController::class);