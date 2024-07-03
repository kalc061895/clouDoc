<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpresaConfiguracionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre_empresa' => 'Nombre de la Empresa',
                'documento_empresa' => 'RUC 12345678901', // Ejemplo de documento de la empresa
                'nombre_director' => 'Juan Pérez',
                'direccion' => 'Calle Principal 123, Ciudad',
                'telefono' => '123-456-7890',
                'horarios_atencion' => 'Lunes a Viernes: 8:00 AM - 5:00 PM',
                'logo' => 'logo.png', // Nombre del archivo de logo en tu sistema
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Agrega más registros de configuración si es necesario
        ];

        // Inserta los datos en la tabla empresa_configuracion
        $this->db->table('empresa_configuracion')->insertBatch($data);
    }
}
