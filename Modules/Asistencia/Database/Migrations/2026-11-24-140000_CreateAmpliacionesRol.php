<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAmpliacionesRol extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'envio_id' => ['type' => 'INT', 'unsigned' => true],
            'personal_id' => ['type' => 'INT', 'unsigned' => true],
            'estado' => ['type' => 'VARCHAR', 'constraint' => 24],
            'motivo' => ['type' => 'TEXT'],
            'datos_json' => ['type' => 'LONGTEXT'],
            'solicitado_por' => ['type' => 'INT', 'unsigned' => true],
            'solicitado_at' => ['type' => 'DATETIME'],
            'resuelto_por' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'resuelto_at' => ['type' => 'DATETIME', 'null' => true],
            'comentario' => ['type' => 'TEXT', 'null' => true],
            'programaciones_json' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['envio_id', 'personal_id', 'estado']);
        $this->forge->addKey(['estado', 'id']);
        $this->forge->addForeignKey('envio_id', 'casis_rol_envio', 'id');
        $this->forge->createTable('casis_rol_ampliacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_rol_ampliacion');
    }
}
