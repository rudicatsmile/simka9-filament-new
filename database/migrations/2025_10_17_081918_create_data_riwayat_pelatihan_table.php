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
        Schema::create('data_riwayat_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 50)->comment('NIK pegawai dari tabel data_pegawai');
            $table->string('nama', 255)->nullable()->comment('Nama pelatihan');
            $table->string('kode_tabel_jenis_pelatihan', 10)->comment('Kode jenis pelatihan dari tabel tabel_jenis_pelatihan');
            $table->string('penyelenggara', 255)->nullable()->comment('Penyelenggara pelatihan');
            $table->string('angkatan', 255)->nullable()->comment('Angkatan pelatihan');
            $table->string('nomor', 255)->nullable()->comment('Nomor sertifikat');
            $table->date('tanggal')->nullable()->comment('Tanggal pelaksanaan pelatihan');
            $table->date('tanggal_sertifikat')->nullable()->comment('Tanggal penerbitan sertifikat');
            $table->string('berkas', 255)->nullable()->comment('Path file sertifikat');
            $table->integer('urut')->comment('Urutan prioritas pelatihan');
            $table->timestamps();

            // Create indexes for better performance
            $table->index('nik_data_pegawai', 'idx_pelatihan_nik');
            $table->index('kode_tabel_jenis_pelatihan', 'idx_pelatihan_jenis');
            $table->index('tanggal', 'idx_pelatihan_tanggal');
            $table->index('urut', 'idx_pelatihan_urut');
            
            // Note: Physical foreign keys are avoided as per project guidelines
            // Logical foreign key: nik_data_pegawai REFERENCES data_pegawai(nik)
            // Logical foreign key: kode_tabel_jenis_pelatihan REFERENCES tabel_jenis_pelatihan(kode)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_pelatihan');
    }
};
