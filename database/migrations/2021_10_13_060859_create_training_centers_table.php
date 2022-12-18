<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_centers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('region_id');
            $table->integer('district_id');
            $table->string('name');
            $table->string('user_name')->nullable();
            $table->string('phone');
            $table->integer('parol')->nullable();
            $table->string('address');
            $table->text('comment')->nullable();
            $table->integer('monthly_payment_min');
            $table->integer('monthly_payment_max');
            $table->string('main_image')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('status')->default('waiting');
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
        Schema::dropIfExists('training_centers');
    }
}
