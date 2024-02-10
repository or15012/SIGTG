<?php

namespace Database\Seeders;

use Database\Seeders\Pdi\PdiProposalSeeder;
use Database\Seeders\Pdi\PdiStageSeeder;
use Database\Seeders\Ppp\EntityContactSeeder;
use Database\Seeders\Ppp\EntitySeeder;
use Database\Seeders\Ppp\ProposalSeeder;
use Database\Seeders\Ppp\StageSeeder;
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
            ModalitySeeder::class,
            ProtocolSeeder::class,
            StateSeeder::class,
            UserSeeder::class,
            CycleSeeder::class,
            ParameterSeeder::class,
            UserProtocolSeeder::class,
            TypeExtensionSeeder::class,
            RolAndPermissionSeeder::class,
            RoleSeeder::class,
            RolePermission::class,


            /**
             * Seeder Dennis Pasantia Profesional
             *
             */
            EntitySeeder::class,
            EntityContactSeeder::class,
            ProposalSeeder::class,
            StageSeeder::class,



            /**
             * Seeder Dennis Pasantia de Investigación
             *
             */
            PdiProposalSeeder::class,
            PdiStageSeeder::class,

        ]);
    }
}
