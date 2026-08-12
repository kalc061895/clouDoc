<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteProfesiones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ppr_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'ppr_pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'ppr_pro_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'ppr_institucion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'ppr_grado' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'ppr_titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'ppr_fecha' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ppr_colegiatura' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'ppr_habilitacion' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'ppr_documento_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('ppr_ide', true);
        $this->forge->addForeignKey('ppr_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('ppr_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('ppr_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulante_profesiones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_profesiones', true);
    }
}
