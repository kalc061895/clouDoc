<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'act_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'act_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'act_eta_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'act_numero' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'act_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'act_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'act_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'act_acuerdos' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'act_observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'act_documento_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'act_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('act_ide', true);
        $this->forge->addForeignKey('act_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('act_eta_ide', 'selec_etapas', 'eta_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('act_documento_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_actas');
    }

    public function down()
    {
        $this->forge->dropTable('selec_actas', true);
    }
}
