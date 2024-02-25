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
        Schema::create('criteria_subareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subarea_criteria_id')->constrained('subarea_criterias')->onDelete('restrict');
            // $table->foreignId('evaluation_subareas_id')->constrained('evaluation_subareas')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
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
        Schema::dropIfExists('criteria_subareas');
    }
};
