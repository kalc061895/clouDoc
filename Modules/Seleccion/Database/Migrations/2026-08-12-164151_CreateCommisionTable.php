<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommisionTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_comisiones
        // --------------------------------------------------------------------
        $this->forge->addField([
            'com_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'com_con_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'com_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'com_fecha_designacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'com_documento_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'com_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVA',
                'null'       => true,
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

        $this->forge->addKey('com_ide', true);
        $this->forge->addForeignKey('com_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('com_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL');
        $this->forge->createTable('selec_comisiones', true);

        // --------------------------------------------------------------------
        // 2. selec_comision_miembros
        // --------------------------------------------------------------------
        $this->forge->addField([
            'cmi_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cmi_com_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cmi_usu_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cmi_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => false,
            ],
            'cmi_fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cmi_fecha_fin' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cmi_documento_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'cmi_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('cmi_ide', true);
        $this->forge->addForeignKey('cmi_com_ide', 'selec_comisiones', 'com_ide', 'CASCADE', 'CASCADE');
        // Asumiendo tabla general de usuarios/personal del sistema
        $this->forge->addForeignKey('cmi_usu_ide', 'usuarios', 'usu_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cmi_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL');
        $this->forge->createTable('selec_comision_miembros', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_comision_miembros', true);
        $this->forge->dropTable('selec_comisiones', true);
    }
}
