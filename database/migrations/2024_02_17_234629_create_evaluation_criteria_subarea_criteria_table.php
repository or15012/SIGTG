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
        Schema::create('evaluation_crit_subarea_crit', function (Blueprint $table) {
            $table->id();
            // Campos de la tabla
            $table->foreignId('evaluation_criteria_id');
            $table->foreignId('subarea_criteria_id');

            // Restricciones de clave externa
            $table->foreign('evaluation_criteria_id', 'eval_crit_for')
                ->references('id')
                ->on('evaluation_criteria')
                ->onDelete('restrict');

            $table->foreign('subarea_criteria_id', 'sub_crit_for')
                ->references('id')
                ->on('subarea_criterias')
                ->onDelete('restrict');
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
