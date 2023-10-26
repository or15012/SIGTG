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
        Schema::create('criteria_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_criteria_id')->constrained('evaluation_criteria')->onDelete('restrict');
            $table->foreignId('evaluation_stage_id')->constrained('evaluation_stages')->onDelete('restrict');
            $table->decimal("note",8,2,true);
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
        Schema::dropIfExists('evaluation_criteria_evaluation_stage');
    }
};
