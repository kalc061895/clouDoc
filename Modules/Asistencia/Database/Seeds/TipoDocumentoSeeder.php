<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tdi_nombre'       => 'Documento Nacional de Identidad',
                'tdi_abreviatura'  => 'DNI',
                'tdi_longitud'     => 8,
                'tdi_estado'       => 1,
            ],
            [
                'tdi_nombre'       => 'Carné de Extranjería',
                'tdi_abreviatura'  => 'CE',
                'tdi_longitud'     => 12,
                'tdi_estado'       => 1,
            ],
            [
                'tdi_nombre'       => 'Pasaporte',
                'tdi_abreviatura'  => 'PAS',
                'tdi_longitud'     => 20,
                'tdi_estado'       => 1,
            ],
            [
                'tdi_nombre'       => 'Permiso Temporal de Permanencia',
                'tdi_abreviatura'  => 'PTP',
                'tdi_longitud'     => 15,
                'tdi_estado'       => 1,
            ],
            [
                'tdi_nombre'       => 'Carné de Solicitud de Refugio',
                'tdi_abreviatura'  => 'CSR',
                'tdi_longitud'     => 15,
                'tdi_estado'       => 1,
            ],
        ];

        $this->db->table('casis_tipo_documento_identidad')
            ->insertBatch($data);
    }
}
