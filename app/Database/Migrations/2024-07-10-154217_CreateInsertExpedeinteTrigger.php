<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInsertExpedeinteTrigger extends Migration
{
    public function up()
    {
        // Crear el trigger
        $this->db->query("
            CREATE TRIGGER trg_generate_expediente_number
            BEFORE INSERT ON expedientes
            FOR EACH ROW
            BEGIN
                DECLARE last_number INT DEFAULT 0;
                DECLARE new_number VARCHAR(10);
                DECLARE current_year INT;

                -- Obtener el año actual
                SET current_year = YEAR(CURDATE());

                -- Obtener el último número de expediente del año actual
                SELECT MAX(CAST(numero_expediente AS UNSIGNED)) 
                INTO last_number 
                FROM expedientes 
                WHERE YEAR(fecha_recepcion) = current_year;

                -- Si no se encuentra un número, asignar 0 (para luego incrementar a 1)
                IF last_number IS NULL THEN
                    SET last_number = 0;
                END IF;

                -- Incrementar el número
                SET new_number = LPAD(last_number + 1, 6, '0');

                -- Asignar el nuevo número de expediente
                SET NEW.numero_expediente = new_number;
            END
        ");
    }

    public function down()
    {
        // Eliminar el trigger
        $this->db->query("DROP TRIGGER IF EXISTS trg_generate_expediente_number");
    }
}
