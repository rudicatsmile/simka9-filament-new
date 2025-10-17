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
use App\Http\Controllers\RiwayatPendidikanController;
use App\Http\Controllers\DataRiwayatPasanganController;

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

// Riwayat Pendidikan API Routes
Route::apiResource('riwayat-pendidikan', RiwayatPendidikanController::class);
Route::get('riwayat-pendidikan/employee/{nik}', [RiwayatPendidikanController::class, 'getByEmployee']);

// Data Riwayat Pasangan API Routes
Route::apiResource('data-riwayat-pasangan', DataRiwayatPasanganController::class);
Route::get('data-riwayat-pasangan/employee/{nik}', [DataRiwayatPasanganController::class, 'getByEmployee']);

// Data Riwayat Kepegawaian API Routes
Route::apiResource('data-riwayat-kepegawaian', \App\Http\Controllers\Api\DataRiwayatKepegawaianController::class);
Route::get('data-riwayat-kepegawaian/{dataRiwayatKepegawaian}/download-berkas', [\App\Http\Controllers\Api\DataRiwayatKepegawaianController::class, 'downloadBerkas']);
