<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCalificacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'cal_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'cal_user_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'cal_titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'cal_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'cal_inicio' => [
                'type' => 'DATE',
            ],

            'cal_fin' => [
                'type' => 'DATE',
            ],
            
            'cal_anio' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'cal_mes' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'cal_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'BORRADOR',
            ],

            // Auditoría

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],

            'updated_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],

            'deleted_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('cal_ide', true);

        $this->forge->createTable('casis_calificacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_calificacion');
    }
}
