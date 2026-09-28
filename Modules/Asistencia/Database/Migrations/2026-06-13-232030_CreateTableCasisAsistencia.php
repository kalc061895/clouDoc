<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisAsistencia extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'asi_ide' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'asi_imp_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'asi_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'asi_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'null'       => true,
            ],

            'asi_fecha_hora' => [
                'type' => 'DATETIME',
            ],

            'asi_dispositivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                // ZKTECO, HIKVISION
            ],

            'asi_origen' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'IMPORTACION',
                // IMPORTACION, API, MANUAL
            ],

            'asi_ip_log' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'asi_user_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
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
        ]);

        $this->forge->addKey('asi_ide', true);

        $this->forge->addKey('asi_perl_ide');
        $this->forge->addKey('asi_imp_ide');
        $this->forge->addKey('asi_fecha_hora');
        $this->forge->addKey(['asi_perl_ide', 'asi_fecha_hora']);

        // Evita duplicados exactos
        $this->forge->addUniqueKey(
            ['asi_perl_ide', 'asi_fecha_hora'],
            'uk_asistencia_personal_fecha'
        );

        $this->forge->addForeignKey(
            'asi_perl_ide',
            'casis_personal',
            'perl_ide',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'asi_imp_ide',
            'casis_asistencia_importacion',
            'imp_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('casis_asistencia');
    }

    public function down()
    {
        $this->forge->dropTable('casis_asistencia');
    }
}
