<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComisionMiembros extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cmi_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'cmi_com_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cmi_usu_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cmi_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false,
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
                'type' => 'BIGINT',
                'unsigned' => true,
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
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'updated_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'deleted_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
        ]);

        $this->forge->addKey('cmi_ide', true);
        $this->forge->addForeignKey('cmi_com_ide', 'selec_comisiones', 'com_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cmi_documento_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_comision_miembros');
    }

    public function down()
    {
        $this->forge->dropTable('selec_comision_miembros', true);
    }
}
