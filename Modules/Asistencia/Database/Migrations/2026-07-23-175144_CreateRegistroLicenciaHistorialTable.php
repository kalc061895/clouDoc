<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistroLicenciaHistorialTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'his_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'his_rl_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID de la licencia auditada (casis_registro_licencia.rl_ide)',
            ],
            'his_accion' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'comment' => 'Acción: CREAR, EDITAR, ELIMINAR, APROBAR, RECHAZAR',
            ],
            'his_datos_anteriores' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Captura JSON del registro antes del cambio (NULL en CREAR)',
            ],
            'his_datos_nuevos' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Captura JSON del registro después del cambio (NULL en ELIMINAR)',
            ],
            'his_motivo_cambio' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Justificación o motivo del ajuste manual / borrado',
            ],
            'his_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'Dirección IP del cliente (IPv4 / IPv6)',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID del usuario que realizó la acción',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('his_ide', true);
        $this->forge->addKey('his_rl_ide'); // Índice para búsquedas rápidas por licencia
        $this->forge->addKey('created_by');

        // Llave foránea opcional si quieres integridad referencial directa:
        // $this->forge->addForeignKey('his_rl_ide', 'casis_registro_licencia', 'rl_ide', 'CASCADE', 'CASCADE');

        $this->forge->createTable('casis_registro_licencia_historial', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_licencia_historial', true);
    }
}
