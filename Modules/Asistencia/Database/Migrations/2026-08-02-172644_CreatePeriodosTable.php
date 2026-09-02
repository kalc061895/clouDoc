<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'per_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'gco_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ID del Grupo de Corte / Régimen (CAS, Nombrados, etc.)',
            ],
            'per_anio' => [
                'type' => 'INT',
                'constraint' => 4,
                'unsigned' => true,
                'comment' => 'Año del período (ej. 2026)',
            ],
            'per_mes' => [
                'type' => 'INT',
                'constraint' => 2,
                'unsigned' => true,
                'comment' => 'Mes del período (1 a 12)',
            ],
            'per_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'comment' => 'Ej: JULIO 2026 - ADMINISTRATIVOS CAS',
            ],
            'per_fecha_inicio' => [
                'type' => 'DATE',
                'comment' => 'Fecha de inicio del ciclo de evaluación',
            ],
            'per_fecha_fin' => [
                'type' => 'DATE',
                'comment' => 'Fecha de cierre del ciclo de evaluación',
            ],
            'per_estado' => [
                'type' => 'ENUM',
                'constraint' => ['PROGRAMADO', 'ABIERTO', 'EN_EVALUACION', 'CERRADO', 'REABIERTO'],
                'default' => 'PROGRAMADO',
                'comment' => 'Estado operativo para el control de edición y cómputo',
            ],
            'per_observacion' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Notas sobre aperturas o cierres excepcionales',
            ],

            // CAMPOS DE AUDITORÍA DE USUARIOS
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

            // TIMESTAMPS Y SOFT DELETE
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

        // Clave Primaria
        $this->forge->addKey('per_ide', true);

        // Clave Foránea con la tabla de Grupos de Corte (si existe)
        // $this->forge->addForeignKey('gco_ide', 'casis_grupos_corte', 'gco_ide', 'CASCADE', 'RESTRICT');

        // Índices estratégicos para búsquedas rápidas en la validación de fechas de asistencia
        $this->forge->addKey(['gco_ide', 'per_estado']);
        $this->forge->addKey(['per_fecha_inicio', 'per_fecha_fin']);
        $this->forge->addKey(['per_anio', 'per_mes']);

        $this->forge->createTable('casis_periodos', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_periodos', true);
    }
}
