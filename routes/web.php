<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataRiwayatPelatihanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/data-riwayat-pelatihans/{id}/download', [DataRiwayatPelatihanController::class, 'downloadCertificate'])
    ->name('admin.data-riwayat-pelatihans.download');
