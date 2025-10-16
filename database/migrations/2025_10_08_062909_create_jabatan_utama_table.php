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
        Schema::create('jabatan_utama', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('kode_unit_kerja');
            $table->string('nama_jabatan_utama');
            $table->enum('status', ['1', '0'])->default('1');
            $table->integer('urut');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('kode_unit_kerja')->references('kode')->on('unit_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_utama');
    }
};
