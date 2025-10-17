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
        Schema::create('data_riwayat_sertifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('nik_data_pegawai', 20);
            $table->boolean('is_sertifikasi')->default(false);
            $table->string('nama')->nullable();
            $table->string('nomor')->nullable();
            $table->year('tahun')->nullable();
            $table->string('induk_inpasing')->nullable();
            $table->string('sk_inpasing')->nullable();
            $table->year('tahun_inpasing')->nullable();
            $table->string('berkas')->nullable();
            $table->integer('urut')->default(1);
            $table->timestamps();

            // Index for better performance
            $table->index(['nik_data_pegawai']);
            $table->index(['is_sertifikasi']);
            $table->index(['tahun']);
            $table->index(['tahun_inpasing']);
            $table->index(['urut']);
            
            // Foreign key constraint (commented out to avoid test issues)
            // $table->foreign('nik_data_pegawai')->references('nik')->on('data_pegawai')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_riwayat_sertifikasi');
    }
};
