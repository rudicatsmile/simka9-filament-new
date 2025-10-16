<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\HubunganKeluargaController;
use App\Http\Controllers\PropinsiController;
use App\Http\Controllers\GolonganDarahController;
use App\Http\Controllers\StatusNikahController;
use App\Http\Controllers\StatusKepegawaianController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Pekerjaan API Routes
Route::apiResource('pekerjaan', PekerjaanController::class);

// Hubungan Keluarga API Routes
Route::apiResource('hubungan-keluarga', HubunganKeluargaController::class);

// Propinsi API Routes
Route::apiResource('propinsi', PropinsiController::class);

// Golongan Darah API Routes
Route::apiResource('golongan-darah', GolonganDarahController::class);

// Status Nikah API Routes
Route::apiResource('status-nikah', StatusNikahController::class);

// Status Kepegawaian API Routes
Route::apiResource('status-kepegawaian', StatusKepegawaianController::class);

// Users API Routes
Route::apiResource('users', UserController::class);
