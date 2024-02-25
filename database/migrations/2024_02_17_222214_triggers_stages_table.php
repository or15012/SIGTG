<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER before_insert_stages
            BEFORE INSERT ON stages FOR EACH ROW
            BEGIN
                IF NEW.start_date IS NOT NULL AND NEW.end_date IS NOT NULL THEN
                    SET NEW.days = DATEDIFF(NEW.end_date, NEW.start_date);
                END IF;
            END;'
            );

        // Crea el trigger para ACTUALIZAR
        DB::unprepared('CREATE TRIGGER before_update_stages
            BEFORE UPDATE ON stages FOR EACH ROW
            BEGIN
                IF NEW.start_date IS NOT NULL AND NEW.end_date IS NOT NULL THEN
                    SET NEW.days = DATEDIFF(NEW.end_date, NEW.start_date);
                END IF;
            END;'
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_stages');

        // Elimina el trigger para ACTUALIZAR
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_stages');
    }
};
