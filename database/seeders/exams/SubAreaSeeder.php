<?php

namespace Database\Seeders\Exams;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SubAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/exg/subareas.json')), true);

        DB::table('evaluation_criteria')->insert($data);

        $this->command->info('Subarea data seeded successfully.');
    }
}
