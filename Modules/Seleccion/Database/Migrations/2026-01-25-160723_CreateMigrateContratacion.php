<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMigrateContratacion extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | USERS (AUTH)
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        /*
        |--------------------------------------------------------------------------
        | POSTULANTES
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_postulante' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'user_id' => ['type'=>'INT','unsigned'=>true],
            'dni' => ['type'=>'VARCHAR','constraint'=>15,'unique'=>true],
            'nombres' => ['type'=>'VARCHAR','constraint'=>150],
            'paterno' => ['type'=>'VARCHAR','constraint'=>150],
            'materno' => ['type'=>'VARCHAR','constraint'=>150],
            'fecha_nacimiento' => ['type'=>'DATE','null'=>true],
            'direccion' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'telefono' => ['type'=>'VARCHAR','constraint'=>20,'null'=>true],
            'estado' => ['type'=>'TINYINT','default'=>1],
        ]);
        $this->forge->addKey('id_postulante', true);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->createTable('postulantes', true);

        /*
        |--------------------------------------------------------------------------
        | CONVOCATORIAS
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_convocatoria' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'codigo' => ['type'=>'VARCHAR','constraint'=>50],
            'titulo' => ['type'=>'VARCHAR','constraint'=>255],
            'descripcion' => ['type'=>'TEXT','null'=>true],
            'fecha_inicio' => ['type'=>'DATE'],
            'fecha_fin' => ['type'=>'DATE'],
            'estado' => ['type'=>'VARCHAR','constraint'=>30],
        ]);
        $this->forge->addKey('id_convocatoria', true);
        $this->forge->createTable('convocatorias', true);

        /*
        |--------------------------------------------------------------------------
        | PLAZAS
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_plaza' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'id_convocatoria' => ['type'=>'INT','unsigned'=>true],
            'codigo_plaza' => ['type'=>'VARCHAR','constraint'=>50],
            'establecimiento' => ['type'=>'VARCHAR','constraint'=>200,'null'=>true],
            'detalle' => ['type'=>'TEXT','null'=>true],
            'vacantes' => ['type'=>'INT'],
        ]);
        $this->forge->addKey('id_plaza', true);
        $this->forge->addForeignKey('id_convocatoria','convocatorias','id_convocatoria','CASCADE','CASCADE');
        $this->forge->createTable('plazas', true);

        /*
        |--------------------------------------------------------------------------
        | PERFIL DE PUESTO (CORREGIDO)
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_perfil' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'id_plaza' => ['type'=>'INT','unsigned'=>true],
            'unidad_organica' => ['type'=>'VARCHAR','constraint'=>200],
            'denominacion' => ['type'=>'VARCHAR','constraint'=>200],
            'nombre_puesto' => ['type'=>'VARCHAR','constraint'=>200],
            'dependencia_jerarquica' => ['type'=>'VARCHAR','constraint'=>200],
            'mision' => ['type'=>'TEXT'],
        ]);
        $this->forge->addKey('id_perfil', true);
        $this->forge->addForeignKey('id_plaza','plazas','id_plaza','CASCADE','CASCADE');
        $this->forge->createTable('perfil_puesto', true);

        /*
        |--------------------------------------------------------------------------
        | POSTULACIONES
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_postulacion' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'id_postulante' => ['type'=>'INT','unsigned'=>true],
            'id_convocatoria' => ['type'=>'INT','unsigned'=>true],
            'id_plaza' => ['type'=>'INT','unsigned'=>true],
            'estado' => ['type'=>'VARCHAR','constraint'=>50],
            'fecha_postulacion' => ['type'=>'TIMESTAMP','null'=>true],
        ]);
        $this->forge->addKey('id_postulacion', true);
        $this->forge->addForeignKey('id_postulante','postulantes','id_postulante');
        $this->forge->addForeignKey('id_convocatoria','convocatorias','id_convocatoria');
        $this->forge->addForeignKey('id_plaza','plazas','id_plaza');
        $this->forge->createTable('postulaciones', true);

        /*
        |--------------------------------------------------------------------------
        | EXPERIENCIA LABORAL
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_experiencia' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'id_postulante' => ['type'=>'INT','unsigned'=>true],
            'cargo' => ['type'=>'VARCHAR','constraint'=>150],
            'entidad' => ['type'=>'VARCHAR','constraint'=>200],
            'fecha_inicio' => ['type'=>'DATE'],
            'fecha_fin' => ['type'=>'DATE','null'=>true],
        ]);
        $this->forge->addKey('id_experiencia', true);
        $this->forge->addForeignKey('id_postulante','postulantes','id_postulante');
        $this->forge->createTable('experiencia_laboral', true);

        /*
        |--------------------------------------------------------------------------
        | FORMACIÓN PROFESIONAL
        |--------------------------------------------------------------------------
        */
        $this->forge->addField([
            'id_formacion' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'id_postulante' => ['type'=>'INT','unsigned'=>true],
            'nivel' => ['type'=>'VARCHAR','constraint'=>100],
            'carrera' => ['type'=>'VARCHAR','constraint'=>150],
            'institucion' => ['type'=>'VARCHAR','constraint'=>200],
            'fecha_inicio' => ['type'=>'DATE'],
            'fecha_fin' => ['type'=>'DATE','null'=>true],
        ]);
        $this->forge->addKey('id_formacion', true);
        $this->forge->addForeignKey('id_postulante','postulantes','id_postulante');
        $this->forge->createTable('formacion_profesional', true);
    }

    public function down()
    {
        $this->forge->dropTable('formacion_profesional', true);
        $this->forge->dropTable('experiencia_laboral', true);
        $this->forge->dropTable('postulaciones', true);
        $this->forge->dropTable('perfil_puesto', true);
        $this->forge->dropTable('plazas', true);
        $this->forge->dropTable('convocatorias', true);
        $this->forge->dropTable('postulantes', true);
        $this->forge->dropTable('users', true);
    }
}
