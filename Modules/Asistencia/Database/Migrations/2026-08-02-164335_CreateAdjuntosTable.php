<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdjuntosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'adj_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'adj_modulo' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'Módulo origen (ej: papeletas, turnos, licencias)',
            ],
            'adj_registro_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID del registro primario en su respectivo módulo',
            ],
            'adj_local_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'adj_drive_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'adj_nombre_original' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'adj_mime_type' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'adj_tamano' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Tamaño del archivo en Bytes',
            ],
            'adj_orden' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 0,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Clave Primaria
        $this->forge->addKey('adj_ide', true);

        // Índice compuesto estratégico para acelerar obtenerPorRegistro($modulo, $registroId)
        $this->forge->addKey(['adj_modulo', 'adj_registro_id']);

        $this->forge->createTable('casis_adjuntos', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_adjuntos', true);
    }
}
