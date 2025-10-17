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
        Schema::create('data_riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 50)->comment('NIK pegawai dari tabel data_pegawai');
            $table->string('kode_jenjang_pendidikan', 50)->comment('Kode jenjang pendidikan dari tabel jenjang_pendidikan');
            $table->string('nama_sekolah', 255)->comment('Nama sekolah/institusi pendidikan');
            $table->string('tahun_ijazah', 20)->comment('Tahun kelulusan/ijazah');
            $table->integer('urut')->comment('Urutan prioritas pendidikan');
            $table->timestamps();

            // Create indexes for better performance
            $table->index('nik_data_pegawai', 'idx_riwayat_pendidikan_nik');
            $table->index('kode_jenjang_pendidikan', 'idx_riwayat_pendidikan_jenjang');
            $table->index('tahun_ijazah', 'idx_riwayat_pendidikan_tahun');
            $table->index('urut', 'idx_riwayat_pendidikan_urut');
            
            // Note: Physical foreign keys are avoided as per project guidelines
            // Logical foreign key: nik_data_pegawai REFERENCES data_pegawai(nik)
            // Logical foreign key: kode_jenjang_pendidikan REFERENCES jenjang_pendidikan(kode)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_pendidikan');
    }
};
