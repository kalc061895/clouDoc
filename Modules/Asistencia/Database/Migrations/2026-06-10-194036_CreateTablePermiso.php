<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePermiso extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'pero_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'pero_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'pero_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'pero_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'pero_remunerado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Remunerado,0=No remunerado',
            ],

            'pero_horas_maximas' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],

            'pero_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'deleted_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

        ]);

        $this->forge->addKey('pero_ide', true);

        $this->forge->addUniqueKey(
            'pero_nombre',
            'uk_permiso_nombre'
        );

        $this->forge->createTable('casis_permiso');
    }

    public function down()
    {
        $this->forge->dropTable('casis_permiso', true);
    }
}
