<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OtrosAnexosMigrate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_extra' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'id_postulante' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'tipo' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'FELICITACION',
                    'ENCARGATURA',
                    'SERUMS',
                    'CONADIS',
                    'FFAA',
                    'OTRO'
                ],
            ],

            'descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'fecha_expedicion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'id_anexo' => [
                'type' => 'INT',
                'null' => true,
            ],

        ]);

        $this->forge->addKey('id_extra', true);

        $this->forge->addForeignKey(
            'id_postulante',
            'postulantes',
            'id_postulante',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('informacion_extra', true);
    }

    public function down()
    {
        $this->forge->dropTable('informacion_extra', true);
    }
}
