<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisAsistenciaImportacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'imp_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'imp_nombre_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'imp_ruta_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'imp_tipo_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                // xlsx, xls, dat, csv
            ],

            'imp_hash_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],

            'imp_origen' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                // ZKTECO, HIKVISION, MANUAL
            ],

            'imp_total_registros' => [
                'type'       => 'INT',
                'default'    => 0,
            ],

            'imp_total_importados' => [
                'type'       => 'INT',
                'default'    => 0,
            ],

            'imp_total_duplicados' => [
                'type'       => 'INT',
                'default'    => 0,
            ],

            'imp_total_error' => [
                'type'       => 'INT',
                'default'    => 0,
            ],

            'imp_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'imp_user_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            
            'imp_est_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'imp_fecha_proceso' => [
                'type' => 'DATETIME',
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

        ]);

        $this->forge->addKey('imp_ide', true);

        $this->forge->addKey('imp_user_ide');
        $this->forge->addKey('imp_est_ide');

        $this->forge->addKey('imp_hash_archivo');

        // Si tienes tabla usuarios:
        $this->forge->addForeignKey(
            'imp_user_ide',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'imp_est_ide',
            'casis_establecimiento',
            'est_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_asistencia_importacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_asistencia_importacion');
    }
}
