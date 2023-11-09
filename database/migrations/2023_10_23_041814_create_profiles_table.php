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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->longText("description");
            $table->boolean("type");
            $table->string("path");
            $table->string("vision_path");
            $table->string("summary_path");
            $table->string("size_calculation_path");
            $table->integer('proposal_priority');
            $table->integer("status");
            $table->foreignId('group_id')->constrained('groups')->onDelete('restrict');
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
        Schema::dropIfExists('profiles');
    }
};
