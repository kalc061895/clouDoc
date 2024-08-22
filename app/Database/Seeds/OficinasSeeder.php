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
            [
                'nombre' => 'Dirección Hospital Carlos MongeMedrano',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Planillas y Remuneraciónes',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Archivos y Legajos',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Selección y Contratación',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Escalafon',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Caja',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Control Interno',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Tesorería',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Patrimonio',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Almacen',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Programacion',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Kardex',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Adquisición',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Proceso y Selección',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Unidad Estadística e Informática - RSSR',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Salud Ambiental',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
            [
                'nombre' => 'Zoonosis',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
            [
                'nombre' => 'Epidemiologia',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
        ];

        // Insertar los datos en la base de datos
        foreach ($data as $item) {
            $this->db->table('oficinas')->insert($item);
        }
    }
}
