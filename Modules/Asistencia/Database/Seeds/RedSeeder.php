<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RedSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['red_ide' => 1, 'red_dir_ide' => 1, 'red_codigo' => 'UE-470', 'red_nombre' => 'EJERCITO PERUANO', 'red_ubicacion' => 'PUNO'],
            ['red_ide' => 2, 'red_dir_ide' => 1, 'red_codigo' => 'UE-916', 'red_nombre' => 'RED DE SALUD AZANGARO', 'red_ubicacion' => 'AZANGARO'],
            ['red_ide' => 3, 'red_dir_ide' => 1, 'red_codigo' => 'UE-920', 'red_nombre' => 'RED DE SALUD CHUCUITO', 'red_ubicacion' => 'JULI'],
            ['red_ide' => 4, 'red_dir_ide' => 1, 'red_codigo' => 'UE-968', 'red_nombre' => 'RED DE SALUD EL COLLAO', 'red_ubicacion' => 'ILAVE'],
            ['red_ide' => 5, 'red_dir_ide' => 1, 'red_codigo' => 'UE-918', 'red_nombre' => 'RED DE SALUD HUANCANE', 'red_ubicacion' => 'HUANCANE'],
            ['red_ide' => 6, 'red_dir_ide' => 1, 'red_codigo' => 'UE-1621', 'red_nombre' => 'RED DE SALUD LAMPA', 'red_ubicacion' => 'LAMPA'],
            ['red_ide' => 7, 'red_dir_ide' => 1, 'red_codigo' => 'UE-1006', 'red_nombre' => 'RED DE SALUD MACUSANI', 'red_ubicacion' => 'MACUSANI'],
            ['red_ide' => 8, 'red_dir_ide' => 1, 'red_codigo' => 'UE-915', 'red_nombre' => 'RED DE SALUD MELGAR', 'red_ubicacion' => 'AYAVIRI'],
            ['red_ide' => 9, 'red_dir_ide' => 1, 'red_codigo' => 'UE-919', 'red_nombre' => 'RED DE SALUD PUNO', 'red_ubicacion' => 'PUNO'],
            ['red_ide' => 10, 'red_dir_ide' => 1, 'red_codigo' => 'UE-1759', 'red_nombre' => 'RED DE SALUD SAN ANTONIO DE PUTINA', 'red_ubicacion' => 'PUTINA'],
            ['red_ide' => 11, 'red_dir_ide' => 1, 'red_codigo' => 'UE-917', 'red_nombre' => 'RED DE SALUD SAN ROMAN', 'red_ubicacion' => 'JULIACA'],
            ['red_ide' => 12, 'red_dir_ide' => 1, 'red_codigo' => 'UE-1007', 'red_nombre' => 'RED DE SALUD SANDIA', 'red_ubicacion' => 'SANDIA'],
            ['red_ide' => 13, 'red_dir_ide' => 1, 'red_codigo' => 'UE-967', 'red_nombre' => 'RED DE SALUD YUNGUYO', 'red_ubicacion' => 'YUNGUYO'],
        ];

        $this->db->table('casis_red')->insertBatch($data);
    }
}
