<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TupaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'numero_orden' => 1,
                'denominacion_procedimiento' => 'COPIA DE HISTORIA CLINICA',
                'derecho_admision_uit' => 0.02,
                'derecho_admision_soles' => 20,
                'calificacion' => 'Unidad de Estadistica',
                'plazo' => 5,
                'oficina_inicio' => 'Unidad de Estadistica e informatica',
                'oficina_competencia' => 'Unidad de Archivos Cllinicos',
                'oficina_reconsideracion' => 'Unidad de Estadistica e Informatico',
                'oficina_apelacion' => 'Direccion de Hospital',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'numero_orden' => 10,
                'denominacion_procedimiento' => 'CONSTANCIA DE INTERNADO',
                'derecho_admision_uit' => 0,
                'derecho_admision_soles' => 0,
                'calificacion' => '',
                'plazo' => 0,
                'oficina_inicio' => 'UADI',
                'oficina_competencia' => '',
                'oficina_reconsideracion' => '',
                'oficina_apelacion' => '',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'numero_orden' => 11,
                'denominacion_procedimiento' => 'APLICACION DE PROYECTO DE TESIS',
                'derecho_admision_uit' => 0,
                'derecho_admision_soles' => 0,
                'calificacion' => '',
                'plazo' => 0,
                'oficina_inicio' => 'UADI',
                'oficina_competencia' => '',
                'oficina_reconsideracion' => '',
                'oficina_apelacion' => '',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 4,
                'numero_orden' => 12,
                'denominacion_procedimiento' => 'REALIZACION DE PRACTICAS PROFESIONALES',
                'derecho_admision_uit' => 0,
                'derecho_admision_soles' => 0,
                'calificacion' => '',
                'plazo' => 0,
                'oficina_inicio' => 'UADI',
                'oficina_competencia' => '',
                'oficina_reconsideracion' => '',
                'oficina_apelacion' => '',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 5,
                'numero_orden' => 13,
                'denominacion_procedimiento' => 'CONSTANCIA DE TERMINO DE PRACTICAS PROFESIONALES',
                'derecho_admision_uit' => 0,
                'derecho_admision_soles' => 0,
                'calificacion' => '',
                'plazo' => 0,
                'oficina_inicio' => 'UADI',
                'oficina_competencia' => '',
                'oficina_reconsideracion' => '',
                'oficina_apelacion' => '',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 6,
                'numero_orden' => 2,
                'denominacion_procedimiento' => 'CONSTANCIA DE TRASLADO DE CADAVER',
                'derecho_admision_uit' => 0,
                'derecho_admision_soles' => 0,
                'calificacion' => '',
                'plazo' => 0,
                'oficina_inicio' => 'EPIDEMIOLOGIA',
                'oficina_competencia' => '',
                'oficina_reconsideracion' => '',
                'oficina_apelacion' => '',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
        ];

        $this->db->table('tupa')->insertBatch($data);
    }
}
