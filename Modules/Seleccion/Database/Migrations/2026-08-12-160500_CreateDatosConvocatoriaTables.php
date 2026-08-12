<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDatosConvocatoriaTables extends Migration
{
    public function up()
    {
        // 1. selec_convocatorias
        $this->forge->addField([
            'con_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'con_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'con_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'con_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'con_tco_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'con_eco_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'con_regimen_laboral' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'con_anio' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'con_resolucion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'con_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'con_fecha_publicacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_fecha_cierre' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'con_responsable_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'con_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
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
        $this->forge->addKey('con_ide', true);
        $this->forge->addUniqueKey('con_codigo');
        $this->forge->addForeignKey('con_tco_ide', 'selec_tipos_convocatoria', 'tco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('con_eco_ide', 'selec_estados_convocatoria', 'eco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('selec_convocatorias', true);

        // 2. selec_convocatoria_documentos
        $this->forge->addField([
            'cod_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cod_con_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cod_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'cod_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cod_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'cod_ruta' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'cod_nombre_interno' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cod_mime' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'cod_tamanio' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'null'       => true,
            ],
            'cod_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'cod_version' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'cod_obligatorio' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'created_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('cod_ide', true);
        $this->forge->addForeignKey('cod_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE');
        $this->forge->createTable('selec_convocatoria_documentos', true);

        // 3. selec_cargos
        $this->forge->addField([
            'car_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'car_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'car_denominacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => false,
            ],
            'car_especialidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'car_tca_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'car_gru_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'car_niv_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'car_pro_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'car_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'car_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('car_ide', true);
        $this->forge->addForeignKey('car_tca_ide', 'selec_tipos_cargo', 'tca_ide', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('car_gru_ide', 'selec_grupos_ocupacionales', 'gru_ide', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('car_niv_ide', 'selec_niveles', 'niv_ide', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('car_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'SET NULL');
        $this->forge->createTable('selec_cargos', true);

        // 4. selec_convocatoria_cargos
        $this->forge->addField([
            'cco_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cco_con_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cco_car_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cco_numero_plazas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'cco_dependencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'cco_area' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'cco_est_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'cco_remuneracion' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'cco_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('cco_ide', true);
        $this->forge->addUniqueKey(['cco_con_ide', 'cco_car_ide']);
        $this->forge->addForeignKey('cco_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('cco_car_ide', 'selec_cargos', 'car_ide', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('selec_convocatoria_cargos', true);

        // 5. selec_requisitos
        $this->forge->addField([
            'req_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'req_cco_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'req_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'req_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'req_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'req_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'req_obligatorio' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'req_puntaje' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'req_orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('req_ide', true);
        $this->forge->addForeignKey('req_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'CASCADE');
        $this->forge->createTable('selec_requisitos', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisitos', true);
        $this->forge->dropTable('selec_convocatoria_cargos', true);
        $this->forge->dropTable('selec_cargos', true);
        $this->forge->dropTable('selec_convocatoria_documentos', true);
        $this->forge->dropTable('selec_convocatorias', true);
    }
}
