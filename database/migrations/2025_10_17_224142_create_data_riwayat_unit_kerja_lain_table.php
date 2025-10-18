<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_riwayat_unit_kerja_lain', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 255);
            $table->string('nama_unit_kerja', 255);
            $table->text('alamat_unit_kerja')->nullable();
            $table->string('jabatan', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_bekerja_di_tempat_lain')->default(false);
            $table->enum('status', ['aktif', 'tidak_aktif', 'selesai'])->default('aktif');
            $table->timestamps();

            // Performance indexes
            $table->index('nik_data_pegawai', 'idx_data_riwayat_unit_kerja_lain_pegawai');
            $table->index('status', 'idx_data_riwayat_unit_kerja_lain_status');
            $table->index('tanggal_mulai', 'idx_data_riwayat_unit_kerja_lain_tanggal_mulai');
            
            // Note: Using logical foreign key relationship instead of physical constraint
            // to avoid issues with nullable nik column in data_pegawai table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_unit_kerja_lain');
    }
};
