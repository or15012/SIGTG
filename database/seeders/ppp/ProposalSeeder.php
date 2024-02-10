<?php

namespace Database\Seeders\Ppp;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/ppp/proposals.json')), true);

        DB::table('proposals')->insert($data);

        $this->command->info('Proposals data seeded successfully.');
    }
}
