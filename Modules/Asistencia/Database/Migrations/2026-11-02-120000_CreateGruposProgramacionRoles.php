<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Adds hierarchy scopes for role-programming periods and migrates legacy establishments. */
class CreateGruposProgramacionRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','auto_increment'=>true],
            'nivel' => ['type'=>'VARCHAR','constraint'=>24],
            'entidad_id' => ['type'=>'INT'],
            'nombre' => ['type'=>'VARCHAR','constraint'=>150],
            'estado' => ['type'=>'VARCHAR','constraint'=>16,'default'=>'ACTIVO'],
            'created_by' => ['type'=>'INT','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['nivel','entidad_id']);
        $this->forge->createTable('casis_plan_grupo');

        if (!$this->db->fieldExists('grupo_id', 'casis_plan_periodo')) {
            $this->forge->addColumn('casis_plan_periodo', ['grupo_id'=>['type'=>'INT','null'=>true]]);
        }
        if ($this->db->fieldExists('est_id', 'casis_plan_periodo')) {
            $this->forge->modifyColumn('casis_plan_periodo', [
                'est_id'=>['name'=>'est_id','type'=>'INT','null'=>true],
            ]);
        }

        $periodos = $this->db->table('casis_plan_periodo')->where('grupo_id', null)->where('est_id !=', null)->get()->getResultArray();
        $grupos = [];
        foreach ($periodos as $periodo) {
            $estId = (int) $periodo['est_id'];
            if (!isset($grupos[$estId])) {
                $existente = $this->db->table('casis_plan_grupo')->where('nivel','ESTABLECIMIENTO')->where('entidad_id',$estId)->get()->getRowArray();
                if (!$existente) {
                    $nombre = $this->db->table('casis_establecimiento')->select('est_nombre')->where('est_ide',$estId)->get()->getRowArray()['est_nombre'] ?? ('Establecimiento ' . $estId);
                    $this->db->table('casis_plan_grupo')->insert(['nivel'=>'ESTABLECIMIENTO','entidad_id'=>$estId,'nombre'=>'Roles - ' . $nombre,'estado'=>'ACTIVO']);
                    $existente = ['id'=>(int)$this->db->insertID()];
                }
                $grupos[$estId] = (int) $existente['id'];
            }
            $this->db->table('casis_plan_periodo')->where('id',(int)$periodo['id'])->update(['grupo_id'=>$grupos[$estId]]);
        }
        if (!$this->indexExists('uk_plan_periodo_grupo_mes')) $this->db->query('CREATE UNIQUE INDEX uk_plan_periodo_grupo_mes ON casis_plan_periodo (grupo_id, mes)');
    }

    public function down()
    {
        if ($this->db->fieldExists('grupo_id', 'casis_plan_periodo')) {
            if ($this->indexExists('uk_plan_periodo_grupo_mes')) {
                if ($this->db->DBDriver === 'SQLite3') $this->db->query('DROP INDEX uk_plan_periodo_grupo_mes');
                else $this->db->query('ALTER TABLE casis_plan_periodo DROP INDEX uk_plan_periodo_grupo_mes');
            }
            $this->forge->dropColumn('casis_plan_periodo','grupo_id');
        }
        $this->forge->modifyColumn('casis_plan_periodo', [
            'est_id'=>['name'=>'est_id','type'=>'INT','null'=>false],
        ]);
        $this->forge->dropTable('casis_plan_grupo', true);
    }

    private function indexExists(string $name): bool
    {
        if ($this->db->DBDriver === 'SQLite3') return (bool)$this->db->query("SELECT name FROM sqlite_master WHERE type='index' AND name=?",[$name])->getRowArray();
        return (bool)$this->db->query("SHOW INDEX FROM casis_plan_periodo WHERE Key_name = '" . $this->db->escapeString($name) . "'")->getRowArray();
    }
}
