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
                'abreviatura' => 'Dir. RED',
                'tipo' => 'Oficina',
                'rango' => 1,
                'oficina_padre_id' => null,
            ],
            [
                'nombre' => 'Oficina de Control Interno',
                'abreviatura' => 'OCI',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Dirección de Administración',
                'abreviatura' => 'Dir. Adm.',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Unidad Recursos Humanos',
                'abreviatura' => 'U. RRHH',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Economía',
                'abreviatura' => 'U. Econ.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Logística',
                'abreviatura' => 'U. Logi.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Servicios Generales y Mantenimiento',
                'abreviatura' => 'Serv. G.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Control Patrimonial',
                'abreviatura' => 'U. Patri.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad Estadística e Informática - RED',
                'abreviatura' => 'UEI Red',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de Apoyo a la Docencia',
                'abreviatura' => 'UADI',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de Seguros',
                'abreviatura' => 'U. Seguros',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Unidad de SISMED',
                'abreviatura' => 'SISMED',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 3,
            ],
            [
                'nombre' => 'Oficina de Planeamiento Estratégico',
                'abreviatura' => 'O. Planif.',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Oficina de Desarrollo Institucional',
                'abreviatura' => 'ODI',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Unidad de Epidemiología y Salud Ambiental',
                'abreviatura' => 'U. Epide.',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Unidad Gestión de la Calidad',
                'abreviatura' => 'U. Calidad',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Unidad de Asesoría Legal',
                'abreviatura' => 'U. Asesoría',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 14,
            ],
            [
                'nombre' => 'Microred Cono Sur',
                'abreviatura' => 'MR ConoSur',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Juliaca',
                'abreviatura' => 'MR Juliaca',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Santa Adriana',
                'abreviatura' => 'MR S.Adriana',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Taraco',
                'abreviatura' => 'MR Taraco',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Cabanillas',
                'abreviatura' => 'MR Cabanillas',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Microred Saman',
                'abreviatura' => 'MR Saman',
                'tipo' => 'Oficina',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Trámite Documentario',
                'abreviatura' => 'Trámite',
                'tipo' => 'Oficina',
                'rango' => 2,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Dirección Hospital Carlos MongeMedrano',
                'abreviatura' => 'Dir. HCMM',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Planillas y Remuneraciónes',
                'abreviatura' => 'Plan. y Remu.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Archivos y Legajos',
                'abreviatura' => 'A. y Legajos',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Selección y Contratación',
                'abreviatura' => 'Selección',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Escalafón',
                'abreviatura' => 'Escalafón',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Caja',
                'abreviatura' => 'Caja',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Control Interno',
                'abreviatura' => 'C. Interno',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Tesorería',
                'abreviatura' => 'Tesorería',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 10,
            ],
            [
                'nombre' => 'Patrimonio',
                'abreviatura' => 'Patrimonio',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Almacen',
                'abreviatura' => 'Almacen',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Programacion',
                'abreviatura' => 'Progamación',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Kardex',
                'abreviatura' => 'Kardex',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Adquisición',
                'abreviatura' => 'Adquisición',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Proceso y Selección',
                'abreviatura' => 'Proc. y Selec.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 6,
            ],
            [
                'nombre' => 'Unidad Estadística e Informática',
                'abreviatura' => 'UEI - HCMM',
                'tipo' => 'Área',
                'rango' => 25,
                'oficina_padre_id' => 1,
            ],
            [
                'nombre' => 'Salud Ambiental',
                'abreviatura' => 'S. Ambi.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
            [
                'nombre' => 'Zoonosis',
                'abreviatura' => 'Zoono.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
            [
                'nombre' => 'Epidemiologia',
                'abreviatura' => 'Epidemio.',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 15,
            ],
            [
                'nombre' => 'Control de Asistencia y Permanencia',
                'abreviatura' => 'ACAP',
                'tipo' => 'Área',
                'rango' => 3,
                'oficina_padre_id' => 4,
            ],
            [
                'nombre' => 'Departamento Médico',
                'abreviatura' => 'Dep. Médico',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Pediatría',
                'abreviatura' => 'Dep. Pedia.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Patología Clínica y Anatomía Patológica',
                'abreviatura' => 'D.PatCliyAnaPat',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Emergencia y Cuidados Críticos',
                'abreviatura' => 'Dep. Emergencia',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Gineco-Obstetricia',
                'abreviatura' => 'Dep.Gine-Obs',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Medicina',
                'abreviatura' => 'Dep. Médicina',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Enfermería',
                'abreviatura' => 'Dep. Enferm.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Cirugía',
                'abreviatura' => 'Dep. Cirug.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Medicina Interna',
                'abreviatura' => 'Dep. M. Inter.',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Salud Mental',
                'abreviatura' => 'S. Mental',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Servicio de Medicina de Especialidades',
                'abreviatura' => 'Ser. Especia.',
                'tipo' => 'Servicio',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Anestesiología y Centro Quirúrgico',
                'abreviatura' => 'Dep. Anestesio.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Diagnóstico por Imágenes',
                'abreviatura' => 'Diag. Imagen.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Odontología',
                'abreviatura' => 'Dep. Odonto.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Nutrición y Dietética',
                'abreviatura' => 'Dep. Nutric.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Farmacia',
                'abreviatura' => 'Dep. Farmac.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Servicio Social',
                'abreviatura' => 'D.Ser.Social',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Centro de Excelencia TBC',
                'abreviatura' => 'TBC',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Departamento de Medicina de Rehabilitación',
                'abreviatura' => 'Dep. Rehabil.',
                'tipo' => 'Departamento',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Banco de Sangre',
                'abreviatura' => 'Banc. Sangre',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 46,
            ],
            [
                'nombre' => 'Laboratorio',
                'abreviatura' => 'Laboratorio',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 46,
            ],
            [
                'nombre' => 'Relaciones Públicas - HCMM',
                'abreviatura' => 'RRPP-HCMM',
                'tipo' => 'Area',
                'rango' => 3,
                'oficina_padre_id' => 25,
            ],
            [
                'nombre' => 'Relaciones Públicas - RED',
                'abreviatura' => 'RRPP-RED',
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
