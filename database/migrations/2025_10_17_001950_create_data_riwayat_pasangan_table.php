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
        Schema::create('data_riwayat_pasangan', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 50)->comment('NIK pegawai dari tabel data_pegawai');
            $table->string('nama_pasangan', 255)->nullable()->comment('Nama lengkap pasangan');
            $table->string('tempat_lahir', 100)->nullable()->comment('Tempat lahir pasangan');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal lahir pasangan');
            $table->enum('hubungan', ['Suami', 'Istri'])->comment('Hubungan dengan pegawai');
            $table->string('kode_jenjang_pendidikan', 50)->nullable()->comment('Kode jenjang pendidikan dari tabel jenjang_pendidikan');
            $table->string('kode_tabel_pekerjaan', 50)->nullable()->comment('Kode pekerjaan dari tabel tabel_pekerjaan');
            $table->integer('urut')->comment('Urutan prioritas data pasangan');
            $table->timestamps();

            // Create indexes for better performance
            $table->index('nik_data_pegawai', 'idx_riwayat_pasangan_nik');
            $table->index('hubungan', 'idx_riwayat_pasangan_hubungan');
            $table->index('kode_jenjang_pendidikan', 'idx_riwayat_pasangan_jenjang');
            $table->index('kode_tabel_pekerjaan', 'idx_riwayat_pasangan_pekerjaan');
            $table->index('urut', 'idx_riwayat_pasangan_urut');
            $table->index('tanggal_lahir', 'idx_riwayat_pasangan_tgl_lahir');
            
            // Note: Physical foreign keys are avoided as per project guidelines
            // Logical foreign key: nik_data_pegawai REFERENCES data_pegawai(nik)
            // Logical foreign key: kode_jenjang_pendidikan REFERENCES jenjang_pendidikan(kode)
            // Logical foreign key: kode_tabel_pekerjaan REFERENCES tabel_pekerjaan(kode)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_pasangan');
    }
};
