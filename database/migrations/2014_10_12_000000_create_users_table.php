<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('second_last_name');
            $table->string('carnet', 7);
            $table->boolean('state');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('users')
            ->insert(
                array(
                    'first_name' => 'admin',
                    'middle_name' => 'admin',
                    'last_name' => 'admin',
                    'second_last_name' => 'admin',
                    'carnet' => 'OR15012',
                    'state' => 1,
                    'email' => 'admin@themesbrand.com',
                    'avatar' => '',
                    'password' => Hash::make('123456')
                )
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
