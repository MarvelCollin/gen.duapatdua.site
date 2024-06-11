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
        Schema::create('bpproject_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpproject_id')->constrained('bpprojects')->onDelete('cascade');
            $table->string('subtitle');
            $table->string('percentage');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->longText('notes');
            $table->string('external_trainee')->nullable();
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
        Schema::dropIfExists('bpproject_teams');
    }
};
