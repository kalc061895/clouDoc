<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRemuneraciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rem_ide' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'rem_car_ide' => ['type' => 'INT', 'unsigned' => true],
            'rem_nivel' => ['type' => 'VARCHAR', 'constraint' => 50],
            'rem_configuracion' => ['type' => 'JSON'],
            'rem_estado' => [
                'type' => 'TINYINT', 'constraint' => 1, 'default' => 1,
                'comment' => '1=Activo, 0=Inactivo',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('rem_ide', true);
        $this->forge->addUniqueKey(['rem_car_ide', 'rem_nivel']);
        $this->forge->addForeignKey('rem_car_ide', 'casis_cargo', 'car_ide', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('casis_remuneraciones');
    }

    public function down()
    {
        $this->forge->dropTable('casis_remuneraciones');
    }
}
