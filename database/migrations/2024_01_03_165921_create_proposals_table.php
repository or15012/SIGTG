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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->mediumText("description")->nullable()->default(null);
            $table->string("path")->nullable()->default(null);
            $table->integer("amount_student");
            $table->foreignId('entity_id')->nullable()->constrained('entities')->onDelete('restrict');
            $table->boolean('status');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();

            //Restricción de unicidad
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal');
    }
};
