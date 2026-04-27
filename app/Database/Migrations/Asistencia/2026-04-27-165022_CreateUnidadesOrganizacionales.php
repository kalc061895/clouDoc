<?php

namespace App\Database\Migrations\Asistencia;

use CodeIgniter\Database\Migration;

class CreateUnidadesOrganizacionales extends Migration
{
    public function up()
    {
        // 1. Crear la tabla de Unidades Organizacionales (Jerárquica)
        $this->forge->addField([
            'uo_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uo_padre_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'uo_nivel_tipo' => [
                'type'       => 'ENUM',
                'constraint' => ['EJECUTORA', 'ORGANICA', 'ESTABLECIMIENTO', 'DEPARTAMENTO', 'AREA', 'SERVICIO', 'OFICINA'],
                'default'    => 'AREA',
            ],
            'uo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'uo_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'uo_direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'uo_ubicacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'uo_jefe_per_ide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'uo_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'uo_fecha_registro' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('uo_ide', true);
        $this->forge->addForeignKey('uo_padre_ide', 'casis_unidades_organizacionales', 'uo_ide', 'CASCADE', 'SET NULL');
        $this->forge->createTable('casis_unidades_organizacionales');

        // 2. Modificar la tabla casis_personal para añadir la relación
        // Esto permite saber a qué unidad exacta pertenece el trabajador
        $fields = [
            'perl_uo_ide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'perl_ser_ide' // Se coloca después del campo de servicio
            ],
        ];
        $this->forge->addColumn('casis_personal', $fields);

        // Añadir la llave foránea a casis_personal
        $this->db->query("ALTER TABLE casis_personal ADD CONSTRAINT fk_personal_uo FOREIGN KEY (perl_uo_ide) REFERENCES casis_unidades_organizacionales(uo_ide) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        // Eliminar la llave foránea y la columna en casis_personal
        $this->db->query("ALTER TABLE casis_personal DROP FOREIGN KEY fk_personal_uo");
        $this->forge->dropColumn('casis_personal', 'perl_uo_ide');

        // Eliminar la tabla principal
        $this->forge->dropTable('casis_unidades_organizacionales');
    }
}
