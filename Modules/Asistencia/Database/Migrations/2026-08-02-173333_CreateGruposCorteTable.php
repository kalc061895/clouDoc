<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGruposCorteTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'gco_ide' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            // -----------------------------------------------------------------
            // CLAVES DE JERARQUÍA MULTIENTIDAD / EJECUTORA
            // (Permite asignar el corte al nivel jerárquico que corresponda)
            // -----------------------------------------------------------------
            'diresa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID de la DIRESA/GERESA matriz',
            ],
            'red_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID de la Red de Salud / Unidad Ejecutora',
            ],
            'microred_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID de la Microred (Opcional)',
            ],
            'establecimiento_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID del Hospital / CS / PS (Si es corte propio)',
            ],
            // -----------------------------------------------------------------
            // CONFIGURACIÓN DEL GRUPO DE CORTE
            // -----------------------------------------------------------------
            'gco_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'comment' => 'Ej: ASISTENCIAL HOSPITAL, ADMINISTRATIVOS RED, CAS D.L. 1057',
            ],
            'gco_mco_ide' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Ej: 276, 728, 1057(CAS), 1153(Salud). NULL para todos.',
            ],
            'gco_dia_inicio' => [
                'type' => 'INT',
                'constraint' => 2,
                'unsigned' => true,
                'default' => 1,
                'comment' => 'Día del mes en que inicia el corte (Ej: 26 para el ciclo 26-25)',
            ],
            'gco_dia_fin' => [
                'type' => 'INT',
                'constraint' => 2,
                'unsigned' => true,
                'default' => 30,
                'comment' => 'Día del mes en que finaliza el corte (Ej: 25 para el ciclo 26-25)',
            ],
            'gco_mes_desfasado' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'True si el rango cruza dos meses (Ej: 26 de Junio al 25 de Julio)',
            ],
            'gco_estado' => [
                'type' => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default' => 'ACTIVO',
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

        $this->forge->addKey('gco_ide', true);

        // Índices para búsquedas jerárquicas rápidas
        $this->forge->addKey(['diresa_id', 'red_id', 'establecimiento_id']);
        $this->forge->addKey('gco_estado');

        $this->forge->createTable('casis_grupos_corte', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_grupos_corte', true);
    }
}
