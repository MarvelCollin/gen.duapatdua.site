<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainees', function (Blueprint $table) {
            $table->id();
            $table->string('trainee_number');
            $table->string('name');
            $table->string('degree');
            $table->string('status');
            $table->string('binusian');
            $table->string('profile');
            $table->integer('totalForum');
            $table->integer('totalAcq');
            $table->integer('totalCatering')->nullable();
            $table->string('bookCatering')->nullable();
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
        Schema::dropIfExists('trainees');
    }
};
