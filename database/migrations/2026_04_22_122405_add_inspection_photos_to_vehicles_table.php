<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('front_photo')->nullable()->after('vehicle_photo');
            $table->string('back_photo')->nullable()->after('front_photo');
            $table->string('left_side_photo')->nullable()->after('back_photo');
            $table->string('right_side_photo')->nullable()->after('left_side_photo');
            $table->string('interior_photo')->nullable()->after('right_side_photo');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'front_photo',
                'back_photo',
                'left_side_photo',
                'right_side_photo',
                'interior_photo'
            ]);
        });
    }
};
