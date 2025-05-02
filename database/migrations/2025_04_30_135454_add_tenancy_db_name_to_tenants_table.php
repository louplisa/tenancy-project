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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('tenancy_db_name')->nullable();
            $table->string('tenancy_db_username')->nullable(); // Nom d'utilisateur de la base de données
            $table->string('tenancy_db_password')->nullable(); // Mot de passe de la base de données
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['tenancy_db_name', 'tenancy_db_username', 'tenancy_db_password']);
        });
    }
};
