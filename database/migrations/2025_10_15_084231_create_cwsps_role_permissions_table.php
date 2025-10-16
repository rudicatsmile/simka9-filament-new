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
        Schema::create('cwsps_role_permissions', function (Blueprint $table) {
            $table->uuid('role_id');
            $table->uuid('permission_id');
            $table->foreign('role_id')->references('id')->on('cwsps_roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('cwsps_permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cwsps_role_permissions');
    }
};