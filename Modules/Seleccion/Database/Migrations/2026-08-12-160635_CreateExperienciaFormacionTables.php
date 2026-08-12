<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExperienciaFormacionTables extends Migration
{
    public function up()
    {
        // 1. selec_requisito_formacion
        $this->forge->addField([
            'rfo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rfo_req_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'rfo_nfo_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'rfo_pro_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'rfo_grado' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'rfo_obligatorio' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
        ]);
        $this->forge->addKey('rfo_ide', true);
        $this->forge->addForeignKey('rfo_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('rfo_nfo_ide', 'selec_niveles_formacion', 'nfo_ide', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('rfo_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'SET NULL');
        $this->forge->createTable('selec_requisito_formacion', true);

        // 2. selec_requisito_experiencia
        $this->forge->addField([
            'rex_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rex_req_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'rex_anios' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'rex_meses' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'rex_dias' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'rex_tipo_experiencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'rex_especifica' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'rex_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('rex_ide', true);
        $this->forge->addForeignKey('rex_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'CASCADE');
        $this->forge->createTable('selec_requisito_experiencia', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisito_experiencia', true);
        $this->forge->dropTable('selec_requisito_formacion', true);
    }
}
