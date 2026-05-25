<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->string('location')->nullable()->after('ip_address');
            $table->string('device_name')->nullable()->after('user_agent');
        });
    }
    
    public function down()
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->dropColumn(['location', 'device_name']);
        });
    }
};