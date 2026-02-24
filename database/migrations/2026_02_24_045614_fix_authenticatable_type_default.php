<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            // Make sure the column exists
            $table->string('authenticatable_type')->default('App\\Models\\User')->change();
        });
    }

    public function down(): void
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->string('authenticatable_type')->change();
        });
    }
};