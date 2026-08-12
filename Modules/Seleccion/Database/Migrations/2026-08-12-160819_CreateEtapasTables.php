<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEtapasTables extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_etapas
        // --------------------------------------------------------------------
        $this->forge->addField([
            'eta_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'eta_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'eta_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'eta_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'eta_orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'eta_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('eta_ide', true);
        $this->forge->addUniqueKey('eta_codigo');
        $this->forge->createTable('selec_etapas', true);

        // --------------------------------------------------------------------
        // 2. selec_convocatoria_etapas
        // --------------------------------------------------------------------
        $this->forge->addField([
            'cet_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cet_con_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cet_eta_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cet_fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'cet_hora_inicio' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'cet_fecha_cierre' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'cet_hora_cierre' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'cet_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'cet_responsable_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'cet_observacion' => [
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
        $this->forge->addKey('cet_ide', true);
        $this->forge->addUniqueKey(['cet_con_ide', 'cet_eta_ide']);
        $this->forge->addForeignKey('cet_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('cet_eta_ide', 'selec_etapas', 'eta_ide', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('selec_convocatoria_etapas', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_convocatoria_etapas', true);
        $this->forge->dropTable('selec_etapas', true);
    }
}
