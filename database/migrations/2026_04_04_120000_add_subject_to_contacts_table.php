<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('contacts', 'subject')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('subject')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('contacts', 'subject')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }
    }
};
