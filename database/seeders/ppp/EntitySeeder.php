<?php

namespace Database\Seeders\Ppp;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/ppp/entities.json')), true);

        DB::table('entities')->insert($data);

        $this->command->info('Entities data seeded successfully.');
    }
}
