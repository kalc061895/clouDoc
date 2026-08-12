<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'aud_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'aud_usu_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'aud_rol_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'aud_fecha' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'aud_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'aud_accion' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'aud_modulo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'aud_tabla' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'aud_registro_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'aud_valor_anterior' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'aud_valor_nuevo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'aud_motivo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'aud_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'aud_eta_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'aud_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('aud_ide', true);
        $this->forge->addForeignKey('aud_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('aud_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('aud_eta_ide', 'selec_etapas', 'eta_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_auditoria');
    }

    public function down()
    {
        $this->forge->dropTable('selec_auditoria', true);
    }
}
