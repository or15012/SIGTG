<?php

namespace Database\Seeders\Ppp;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class EntityContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/ppp/entity_contacts.json')), true);

        DB::table('entity_contacts')->insert($data);

        $this->command->info('Entity_contacts data seeded successfully.');
    }
}
