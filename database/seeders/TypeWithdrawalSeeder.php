<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class TypeWithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get(database_path('datos/type_withdrawals.json')), true);

        DB::table('type_withdrawals')->insert($data);

        $this->command->info('Types withdrawals data seeded successfully.');
    }
}
