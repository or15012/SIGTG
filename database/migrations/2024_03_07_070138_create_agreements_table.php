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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('approval_date');
            $table->date('description')->nullable();
            $table->foreignId('type_agreement_id')->constrained('type_agreements')->onDelete('restrict');
            $table->unsignedBigInteger('user_load_id')->nullable();
            $table->foreign('user_load_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('protocol_id')->nullable()->constrained('protocols')->onDelete('restrict');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('restrict');
            $table->softDeletes();
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
        Schema::dropIfExists('agreements');
    }
};
