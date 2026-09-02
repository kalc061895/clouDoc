<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiaFeriadoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'df_nombre' => 'AÑO NUEVO',
                'df_fecha' => '2026-01-01',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'DIA DEL TRABAJO',
                'df_fecha' => '2026-05-01',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'BATALLA DE ARICA Y DIA DE LA BANDERA',
                'df_fecha' => '2026-06-07',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'SAN PEDRO Y SAN PABLO',
                'df_fecha' => '2026-06-29',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'DIA DE LA FUERZA AEREA DEL PERU (RELIQUIA JOSE QUIÑONES)',
                'df_fecha' => '2026-07-23',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'FIESTAS PATRIAS - INAUGURACION DE LA REPUBLICA',
                'df_fecha' => '2026-07-28',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'FIESTAS PATRIAS - CELEBRACION NACIONAL',
                'df_fecha' => '2026-07-29',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'BATALLA DE JUNIN',
                'df_fecha' => '2026-08-06',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'SANTA ROSA DE LIMA',
                'df_fecha' => '2026-08-30',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'COMBATE DE ANGAMOS',
                'df_fecha' => '2026-10-08',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'DIA DE TODOS LOS SANTOS',
                'df_fecha' => '2026-11-01',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'INMACULADA CONCEPCION',
                'df_fecha' => '2026-12-08',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'BATALLA DE AYACUCHO',
                'df_fecha' => '2026-12-09',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ],
            [
                'df_nombre' => 'NAVIDAD',
                'df_fecha' => '2026-12-25',
                'df_tipo' => 'NACIONAL',
                'df_repetitivo' => 1
            ]
        ];

        // Inserción masiva en la tabla de feriados
        $this->db->table('casis_diaferiado')->insertBatch($data);
    }
}
