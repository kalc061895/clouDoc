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
                'nombre' => 'Unidad Estadística e Informática - RED',
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
                'nombre' => 'Unidad Estadística e Informática',
                'tipo' => 'Área',
                'rango' => 25,
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
            [
                'nombre' => 'Control de Asistencia y Permanencia',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Departamento Médico',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Pediatría',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Patología Clínica y Anatomía Patológica',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Emergencia y Cuidados Críticos',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Gineco-Obstetricia',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Medicina',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Enfermería',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Cirugía',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Medicina Interna',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Salud Mental',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Medicina de Especialidades',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Anestesiología y Centro Quirúrgico',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Diagnóstico por Imágenes',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Odontología',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Nutrición y Dietética',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Farmacia',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Servicio Social',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Centro de Excelencia TBC',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Medicina de Rehabilitación',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Banco de Sangre',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 46,
            ],
            [
                'nombre' => 'Laboratorio',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 46,
            ],
            [
                'nombre' => 'Relaciones Públicas - HCMM',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Relaciones Públicas - RED',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
        ];

        // Insertar los datos en la base de datos
        foreach ($data as $item) {
            $this->db->table('oficinas')->insert($item);
        }
    }
}
