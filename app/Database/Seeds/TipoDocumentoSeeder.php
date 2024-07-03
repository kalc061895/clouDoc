<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre' => 'DNI',
                'tamano' => 8,
                'activo' => true,
            ],
            [
                'nombre' => 'RUC',
                'tamano' => 11,
                'activo' => true,
            ],
            [
                'nombre' => 'Carnet de Extranjería',
                'tamano' => 20, // Puedes ajustar este valor según sea necesario
                'activo' => true,
            ],
            // Agrega más tipos de documentos según sea necesario
        ];

        // Insertar datos en la tabla 'tipo_documento'
        $this->db->table('tipo_documento')->insertBatch($data);
    }
}
