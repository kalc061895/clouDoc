<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistroPermisoHistorialTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rph_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'rph_rp_ide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'ID del registro de permiso (casis_registro_permiso)',
            ],
            'rph_accion' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'CREAR, EDITAR, ELIMINAR, APROBAR',
            ],
            'rph_datos_anteriores' => [
                'type' => 'JSON',
                'null' => true,
                'comment'    => 'JSON con los datos previos (null en CREAR)',
            ],
            'rph_datos_nuevos' => [
                'type' => 'JSON',
                'null' => true,
                'comment'    => 'JSON con los datos nuevos (null en ELIMINAR)',
            ],
            'rph_motivo_cambio' => [
                'type' => 'TEXT',
                'null' => true,
                'comment'    => 'Razón del ajuste manual',
            ],
            'rph_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'comment'    => 'Dirección IP del usuario',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'ID del usuario logueado',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('rph_ide', true);
        $this->forge->addKey('rph_rp_ide');
        $this->forge->createTable('casis_registro_permiso_historial', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_permiso_historial', true);
    }
}
