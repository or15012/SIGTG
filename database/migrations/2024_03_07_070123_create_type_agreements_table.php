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
        Schema::create('type_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('affect'); // 1 estudiante, 2 grupo, 3 protocolo escuela, 4 escuela;
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
        Schema::dropIfExists('type_agreements');
    }
};
