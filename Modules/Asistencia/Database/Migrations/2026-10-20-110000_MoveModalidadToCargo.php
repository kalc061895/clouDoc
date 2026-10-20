<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class MoveModalidadToCargo extends Migration
{
    public function up()
    {
        // No elegir arbitrariamente si un cargo tenía modalidades contradictorias.
        $conflictos = $this->db->query(
            'SELECT perl_car_ide FROM casis_personal
             WHERE perl_car_ide IS NOT NULL
             GROUP BY perl_car_ide HAVING COUNT(DISTINCT perl_modalidad) > 1'
        )->getResultArray();
        if ($conflictos) {
            throw new \RuntimeException('Unifique la modalidad del personal de estos cargos antes de migrar: '
                . implode(', ', array_column($conflictos, 'perl_car_ide')));
        }

        $this->forge->addColumn('casis_cargo', [
            'car_modalidad' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'ASISTENCIAL'],
        ]);
        $this->db->query(
            'UPDATE casis_cargo c
             JOIN (SELECT perl_car_ide, MAX(perl_modalidad) modalidad
                   FROM casis_personal GROUP BY perl_car_ide) p ON p.perl_car_ide = c.car_ide
             SET c.car_modalidad = p.modalidad'
        );
        $this->forge->dropColumn('casis_personal', 'perl_modalidad');
    }

    public function down()
    {
        $this->forge->addColumn('casis_personal', [
            'perl_modalidad' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'ASISTENCIAL'],
        ]);
        $this->db->query(
            'UPDATE casis_personal p JOIN casis_cargo c ON c.car_ide = p.perl_car_ide
             SET p.perl_modalidad = c.car_modalidad'
        );
        $this->forge->dropColumn('casis_cargo', 'car_modalidad');
    }
}
