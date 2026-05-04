<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->decimal('wallet_balance', 15, 2)->default(0)->after('driver_status');
        });
    }


    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
        });
    }
};
