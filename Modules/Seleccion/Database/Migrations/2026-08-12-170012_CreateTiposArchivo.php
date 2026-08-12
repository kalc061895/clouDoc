<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposArchivo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tar_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tar_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tar_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'tar_extension' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'tar_mime' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('tar_ide', true);
        $this->forge->addUniqueKey('tar_codigo');

        $this->forge->createTable('selec_tipos_archivo');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_archivo', true);
    }
}
