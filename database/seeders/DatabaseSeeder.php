<?php

namespace Database\Seeders;

use Database\Seeders\Pdi\PdiProposalSeeder;
use Database\Seeders\Pdi\PdiStageSeeder;
use Database\Seeders\Ppp\EntityContactSeeder;
use Database\Seeders\Ppp\EntitySeeder;
use Database\Seeders\Ppp\ProposalSeeder;
use Database\Seeders\Ppp\StageSeeder;
use App\Models\CriteriaStage;
use App\Models\TypeExtension;
use Database\Seeders\Exams\AreaSeeder;
use Database\Seeders\Exams\CriteriaSeeder;
use Database\Seeders\Exams\PhaseSeeder;
use Database\Seeders\Exams\SubAreaSeeder;
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
