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
        Schema::create('evaluation_stages', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->foreignId('stage_id')->constrained('stages')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_stages');
    }
};
