<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom UUID untuk relasi ke cwsps_roles
            $table->uuid('role_id')->nullable()->after('id');

            // Foreign key ke cwsps_roles.id (UUID)
            $table->foreign('role_id')
                ->references('id')
                ->on('cwsps_roles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};