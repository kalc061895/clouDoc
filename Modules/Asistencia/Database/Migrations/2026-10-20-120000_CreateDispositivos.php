<?php
namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDispositivos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'codigo'=>['type'=>'VARCHAR','constraint'=>40],
            'nombre'=>['type'=>'VARCHAR','constraint'=>150],
            'marca'=>['type'=>'VARCHAR','constraint'=>20],
            'est_id'=>['type'=>'INT','unsigned'=>true],
            'modelo'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'serie'=>['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'ip'=>['type'=>'VARCHAR','constraint'=>45,'null'=>true],
            'puerto'=>['type'=>'INT','null'=>true],
            'activo'=>['type'=>'TINYINT','default'=>1],
            'created_by'=>['type'=>'INT','unsigned'=>true],
            'updated_by'=>['type'=>'INT','unsigned'=>true],
            'created_at'=>['type'=>'DATETIME'],
            'updated_at'=>['type'=>'DATETIME'],
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->addForeignKey('est_id','casis_establecimiento','est_ide','RESTRICT','RESTRICT');
        $this->forge->createTable('casis_dispositivo');
        $this->forge->addColumn('casis_asistencia_importacion',[
            'imp_codigo'=>['type'=>'VARCHAR','constraint'=>60,'null'=>true],
            'imp_dispositivo_id'=>['type'=>'INT','unsigned'=>true,'null'=>true],
            'imp_estado'=>['type'=>'VARCHAR','constraint'=>20,'default'=>'LEGADO'],
            // Clave exclusiva para cargas nuevas; no altera duplicados históricos.
            'imp_clave'=>['type'=>'CHAR','constraint'=>64,'null'=>true],
            'imp_confirmado_por'=>['type'=>'INT','unsigned'=>true,'null'=>true],
            'imp_errores'=>['type'=>'LONGTEXT','null'=>true],
            'imp_dispositivo_snapshot'=>['type'=>'TEXT','null'=>true],
        ]);
        $this->db->query('CREATE UNIQUE INDEX uk_importacion_clave ON casis_asistencia_importacion (imp_clave)');
        $this->db->query('CREATE UNIQUE INDEX uk_importacion_codigo ON casis_asistencia_importacion (imp_codigo)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX uk_importacion_clave ON casis_asistencia_importacion');
        $this->db->query('DROP INDEX uk_importacion_codigo ON casis_asistencia_importacion');
        $this->forge->dropColumn('casis_asistencia_importacion',['imp_codigo','imp_dispositivo_id','imp_estado','imp_clave','imp_confirmado_por','imp_errores','imp_dispositivo_snapshot']);
        $this->forge->dropTable('casis_dispositivo');
    }
}
