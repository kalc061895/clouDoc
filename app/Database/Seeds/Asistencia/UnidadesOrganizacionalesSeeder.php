<?php

namespace App\Database\Seeds\Asistencia;

use CodeIgniter\Database\Seeder;

class UnidadesOrganizacionalesSeeder extends Seeder
{
    public function run()
    {

        // 1. Insertar la Unidad Ejecutora Raíz
        $ejecutora = [
            'uo_nombre'      => 'RED DE SALUD SAN ROMAN',
            'uo_abreviatura' => 'RSSR',
            'uo_nivel_tipo'  => 'EJECUTORA',
            'uo_padre_ide'   => null,
            'uo_estado'      => 1
        ];

        $this->db->table('casis_unidades_organizacionales')->insert($ejecutora);
        $padreId = $this->db->insertID(); // Obtenemos el ID de la Red de Salud

        // 2. Listado de Servicios enviado (mapeado a la estructura de la nueva tabla)
        $servicios = [
            ['uo_nombre' => 'SIN/SERVICIO', 'uo_abreviatura' => 'N/A', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'PEDIATRIA', 'uo_abreviatura' => 'PEDIATRIA', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'ENFERMERIA', 'uo_abreviatura' => 'ENFERMERIA', 'uo_nivel_tipo' => 'DEPARTAMENTO'],
            ['uo_nombre' => 'DERMATOLOGIA', 'uo_abreviatura' => 'DERMATO', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'GASTROENTEROLOGIA', 'uo_abreviatura' => 'GASTRO', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'GINECOLOGIA', 'uo_abreviatura' => 'GINECO', 'uo_nivel_tipo' => 'DEPARTAMENTO'],
            ['uo_nombre' => 'CENEX', 'uo_abreviatura' => 'CENEX', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'EMERGENCIA', 'uo_abreviatura' => 'EMERG', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'QUIROFANO', 'uo_abreviatura' => 'QUIR', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'SALUD AMBIENTAL', 'uo_abreviatura' => 'S.AMB', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'MEDICINA', 'uo_abreviatura' => 'MED', 'uo_nivel_tipo' => 'DEPARTAMENTO'],
            ['uo_nombre' => 'PSICOLOGIA', 'uo_abreviatura' => 'PSIC', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'SEGURIDAD Y VIGILANCIA', 'uo_abreviatura' => 'SEG', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'NUTRI. Y DIETETICA', 'uo_abreviatura' => 'NUTR', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'RAYOS X URGENCIAS', 'uo_abreviatura' => 'RX', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'LECTURA DE TOMOGRAFIA', 'uo_abreviatura' => 'TOMO', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'CASA FUERZA', 'uo_abreviatura' => 'C.FZ', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'MANTENIMIENTO', 'uo_abreviatura' => 'MANT', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'SERVICIOS GENERALES', 'uo_abreviatura' => 'SS.GG', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'TRANSPORTES PATIO', 'uo_abreviatura' => 'TRANS.P', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'ECON: CAJA EMERGENCIA', 'uo_abreviatura' => 'CAJA.E', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'FARMACIA', 'uo_abreviatura' => 'FARM', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'NEONATOLOGIA', 'uo_abreviatura' => 'NEO', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'HOSPITALIZACION', 'uo_abreviatura' => 'HOSP', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'SALUD DE LAS PERSONAS', 'uo_abreviatura' => 'S.PER', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'ZOONOSIS', 'uo_abreviatura' => 'ZOO', 'uo_nivel_tipo' => 'AREA'],
            ['uo_nombre' => 'ESTADISTICA E INFORMATICA', 'uo_abreviatura' => 'ESTAD', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'DIR. DE RED', 'uo_abreviatura' => 'DIR.R', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'DIR. DE HOSPITAL', 'uo_abreviatura' => 'DIR.H', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'SALUD MENTAL', 'uo_abreviatura' => 'S.MEN', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'UCI', 'uo_abreviatura' => 'UCI', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'LABORATORIO CENTRAL', 'uo_abreviatura' => 'LAB', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'BANCO DE SANGRE', 'uo_abreviatura' => 'B.SAN', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'CENTRO OBSTETRICO', 'uo_abreviatura' => 'C.OBS', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'ODONTOESTOMATOLOGIA', 'uo_abreviatura' => 'ODONTO', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'CENTRAL ESTERILIZACION', 'uo_abreviatura' => 'C.EST', 'uo_nivel_tipo' => 'SERVICIO'],
            ['uo_nombre' => 'RECURSOS HUMANOS', 'uo_abreviatura' => 'RR.HH', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'PLANILLAS', 'uo_abreviatura' => 'PLAN', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'CONTROL DE ASISTENCIA', 'uo_abreviatura' => 'C.ASI', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'LOGISTICA', 'uo_abreviatura' => 'LOG', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'PLANIFICACION', 'uo_abreviatura' => 'PLANIF', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'ASESORIA JURIDICA', 'uo_abreviatura' => 'A.JUR', 'uo_nivel_tipo' => 'OFICINA'],
            ['uo_nombre' => 'CARDIOLOGIA', 'uo_abreviatura' => 'CARD', 'uo_nivel_tipo' => 'SERVICIO'],
        ];

        // 3. Preparar datos para inserción masiva
        $dataInsert = [];
        foreach ($servicios as $s) {
            $dataInsert[] = [
                'uo_padre_ide'   => $padreId,
                'uo_nivel_tipo'  => $s['uo_nivel_tipo'],
                'uo_nombre'      => $s['uo_nombre'],
                'uo_abreviatura' => $s['uo_abreviatura'],
                'uo_estado'      => 1,
                'uo_fecha_registro' => date('Y-m-d H:i:s')
            ];
        }

        $this->db->table('casis_unidades_organizacionales')->insertBatch($dataInsert);
    }
}
