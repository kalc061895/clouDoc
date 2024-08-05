<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoExpedienteSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre' => 'SOLICITUD',
                'descripcion' => 'Documento que solicita algo específico.',
            ],
            [
                'nombre' => 'CARTA',
                'descripcion' => 'Documento formal de comunicación escrita.',
            ],
            [
                'nombre' => 'OFICIO',
                'descripcion' => 'Documento oficial emitido por una autoridad.',
            ],
            [
                'nombre' => 'INFORME',
                'descripcion' => 'Documento que presenta información detallada sobre un tema específico.',
            ],
            [
                'nombre' => 'MEMORANDUM',
                'descripcion' => 'Documento que remite una jefatura a empleado.',
            ],
            ['ACTA DE REUNION', ''],
            ['CARTA CIRCULAR', ''],
            ['CARTA DE DISPENZA', ''],
            ['CARTA DE GARANTIA', ''],
            ['CARTA DE RENUNCIA', ''],
            ['CARTA GARANTIA ', ''],
            ['CARTA MULTIPLE', ''],
            ['CARTA NOTARIAL', ''],
            ['CARTA PODRE', ''],
            ['CARTA RENUNCIA', ''],
            ['CASO ', ''],
            ['CEDULA DE NOTIFICACION ', ''],
            ['CREDENCIAL', ''],
            ['DESCARGO DE INFORME', ''],
            ['DOCUMENTO ', ''],
            ['ESCRITO ', ''],
            ['EXHORTACION ', ''],
            ['INFORME LEGAL', ''],
            ['INFORME MEDICO', ''],
            ['INFORME PSIQUIATRICO', ''],
            ['MEMORANDUM', ''],
            ['MEMORANDUM MULTIPLE', ''],
            ['MEMORIAL ', ''],
            ['NOTIFICACION ', ''],
            ['OFICIO CIRCULAR ', ''],
            ['OFICIO MULTIPLE ', ''],
            ['PRONUNCIAMIENTO ', ''],
            ['PROVEIDO ', ''],
            ['PROVEIDO LEGAL', ''],
            ['RESOLUCION JEFATURAL ', ''],
            ['SOBRE CERRADO', ''],
            ['SUMILLA', ''],
            // Agrega más tipos de expediente según sea necesario
        ];

        // Inserta los datos en la tabla tipo_expediente
        $this->db->table('tipo_expediente')->insertBatch($data);
    }
}
