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
            PasantiaCriteriaSeeder::class,

            //EXAMEN GENERAL TECNICO PROFESIONAL - KARLA ABREGO
            PhaseSeeder::class,
            AreaSeeder::class,
            SubAreaSeeder::class,
            CriteriaSeeder::class,

            /*
             * Seeders Curso de Especialización - CAlfaro.
             **/
            CourseSeeder::class,
            StagesSeeder::class,
            EvaluationCriteriaSeeder::class,

        ]);
    }
}
