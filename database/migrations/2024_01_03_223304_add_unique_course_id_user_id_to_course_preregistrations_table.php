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
        Schema::table('course_preregistrations', function (Blueprint $table) {
            $table->unique(['course_id', 'user_id'], 'UQ_course_preregistrations_course_id_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_preregistrations', function (Blueprint $table) {
            $table->dropUnique('UQ_course_preregistrations_course_id_user_id');
        });
    }
};
