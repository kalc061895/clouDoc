<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDatosGeneralesTables extends Migration
{
    public function up()
    {
        // 1. selec_tipos_convocatoria
        $this->forge->addField([
            'tco_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tco_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'tco_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'tco_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tco_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('tco_ide', true);
        $this->forge->addUniqueKey('tco_codigo');
        $this->forge->createTable('selec_tipos_convocatoria', true);

        // 2. selec_estados_convocatoria
        $this->forge->addField([
            'eco_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'eco_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'eco_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'eco_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'eco_orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'eco_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('eco_ide', true);
        $this->forge->addUniqueKey('eco_codigo');
        $this->forge->createTable('selec_estados_convocatoria', true);

        // 3. selec_tipos_cargo
        $this->forge->addField([
            'tca_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tca_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'tca_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'tca_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('tca_ide', true);
        $this->forge->addUniqueKey('tca_codigo');
        $this->forge->createTable('selec_tipos_cargo', true);

        // 4. selec_grupos_ocupacionales
        $this->forge->addField([
            'gru_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'gru_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'gru_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'gru_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('gru_ide', true);
        $this->forge->addUniqueKey('gru_codigo');
        $this->forge->createTable('selec_grupos_ocupacionales', true);

        // 5. selec_niveles
        $this->forge->addField([
            'niv_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'niv_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'niv_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'niv_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('niv_ide', true);
        $this->forge->addUniqueKey('niv_codigo');
        $this->forge->createTable('selec_niveles', true);

        // 6. selec_modalidades_vinculo
        $this->forge->addField([
            'mvi_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mvi_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'mvi_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'mvi_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('mvi_ide', true);
        $this->forge->addUniqueKey('mvi_codigo');
        $this->forge->createTable('selec_modalidades_vinculo', true);

        // 7. selec_profesiones
        $this->forge->addField([
            'pro_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pro_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'pro_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'pro_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('pro_ide', true);
        $this->forge->createTable('selec_profesiones', true);

        // 8. selec_niveles_formacion
        $this->forge->addField([
            'nfo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nfo_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'nfo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'nfo_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('nfo_ide', true);
        $this->forge->addUniqueKey('nfo_codigo');
        $this->forge->createTable('selec_niveles_formacion', true);

        // 9. selec_tipos_documento
        $this->forge->addField([
            'tdo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tdo_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => false,
            ],
            'tdo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'tdo_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('tdo_ide', true);
        $this->forge->addUniqueKey('tdo_codigo');
        $this->forge->createTable('selec_tipos_documento', true);

        // 10. selec_estados_postulacion
        $this->forge->addField([
            'epo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'epo_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'epo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'epo_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'epo_orden' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('epo_ide', true);
        $this->forge->addUniqueKey('epo_codigo');
        $this->forge->createTable('selec_estados_postulacion', true);

        // 11. selec_estados_expediente
        $this->forge->addField([
            'eex_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'eex_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'eex_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('eex_ide', true);
        $this->forge->addUniqueKey('eex_codigo');
        $this->forge->createTable('selec_estados_expediente', true);

        // 12. selec_tipos_archivo
        $this->forge->addField([
            'tar_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tar_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'tar_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'tar_extension' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tar_mime' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'tar_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('tar_ide', true);
        $this->forge->addUniqueKey('tar_codigo');
        $this->forge->createTable('selec_tipos_archivo', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_archivo', true);
        $this->forge->dropTable('selec_estados_expediente', true);
        $this->forge->dropTable('selec_estados_postulacion', true);
        $this->forge->dropTable('selec_tipos_documento', true);
        $this->forge->dropTable('selec_niveles_formacion', true);
        $this->forge->dropTable('selec_profesiones', true);
        $this->forge->dropTable('selec_modalidades_vinculo', true);
        $this->forge->dropTable('selec_niveles', true);
        $this->forge->dropTable('selec_grupos_ocupacionales', true);
        $this->forge->dropTable('selec_tipos_cargo', true);
        $this->forge->dropTable('selec_estados_convocatoria', true);
        $this->forge->dropTable('selec_tipos_convocatoria', true);
    }
}
