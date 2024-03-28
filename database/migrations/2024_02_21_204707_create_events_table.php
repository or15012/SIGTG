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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->mediumText("description")->nullable()->default(null);
            $table->string("place");

            $table->dateTime('start');
            $table->dateTime('end');

            $table->tinyInteger('status'); // 0 presentada, 1 aceptada, 2 rechazada
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('group_id')->constrained('groups')->onDelete('restrict');
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('restrict');
            $table->foreignId('school_id')->constrained('schools')->onDelete('restrict');
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
        Schema::dropIfExists('events');
    }
};
