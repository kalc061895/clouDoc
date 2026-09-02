<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistroVacacionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'vrg_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'vrg_vac_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true, // FK hacia casis_vacacion
            ],
            'vrg_perl_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'vrg_fecha_inicio' => [
                'type' => 'DATE',
            ],
            'vrg_fecha_fin' => [
                'type' => 'DATE',
            ],
            'vrg_dias' => [
                'type' => 'INT',
                'constraint' => 3, // 1, 7, 15, 30 días
            ],
            'vrg_numero_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'vrg_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'vrg_motivo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'vrg_estado' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1, // 1: Activo, 0: Anulado
            ],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('vrg_ide', true);
        $this->forge->addForeignKey('vrg_vac_ide', 'casis_vacacion', 'vac_ide', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('casis_registro_vacacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_vacacion');
    }
}
