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
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean("visible")->default(1);
            $table->foreignId('protocol_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('restrict');
            $table->foreignId('school_id')->constrained('schools')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();


            //Restricción de unicidad
            $table->unique(['name','protocol_id','cycle_id', 'school_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stages');
    }
};
