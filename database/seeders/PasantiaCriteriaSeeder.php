<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PasantiaCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/ppp/criterias.json')), true);

        DB::table('evaluation_criteria')->insert($data);

        $data = json_decode(File::get(database_path('datos/pdi/criterias.json')), true);

        DB::table('evaluation_criteria')->insert($data);

        $this->command->info('Criteria data seeded successfully.');
    }
}
