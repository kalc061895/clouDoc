<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteFormacionTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_postulante_formacion
        // --------------------------------------------------------------------
        $this->forge->addField([
            'pfo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pfo_pos_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pfo_nfo_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pfo_institucion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'pfo_carrera' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'pfo_grado' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'pfo_fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pfo_fecha_culminacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pfo_fecha_obtencion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pfo_documento_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
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

        $this->forge->addKey('pfo_ide', true);
        $this->forge->addForeignKey('pfo_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pfo_nfo_ide', 'selec_niveles_formacion', 'nfo_ide', 'CASCADE', 'SET NULL');

        $this->forge->createTable('selec_postulante_formacion', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_formacion', true);
    }
}
