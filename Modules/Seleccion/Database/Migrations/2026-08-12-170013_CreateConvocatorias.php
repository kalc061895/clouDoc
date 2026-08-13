<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConvocatorias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'con_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'con_numero' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'con_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'con_tco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'con_eco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'con_regimen_laboral' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'con_anio' => [
                'type' => 'INT',
                'null' => false,
            ],
            'con_resolucion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'con_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'con_fecha_publicacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_fecha_cierre' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_responsable_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'con_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('con_ide', true);
        $this->forge->addUniqueKey('con_codigo');
        $this->forge->addForeignKey('con_tco_ide', 'selec_tipos_convocatoria', 'tco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('con_eco_ide', 'selec_estados_convocatoria', 'eco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('con_responsable_ide', 'users', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('selec_convocatorias');
    }

    public function down()
    {
        $this->forge->dropTable('selec_convocatorias', true);
    }
}
