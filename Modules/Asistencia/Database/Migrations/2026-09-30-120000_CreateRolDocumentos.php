<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolDocumentos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'codigo' => ['type' => 'VARCHAR', 'constraint' => 48],
            'anio' => ['type' => 'SMALLINT', 'unsigned' => true],
            'mes' => ['type' => 'TINYINT', 'unsigned' => true],
            'est_ide' => ['type' => 'INT', 'unsigned' => true],
            'establecimiento' => ['type' => 'VARCHAR', 'constraint' => 255],
            'ambito' => ['type' => 'TEXT'],
            'estado' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'GENERADO'],
            'filtros_json' => ['type' => 'TEXT'],
            'snapshot_json' => ['type' => 'LONGTEXT'],
            'archivo' => ['type' => 'VARCHAR', 'constraint' => 180],
            'sha256' => ['type' => 'CHAR', 'constraint' => 64],
            'total_personal' => ['type' => 'INT', 'unsigned' => true],
            'total_horas' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'created_by' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME'],
            'anulado_por' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'anulado_at' => ['type' => 'DATETIME', 'null' => true],
            'motivo_anulacion' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->addKey(['est_ide', 'anio', 'mes']);
        $this->forge->addKey('estado');
        $this->forge->createTable('casis_rol_documento');
    }

    public function down()
    {
        $this->forge->dropTable('casis_rol_documento');
    }
}
