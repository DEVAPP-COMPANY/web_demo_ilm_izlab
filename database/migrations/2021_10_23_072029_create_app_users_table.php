<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('step')->default(0);
            $table->string('role')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('sms_code')->nullable();
            $table->integer('counter')->nullable();
            $table->string('sms_time')->nullable();
            $table->string('password')->nullable();
            $table->string('token')->nullable();
            $table->string('status')->nullable();
            $table->integer('bot_reg')->default(0);
            $table->string('fcm_token')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_users');
    }
}
