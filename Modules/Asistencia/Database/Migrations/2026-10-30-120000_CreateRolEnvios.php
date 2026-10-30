<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Snapshots versionados e inmutables de los roles enviados a aprobación. */
class CreateRolEnvios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'rol_id' => ['type' => 'INT', 'unsigned' => true],
            'version' => ['type' => 'INT', 'unsigned' => true],
            'anio' => ['type' => 'SMALLINT', 'unsigned' => true],
            'mes' => ['type' => 'TINYINT', 'unsigned' => true],
            'id_oficina' => ['type' => 'INT', 'unsigned' => true],
            'id_establecimiento' => ['type' => 'INT', 'unsigned' => true],
            'datos_json' => ['type' => 'LONGTEXT'],
            'cabecera_json' => ['type' => 'LONGTEXT'],
            'cabecera_sha256' => ['type' => 'CHAR', 'constraint' => 64],
            'hash_sha256' => ['type' => 'CHAR', 'constraint' => 64],
            'estado' => ['type' => 'VARCHAR', 'constraint' => 32],
            'usuario_envio_id' => ['type' => 'INT', 'unsigned' => true],
            'enviado_at' => ['type' => 'DATETIME'],
            'usuario_rrhh_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'rrhh_at' => ['type' => 'DATETIME', 'null' => true],
            'comentario_rrhh' => ['type' => 'TEXT', 'null' => true],
            'usuario_direccion_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'direccion_at' => ['type' => 'DATETIME', 'null' => true],
            'comentario_direccion' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['rol_id', 'version']);
        $this->forge->addKey(['estado', 'id_establecimiento', 'anio', 'mes'], false, false, 'idx_rol_envios_bandeja');
        $this->forge->addKey(['id_oficina', 'anio', 'mes'], false, false, 'idx_rol_envios_oficina_periodo');
        $this->forge->createTable('casis_rol_envio');
    }

    public function down()
    {
        $this->forge->dropTable('casis_rol_envio');
    }
}
