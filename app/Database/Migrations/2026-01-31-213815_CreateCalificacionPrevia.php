<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCalificacionPrevia extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id_calificacion_previa' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true,
            ],

            'id_postulacion' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'id_postulante' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'id_convocatoria' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            // CHECKS
            'titulo' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],

            'titulo_especialidad' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],

            'doctorado' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],

            'maestria' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],

            'experiencia' => [
                'type' => 'TINYINT',
                'default' => 0,
            ],

            // RESULTADO
            'estado_evaluacion' => [
                'type' => 'ENUM',
                'constraint' => ['APTO', 'NO APTO'],
                'default' => 'NO APTO',
            ],

            'observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'evaluado_por' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id_calificacion_previa', true);

        $this->forge->createTable('calificacion_previa');
    }

    public function down()
    {
        $this->forge->dropTable('calificacion_previa');
    }
}
