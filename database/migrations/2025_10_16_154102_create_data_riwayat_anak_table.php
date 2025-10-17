<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating data_riwayat_anak table
 * 
 * This migration creates the table for storing employee children data
 * with proper relationships to other tables in the system.
 * 
 * @author Laravel Filament
 * @version 1.0.0
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('data_riwayat_anak', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 50)->comment('NIK pegawai dari tabel data_pegawai');
            $table->string('nama_anak', 255)->nullable()->comment('Nama lengkap anak');
            $table->string('tempat_lahir', 20)->nullable()->comment('Tempat lahir anak');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal lahir anak');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->comment('Jenis kelamin: L=Laki-laki, P=Perempuan');
            $table->string('kode_tabel_hubungan_keluarga', 10)->nullable()->comment('Kode hubungan keluarga');
            $table->string('kode_jenjang_pendidikan', 10)->nullable()->comment('Kode jenjang pendidikan anak');
            $table->string('kode_tabel_pekerjaan', 10)->nullable()->comment('Kode pekerjaan anak');
            $table->integer('urut')->comment('Urutan anak dalam keluarga');
            $table->timestamps();

            // Indexes for better performance
            $table->index('nik_data_pegawai', 'idx_data_riwayat_anak_nik_pegawai');
            $table->index('nama_anak', 'idx_data_riwayat_anak_nama');
            $table->index('tanggal_lahir', 'idx_data_riwayat_anak_tgl_lahir');
            $table->index('jenis_kelamin', 'idx_data_riwayat_anak_gender');
            $table->index('urut', 'idx_data_riwayat_anak_urut');

            // Foreign key constraints - Note: Using nik field which has unique constraint
            // We'll create the constraint manually after ensuring data integrity
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_anak');
    }
};
