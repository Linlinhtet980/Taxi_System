<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            // အခြေခံ အချက်အလက်
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->unique()->nullable(); // နောက်မှ login နဲ့ ချိတ်ရင် သုံးလို့ရအောင်
            $table->string('phone_no')->unique();
            $table->string('emergency_contact_no')->nullable();

            // လုပ်ငန်းခွင်ဆိုင်ရာ
            $table->string('license_no')->unique();
            $table->string('vehicle_no')->nullable(); // ကားနံပါတ်
            $table->string('vehicle_type')->nullable(); // e.g., Car, Bike, Tuk Tuk

            $table->enum('driver_status', ['active', 'inactive', 'pending'])->default('pending');

            // Identity & Documents (File paths)
            $table->string('profile_picture')->nullable()->comment('Driver ဓာတ်ပုံ');
            $table->string('license_photo')->nullable()->comment('ယာဉ်မောင်းလိုင်စင် ဓာတ်ပုံ');
            $table->string('nric_photo')->nullable()->comment('မှတ်ပုံတင် ဓာတ်ပုံ');

            // တည်နေရာနှင့် မှတ်တမ်း
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('identity_card_no')->nullable(); // မှတ်ပုံတင်နံပါတ်

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
