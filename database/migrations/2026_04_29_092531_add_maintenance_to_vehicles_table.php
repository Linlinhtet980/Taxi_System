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
        Schema::table('vehicles', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->date('last_maintenance_at')->nullable();
            $table->date('next_maintenance_at')->nullable();
            $table->string('inspection_status')->default('Good'); // Good, Warning, Critical
            $table->integer('mileage')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn(['last_maintenance_at', 'next_maintenance_at', 'inspection_status', 'mileage']);
        });
    }
};
