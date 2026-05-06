<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'point_earning_ratio_cash',
                'value' => '1',
                'label' => 'Points per 1,000 MMK (Cash)',
                'group' => 'loyalty',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'point_earning_ratio_digital',
                'value' => '2',
                'label' => 'Points per 1,000 MMK (Digital)',
                'group' => 'loyalty',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'point_earning_ratio')->delete();
    }
};
