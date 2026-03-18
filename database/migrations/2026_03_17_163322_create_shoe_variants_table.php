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
       Schema::create('shoe_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shoe_id')->nullable()->constrained('shoes')->cascadeOnDelete();
            $table->string('color', 30);
            $table->integer('size');
            $table->integer('stock_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoe_variants');
    }
};
