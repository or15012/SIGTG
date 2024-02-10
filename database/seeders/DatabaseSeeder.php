<?php

namespace Database\Seeders;

use App\Models\TypeExtension;
use Database\Seeders\Cde\CourseSeeder;
use Database\Seeders\Cde\EvaluationCriteriaSeeder;
use Database\Seeders\Cde\StagesSeeder;
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
             * Seeders Curso de Especialización - CAlfaro.
             **/
            CourseSeeder::class,
            StagesSeeder::class,
            EvaluationCriteriaSeeder::class,







        ]);
    }
}
