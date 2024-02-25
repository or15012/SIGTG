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
        Schema::create('subarea_criterias', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->mediumText("description")->nullable()->default(null);
            $table->integer("percentage");
            $table->foreignId('stage_id')->constrained('stages')->onDelete('restrict');
            $table->boolean("type")->default(0);
            $table->timestamps();
            $table->softDeletes();

            //Restricción de unicidad
            $table->unique(['name', 'stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subarea_criterias');
    }
};
