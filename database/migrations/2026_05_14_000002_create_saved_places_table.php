<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_places', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('title'); // e.g. Home, Office, Gym
            $table->string('address');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();

            // We use simple indexing instead of strict foreign keys to maximize test environment database compatibility
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_places');
    }
};
