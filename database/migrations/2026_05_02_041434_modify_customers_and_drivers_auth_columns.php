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
        // Update customers table
        Schema::table('customers', function (Blueprint $table) {
            // Make phone nullable for Google Social Login
            $table->string('phone')->nullable()->change();
        });

        // Update drivers table
        Schema::table('drivers', function (Blueprint $table) {
            // Add password column (was missing)
            $table->string('password')->after('email');
            
            // Make license_no nullable for step 1 of registration
            $table->string('license_no')->nullable()->change();
            
            // Add remember_token for persistent login sessions
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Revert phone to non-nullable (may fail if null data exists)
            $table->string('phone')->nullable(false)->change();
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
            $table->string('license_no')->nullable(false)->change();
        });
    }
};
