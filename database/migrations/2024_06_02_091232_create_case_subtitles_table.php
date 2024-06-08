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
        Schema::create('case_subtitles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_solve_detail_id')->constrained('case_solve_details')->onDelete('cascade');
            $table->string('subtitle');
            $table->string('percentage');
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
        Schema::dropIfExists('case_subtitles');
    }
};
