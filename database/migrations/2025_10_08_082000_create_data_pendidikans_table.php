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
        Schema::create('data_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('nip_pegawai', 20);
            $table->string('jenjang_pendidikan', 50);
            $table->string('nama_sekolah', 150);
            $table->string('jurusan', 100)->nullable();
            $table->year('tahun_lulus');
            $table->decimal('nilai_ijazah', 5, 2)->nullable();
            $table->string('nomor_ijazah', 100)->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->integer('urut')->default(0);
            $table->timestamps();

            $table->foreign('nip_pegawai')->references('nip')->on('data_pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pendidikans');
    }
};
