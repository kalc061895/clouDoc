<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAsistenciaAndProgramacionAudit extends Migration
{
    public function up()
    {
        // 1. Agregar campos de auditoría y soft delete a casis_programacion si no existen
        $fieldsProg = [];

        if (!$this->db->fieldExists('created_at', 'casis_programacion')) {
            $fieldsProg['created_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'prog_reg',
            ];
        }
        if (!$this->db->fieldExists('updated_at', 'casis_programacion')) {
            $fieldsProg['updated_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at',
            ];
        }
        if (!$this->db->fieldExists('deleted_at', 'casis_programacion')) {
            $fieldsProg['deleted_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ];
        }
        if (!$this->db->fieldExists('created_by', 'casis_programacion')) {
            $fieldsProg['created_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'deleted_at',
            ];
        }
        if (!$this->db->fieldExists('updated_by', 'casis_programacion')) {
            $fieldsProg['updated_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'created_by',
            ];
        }
        if (!$this->db->fieldExists('deleted_by', 'casis_programacion')) {
            $fieldsProg['deleted_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'updated_by',
            ];
        }

        if (!empty($fieldsProg)) {
            $this->forge->addColumn('casis_programacion', $fieldsProg);
        }

        // 2. Agregar campos a casis_asistencia si no existen
        $fieldsAsi = [];

        if (!$this->db->fieldExists('asi_tipo', 'casis_asistencia')) {
            $fieldsAsi['asi_tipo'] = [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'after' => 'asi_origen',
            ];
        }
        if (!$this->db->fieldExists('asi_motivo', 'casis_asistencia')) {
            $fieldsAsi['asi_motivo'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'asi_tipo',
            ];
        }
        if (!$this->db->fieldExists('created_by', 'casis_asistencia')) {
            $fieldsAsi['created_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'deleted_at',
            ];
        }
        if (!$this->db->fieldExists('updated_by', 'casis_asistencia')) {
            $fieldsAsi['updated_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'created_by',
            ];
        }
        if (!$this->db->fieldExists('deleted_by', 'casis_asistencia')) {
            $fieldsAsi['deleted_by'] = [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'updated_by',
            ];
        }

        if (!empty($fieldsAsi)) {
            $this->forge->addColumn('casis_asistencia', $fieldsAsi);
        }
    }

    public function down()
    {
        $colsProg = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        foreach ($colsProg as $col) {
            if ($this->db->fieldExists($col, 'casis_programacion')) {
                $this->forge->dropColumn('casis_programacion', $col);
            }
        }

        $colsAsi = ['asi_tipo', 'asi_motivo', 'created_by', 'updated_by', 'deleted_by'];
        foreach ($colsAsi as $col) {
            if ($this->db->fieldExists($col, 'casis_asistencia')) {
                $this->forge->dropColumn('casis_asistencia', $col);
            }
        }
    }
}
