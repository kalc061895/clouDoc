<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoOficinaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tofi_codigo' => '01',
                'tofi_nombre' => 'DIRECCION',
                'tofi_descripcion' => 'Órgano de dirección de más alto nivel encargado de la conducción estratégica de la entidad.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '02',
                'tofi_nombre' => 'OFICINA',
                'tofi_descripcion' => 'Órgano de asesoramiento, apoyo o de línea con un nivel estructural formal de segundo orden.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '03',
                'tofi_nombre' => 'DEPARTAMENTO',
                'tofi_descripcion' => 'Órgano de línea final y técnico-asistencial especializado en establecimientos hospitalarios.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '04',
                'tofi_nombre' => 'UNIDAD',
                'tofi_descripcion' => 'Subdivisión funcional técnica o administrativa dependiente de una Oficina o Departamento.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '05',
                'tofi_nombre' => 'AREA',
                'tofi_descripcion' => 'Subdivisión operativa o de control específica dentro de una Unidad o Servicio.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '06',
                'tofi_nombre' => 'SERVICIO',
                'tofi_descripcion' => 'Unidad operativa final dedicada a prestaciones específicas asistenciales o administrativas de ejecución.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'tofi_codigo' => '07',
                'tofi_nombre' => 'EQUIPO DE TRABAJO',
                'tofi_descripcion' => 'Agrupación funcional de servidores públicos conformada para cumplir tareas temporales o especializadas directas.',
                'tofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Inserción masiva usando Query Builder apuntando a tu tabla de Tipos de Oficina
        $this->db->table('casis_tipo_oficina')->insertBatch($data);
    }
}
