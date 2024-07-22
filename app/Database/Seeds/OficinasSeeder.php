<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OficinasSeeder extends Seeder
{
    public function run()
    {
        // Datos del organigrama
        $data = [
            [
                'nombre' => 'Dirección Ejecutiva de Red de Salud San Román',
                'tipo' => 'Oficina',
                'rango' => 1,
                'oficina_padre_id' => null,
            ],
            [
                'nombre' => 'Oficina de Control Interno',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Dirección de Administración',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Unidad Recursos Humanos',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Economía',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Logística',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Servicios Generales y Mantenimiento',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Control Patrimonial',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Estadística e Informática',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de Apoyo a la Docencia',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de Seguros',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de SISMED',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Oficina de Planeamiento Estratégico',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Oficina de Desarrollo Institucional',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Unidad de Epidemiología y Salud Ambiental',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Unidad Gestión de la Calidad',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Unidad de Asesoría Legal',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Microred Cono Sur',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Juliaca',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Santa Adriana',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Taraco',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Cabanillas',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Saman',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Trámite Documentario',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
        ];

        // Insertar los datos en la base de datos
        foreach ($data as $item) {
            $this->db->table('oficinas')->insert($item);
        }
    }
}
