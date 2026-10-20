<?php
namespace Modules\Asistencia\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateGestionRoles extends Migration
{
    public function up()
    {
        $tables = [
            'casis_plan_periodo' => [
                'est_id' => ['type'=>'INT'], 'mes' => ['type'=>'CHAR','constraint'=>7],
                'apertura' => ['type'=>'DATETIME'], 'aprobacion' => ['type'=>'DATETIME'], 'cierre' => ['type'=>'DATETIME'],
                'reglas' => ['type'=>'LONGTEXT'],
            ],
            'casis_plan_responsable' => [
                'usuario_id'=>['type'=>'INT'], 'oficina_id'=>['type'=>'INT'],
                'desde'=>['type'=>'DATE'], 'hasta'=>['type'=>'DATE'],
                'aprobar'=>['type'=>'TINYINT','default'=>0],
            ],
            'casis_plan_rol' => [
                'periodo_id'=>['type'=>'INT'], 'oficina_id'=>['type'=>'INT'],
                'estado'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'BORRADOR'],
                'snapshot'=>['type'=>'LONGTEXT','null'=>true],
            ],
            'casis_plan_evento' => [
                'rol_id'=>['type'=>'INT','null'=>true], 'accion'=>['type'=>'VARCHAR','constraint'=>40],
                'detalle'=>['type'=>'LONGTEXT'],
            ],
        ];
        foreach ($tables as $table=>$fields) {
            $this->forge->addField(array_merge([
                'id'=>['type'=>'INT','auto_increment'=>true],
                'created_by'=>['type'=>'INT'], 'created_at'=>['type'=>'DATETIME'],
            ], $fields));
            $this->forge->addKey('id', true);
            if ($table === 'casis_plan_periodo') $this->forge->addUniqueKey(['est_id','mes']);
            if ($table === 'casis_plan_rol') $this->forge->addUniqueKey(['periodo_id','oficina_id']);
            $this->forge->createTable($table);
        }
        $this->forge->addColumn('casis_turno', ['tur_modalidad'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'ASISTENCIAL']]);
        $this->forge->addColumn('casis_personal', ['perl_modalidad'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'ASISTENCIAL']]);
    }
    public function down()
    {
        foreach (['casis_plan_evento','casis_plan_rol','casis_plan_responsable','casis_plan_periodo'] as $table) $this->forge->dropTable($table);
        $this->forge->dropColumn('casis_turno','tur_modalidad');
        $this->forge->dropColumn('casis_personal','perl_modalidad');
    }
}
