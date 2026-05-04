<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $row) {
            $row->id();
            $row->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $row->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $row->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            
            $row->decimal('amount', 12, 2);
            $row->decimal('commission_amount', 12, 2)->default(0);
            $row->decimal('driver_amount', 12, 2)->default(0);
            
            $row->enum('payment_method', ['Cash', 'Digital'])->default('Cash');
            $row->enum('type', ['Ride Fare', 'Withdrawal', 'Recharge', 'Adjustment'])->default('Ride Fare');
            $row->enum('status', ['Completed', 'Pending', 'Failed'])->default('Completed');
            
            $row->string('reference_number')->nullable();
            $row->text('note')->nullable();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
