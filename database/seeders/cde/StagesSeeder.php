<?php

namespace Database\Seeders\Cde;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class StagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/cde/stages.json')), true);

        DB::table('stages')->insert($data);

        $this->command->info('Stages data seeded successfully.');
    }
}
