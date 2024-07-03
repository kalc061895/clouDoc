<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoExpedienteSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre' => 'Solicitud',
                'descripcion' => 'Documento que solicita algo específico.',
            ],
            [
                'nombre' => 'Carta',
                'descripcion' => 'Documento formal de comunicación escrita.',
            ],
            [
                'nombre' => 'Oficio',
                'descripcion' => 'Documento oficial emitido por una autoridad.',
            ],
            [
                'nombre' => 'Informe',
                'descripcion' => 'Documento que presenta información detallada sobre un tema específico.',
            ],
            [
                'nombre' => 'Memorandum',
                'descripcion' => 'Documento que remite una jefatura a empleado.',
            ],
            // Agrega más tipos de expediente según sea necesario
        ];

        // Inserta los datos en la tabla tipo_expediente
        $this->db->table('tipo_expediente')->insertBatch($data);
    }
}
