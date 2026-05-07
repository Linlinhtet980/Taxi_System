<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('user_id')->nullable()->after('id');
            $blueprint->string('user_type')->nullable()->after('user_id'); // Admin, Customer, Driver
            $blueprint->index(['user_id', 'user_type']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['user_id', 'user_type']);
        });
    }
};
