<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRegistroVacacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rv_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'rv_vac_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'FK casis_vacacion',
            ],
            'rv_perl_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID del personal para búsquedas directas',
            ],
            'rv_fecha_inicio' => [
                'type' => 'DATE',
            ],
            'rv_fecha_fin' => [
                'type' => 'DATE',
            ],
            'rv_dias' => [
                'type' => 'INT',
                'constraint' => 3,
                'comment' => 'Días calendario consumidos (1, 7, 15, 30, etc.)',
            ],
            'rv_numero_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Número de Papeleta, Memorándum o Resolución',
            ],
            'rv_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'rv_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rv_estado' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1: Activo, 0: Anulado/Cancelado',
            ],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('rv_ide', true);
        $this->forge->addKey('rv_vac_ide');
        $this->forge->addKey('rv_perl_ide');

        // Llave foránea que relaciona el uso con su período correspondiente
        $this->forge->addForeignKey('rv_vac_ide', 'casis_vacacion', 'vac_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('casis_registro_vacacion', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_vacacion', true);
    }

}
