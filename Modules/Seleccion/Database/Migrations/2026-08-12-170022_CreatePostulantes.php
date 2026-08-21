<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulantes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pos_tdo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pos_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false,
            ],
            'pos_nombres' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'pos_apellido_paterno' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'pos_apellido_materno' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'pos_fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pos_sexo' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'pos_direccion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pos_dep_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'pos_prv_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'pos_dis_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'pos_telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'pos_email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'pos_password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pos_email_verificado' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'pos_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'default' => 'ACTIVO',
            ],
            'pos_user_id' => [
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

        $this->forge->addKey('pos_ide', true);
        $this->forge->addUniqueKey('pos_documento');
        $this->forge->addForeignKey('pos_tdo_ide', 'selec_tipos_documento', 'tdo_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pos_user_id', 'users', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulantes');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulantes', true);
    }
}
