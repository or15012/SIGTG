<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class EvaluationCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/cde/ev_criteria.json')), true);

        DB::table('evaluation_criteria')->insert($data);

        $this->command->info('Stages data seeded successfully.');
    }
}
