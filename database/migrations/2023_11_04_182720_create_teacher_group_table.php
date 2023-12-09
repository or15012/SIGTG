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
        Schema::create('teacher_group', function (Blueprint $table) {
            $table->id();
            $table->integer('status'); // Agregando la columna 'status' de tipo boolean
            $table->boolean("type")->default(0);
            $table->string("path_agreement")->nullable()->default(null);
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('group_id')->constrained('groups')->onDelete('restrict');
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
        Schema::dropIfExists('teacher_group');
    }
};
