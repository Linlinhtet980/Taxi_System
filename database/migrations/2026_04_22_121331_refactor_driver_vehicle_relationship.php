<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add driver_id to vehicles table and make fields nullable
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->string('brand')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('license_plate')->nullable()->change();
        });

        // 2. Migrate existing data from drivers to vehicles
        $drivers = DB::table('drivers')->get();
        foreach ($drivers as $driver) {
            if (!empty($driver->vehicle_no) || !empty($driver->vehicle_type)) {
                DB::table('vehicles')->insert([
                    'driver_id' => $driver->id,
                    'license_plate' => $driver->vehicle_no,
                    'vehicle_type' => $driver->vehicle_type,
                    'brand' => null,
                    'model' => null,
                    'status' => 'Available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Drop columns from drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['vehicle_no', 'vehicle_type']);
        });
    }

    public function down(): void
    {
        // Reverse process
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('vehicle_no')->nullable();
            $table->string('vehicle_type')->nullable();
        });

        $vehicles = DB::table('vehicles')->whereNotNull('driver_id')->get();
        foreach ($vehicles as $vehicle) {
            DB::table('drivers')
                ->where('id', $vehicle->driver_id)
                ->update([
                    'vehicle_no' => $vehicle->license_plate,
                    'vehicle_type' => $vehicle->vehicle_type,
                ]);
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
