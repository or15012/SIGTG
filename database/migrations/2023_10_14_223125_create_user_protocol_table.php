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
        Schema::create('user_protocol', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true); // Agregando la columna 'status' de tipo boolean
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('restrict');
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
        Schema::dropIfExists('user_protocol');
    }
};
