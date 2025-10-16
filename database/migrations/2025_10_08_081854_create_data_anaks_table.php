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
        Schema::create('data_anaks', function (Blueprint $table) {
            $table->id();
            $table->string('nip_pegawai', 20);
            $table->string('nama_anak', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('status_anak', ['kandung', 'tiri', 'angkat']);
            $table->string('pendidikan_terakhir', 100)->nullable();
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
        Schema::dropIfExists('data_anaks');
    }
};
