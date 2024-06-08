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
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->foreignId('trainee_id')->nullable()->constrained('trainees')->onDelete('cascade');
            $table->string("forum_status"); // yes, no, unshuffle
            // yes -> the trainee has answered
            // no -> has trainee, but not answer
            // unshuffle -> not yet shuffle
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
        Schema::dropIfExists('forums');
    }
};
