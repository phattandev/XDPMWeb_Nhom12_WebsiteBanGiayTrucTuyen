<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('shoe_images', function (Blueprint $table) {
            // Thêm cột public_id nằm ngay sau cột image_url
            $table->string('public_id')->nullable()->after('image_url'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shoe_images', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};
