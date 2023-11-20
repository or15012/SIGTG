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
        Schema::table('extensions', function (Blueprint $table) {
            $table->string('extension_request_path')->nullable();
            $table->string('schedule_activities_path')->nullable();
            $table->string('approval_letter_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extensions', function (Blueprint $table) {
            $table->dropColumn('extension_request_path');
            $table->dropColumn('schedule_activities_path');
            $table->dropColumn('approval_letter_path');
        });
    }
};
