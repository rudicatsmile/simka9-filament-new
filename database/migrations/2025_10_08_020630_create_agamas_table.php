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
        Schema::create('agamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama_agama');
            $table->enum('status', ['1', '0'])->default('1');
            $table->integer('urut');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agamas');
    }
};
