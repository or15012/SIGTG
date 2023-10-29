<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class, // Generador de permisos
            SchoolSeeder::class,
            ProtocolSeeder::class,
            StateSeeder::class,
            UserSeeder::class,
            CycleSeeder::class,
        ]);
    }
}
