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
                DECLARE last_number INT;
                DECLARE new_number VARCHAR(10);

                -- Obtener el último número de expediente
                SELECT MAX(CAST(numero_expediente AS UNSIGNED)) INTO last_number FROM expedientes;

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
