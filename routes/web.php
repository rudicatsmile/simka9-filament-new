<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataRiwayatPelatihanController;
use App\Http\Controllers\Api\DataRiwayatSertifikasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/data-riwayat-pelatihans/{id}/download', [DataRiwayatPelatihanController::class, 'downloadCertificate'])
    ->name('admin.data-riwayat-pelatihans.download');

Route::get('/admin/data-riwayat-sertifikasis/{dataRiwayatSertifikasi}/download', [DataRiwayatSertifikasiController::class, 'downloadBerkas'])
    ->name('admin.data-riwayat-sertifikasis.download');
