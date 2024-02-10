<?php

namespace Database\Seeders;

use App\Models\CriteriaStage;
use App\Models\TypeExtension;
use Database\Seeders\Exams\AreaSeeder;
use Database\Seeders\Exams\CriteriaSeeder;
use Database\Seeders\Exams\PhaseSeeder;
use Database\Seeders\Exams\SubAreaSeeder;
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




            //EXAMEN GENERAL TECNICO PROFESIONAL - KARLA ABREGO
           PhaseSeeder::class,
           AreaSeeder::class,
           SubAreaSeeder::class,
           CriteriaSeeder::class


        ]);
    }
}
