<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/exg/phases.json')), true);

        DB::table('phases')->insert($data);

        $this->command->info('Phases data seeded successfully.');
    }
}
