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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('transaction_id', 100)->nullable();
            $table->integer('amount');
            $table->enum('payment_method', ['COD', 'Bank Transfer', 'VNPay', 'Momo'])->default('COD');
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed', 'Refunded'])->default('Pending');
            $table->timestamp('payment_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
