<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/exg/criterias.json')), true);

        DB::table('subarea_criterias')->insert($data);

        $this->command->info('Criteria data seeded successfully.');
    }
}
