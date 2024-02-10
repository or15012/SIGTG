<?php

namespace Database\Seeders\Exams;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/exg/areas.json')), true);

        DB::table('stages')->insert($data);

        $this->command->info('Area data seeded successfully.');
    }
}
