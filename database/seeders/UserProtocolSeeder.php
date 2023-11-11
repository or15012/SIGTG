<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class UserProtocolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/user_protocol.json')), true);

        DB::table('user_protocol')->insert($data);

        $this->command->info('User Protocol data seeded successfully.');

    }
}
