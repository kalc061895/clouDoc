<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConvocatoriaEtapas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cet_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'cet_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cet_eta_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
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
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'cet_responsable_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
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

        $this->forge->addKey('cet_ide', true);
        $this->forge->addUniqueKey(['cet_con_ide', 'cet_eta_ide']);
        $this->forge->addForeignKey('cet_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cet_eta_ide', 'selec_etapas', 'eta_ide', 'CASCADE', 'RESTRICT');


        $this->forge->createTable('selec_convocatoria_etapas');
    }

    public function down()
    {
        $this->forge->dropTable('selec_convocatoria_etapas', true);
    }
}
