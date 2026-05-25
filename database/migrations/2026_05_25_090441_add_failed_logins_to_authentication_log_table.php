<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->timestamp('login_failed_at')->nullable();
            $table->string('failed_reason')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('authentication_log', function (Blueprint $table) {
            $table->dropColumn(['login_failed_at', 'failed_reason']);
        });
    }
};