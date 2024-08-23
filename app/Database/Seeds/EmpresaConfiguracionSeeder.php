<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpresaConfiguracionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'key' => 'nombre_empresa',
                'value' => 'Nombre de la Empresa'
            ],
            [
                'key' => 'documento_empresa',
                'value' => 'RUC 12345678901'
            ],
            [
                'key' => 'nombre_director',
                'value' => 'Juan Pérez'
            ],
            [
                'key' => 'direccion',
                'value' => 'Calle Principal 123, Ciudad'
            ],
            [
                'key' => 'telefono',
                'value' => '123-456-7890'
            ],
            [
                'key' => 'horarios_atencion',
                'value' => 'Lunes a Viernes: 8:00 AM - 5:00 PM'
            ],
            [
                'key' => 'logo',
                'value' => 'logo.png'
            ],
            [
                'key' => 'google_drive',
                'value' => true
            ],
            [
                'key' => 'google_drive_folder',
                'value' => '15WeczEPwYK534xeyX3BOswRsjBLl67G0'
            ]
            // Agrega más registros de configuración si es necesario
        ];

        // Inserta los datos en la tabla empresa_configuracion
        $this->db->table('empresa_configuracion')->insertBatch($data);
    }
}
