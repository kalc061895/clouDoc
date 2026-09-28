<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodosLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'log_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'per_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID del período evaluado',
            ],
            'estado_anterior' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => 'Estado previo a la modificación',
            ],
            'estado_nuevo' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'comment' => 'Nuevo estado asignado (ABIERTO, CERRADO, REABIERTO, etc.)',
            ],
            'log_accion' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'Ej: APERTURA, PRE_CIERRE, CIERRE, REAPERTURA_EXCEPCIONAL',
            ],
            'log_motivo' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Sustento/Justificación (Obligatorio para Reaperturas)',
            ],
            'log_documento_sustento' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
                'comment' => 'Ej: Oficio N° 120-2026-RED-SR/ORH, Resolución Directorial',
            ],
            'log_ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
                'comment' => 'IP desde la que se ejecutó la acción (Soporta IPv6)',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Usuario que ejecutó el cambio de estado',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Clave Primaria
        $this->forge->addKey('log_ide', true);

        // Clave Foránea vinculada a la tabla casis_periodos
        $this->forge->addForeignKey('per_ide', 'casis_periodos', 'per_ide', 'CASCADE', 'CASCADE');

        // Índices para consultas de auditoría
        $this->forge->addKey('per_ide');
        $this->forge->addKey('created_by');

        $this->forge->createTable('casis_periodos_log', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_periodos_log', true);
    }
}
