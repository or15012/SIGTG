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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("status");
            $table->string("path");
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();

            // Restricción única para evitar que un usuario aplique múltiples veces a la misma propuesta
            $table->unique(['user_id', 'proposal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
