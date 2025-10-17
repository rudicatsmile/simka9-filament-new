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
        Schema::create('data_riwayat_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 50)->comment('NIK pegawai dari tabel data_pegawai');
            $table->string('nama', 255)->nullable()->comment('Nama riwayat kepegawaian');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal lahir');
            $table->string('nomor', 255)->nullable()->comment('Nomor dokumen');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->string('berkas', 255)->nullable()->comment('Path file berkas');
            $table->integer('urut')->comment('Urutan data');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nik_data_pegawai', 'idx_riwayat_kepegawaian_nik');
            $table->index('urut', 'idx_riwayat_kepegawaian_urut');
            $table->index('deleted_at', 'idx_riwayat_kepegawaian_deleted');

            // Logical foreign key: nik_data_pegawai REFERENCES data_pegawai(nik)
            // Physical foreign key tidak digunakan untuk menghindari constraint issues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_kepegawaian');
    }
};
