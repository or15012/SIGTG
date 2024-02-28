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
        Schema::table('evaluation_subareas', function (Blueprint $table) {
            $table->integer('status')->default(0); // Cambia el tipo de dato según tu necesidad

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation_subareas', function (Blueprint $table) {
            $table->dropColumn('status');

        });
    }
};
