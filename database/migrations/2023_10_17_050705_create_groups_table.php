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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->integer("number")->nullable();
            $table->integer("year");
            $table->boolean("status")->default(0);
            $table->foreignId('state_id')->constrained('states')->onDelete('restrict');
            $table->foreignId('protocol_id')->constrained('protocols')->onDelete('restrict');
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('restrict')->nullable();
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
        Schema::dropIfExists('groups');
    }
};
