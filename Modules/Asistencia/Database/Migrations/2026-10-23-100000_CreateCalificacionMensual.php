<?php
namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Asistencia\Services\CalificacionMotor;

class CreateCalificacionMensual extends Migration
{
    public function up()
    {
        $id=['type'=>'INT','unsigned'=>true,'auto_increment'=>true];
        $text=['type'=>'LONGTEXT']; $int=['type'=>'INT','unsigned'=>true]; $str=['type'=>'VARCHAR','constraint'=>100];
        foreach ([
            'casis_cal_criterio'=>['codigo'=>$str,'valor'=>$str,'updated_by'=>$int,'updated_at'=>['type'=>'DATETIME']],
            'casis_cal_mensual'=>['periodo_id'=>$int,'ambito_tipo'=>$str,'ambito_id'=>$int,'trabajador_id'=>$int,'estado'=>$str,'fuentes'=>$text,'reglas'=>$text,'resultado'=>$text,'created_by'=>$int,'created_at'=>['type'=>'DATETIME']],
            'casis_cal_evento'=>['calificacion_id'=>$int,'accion'=>$str,'detalle'=>$text,'created_by'=>$int,'created_at'=>['type'=>'DATETIME']],
            'casis_cal_pdf'=>['calificacion_id'=>$int,'contenido'=>$text,'sha256'=>$str,'created_by'=>$int,'created_at'=>['type'=>'DATETIME']],
        ] as $tabla=>$campos) {
            $this->forge->addField(['id'=>$id]+$campos); $this->forge->addKey('id',true);
            if ($tabla==='casis_cal_criterio') $this->forge->addUniqueKey('codigo');
            if (isset($campos['calificacion_id'])) { $this->forge->addKey('calificacion_id'); $this->forge->addForeignKey('calificacion_id','casis_cal_mensual','id','RESTRICT','RESTRICT'); }
            $this->forge->createTable($tabla);
        }
        foreach (CalificacionMotor::DEFAULTS as $codigo=>$valor) $this->db->table('casis_cal_criterio')->insert(['codigo'=>$codigo,'valor'=>(string)$valor,'updated_by'=>0,'updated_at'=>date('Y-m-d H:i:s')]);
    }
    public function down()
    {
        foreach (['casis_cal_pdf','casis_cal_evento','casis_cal_mensual','casis_cal_criterio'] as $tabla) $this->forge->dropTable($tabla);
    }
}
