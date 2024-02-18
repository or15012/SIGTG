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
        Schema::create('evaluation_criteria_subarea_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_criteria_id')->constrained('evaluation_criteria')->onDelete('restrict');
            $table->foreignId('subarea_criteria_id')->constrained('subarea_criterias')->onDelete('restrict');
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
        Schema::dropIfExists('evaluation_criteria_subarea_criteria');
    }
};
