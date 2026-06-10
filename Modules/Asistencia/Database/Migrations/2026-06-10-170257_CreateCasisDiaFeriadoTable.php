<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCasisDiaFeriadoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'df_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'df_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],

            'df_fecha' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'df_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'df_repetitivo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0=No, 1=Si',
            ],
        ]);

        $this->forge->addKey('df_ide', true);

        // Evita duplicar fechas
        $this->forge->addUniqueKey('df_fecha', 'uk_df_fecha');

        $this->forge->createTable('casis_diaferiado');
    }

    public function down()
    {
        $this->forge->dropTable('casis_diaferiado', true);
    }
}
