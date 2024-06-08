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
        Schema::create('case_solve_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_solve_id')->constrained('case_solves')->onDelete('cascade');
            $table->foreignId('trainee_id')->constrained('trainees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Revesdas
     *asassadasdads
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_solve_details');
    }
};
