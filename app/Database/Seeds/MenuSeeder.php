<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Menús de CONFIGURACIÓN
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'Configuración',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-cog',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'Configuración'
                'name' => 'Usuarios',
                'abbr' => null,
                'url' => '/configuracion/usuarios',
                'icon' => 'fa-users',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'Configuración'
                'name' => 'Menús',
                'abbr' => null,
                'url' => '/configuracion/menus',
                'icon' => 'fa-bars',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'Configuración'
                'name' => 'Perfiles',
                'abbr' => null,
                'url' => '/configuracion/perfiles',
                'icon' => 'fa-id-badge',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'Configuración'
                'name' => 'Oficinas',
                'abbr' => null,
                'url' => '/configuracion/oficinas',
                'icon' => 'fa-building',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'Configuración'
                'name' => 'Documentos',
                'abbr' => null,
                'url' => '/configuracion/documentos',
                'icon' => 'fa-file-alt',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de EXPEDIENTE
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'EXPEDIENTE',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-folder',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Nuevo trámite',
                'abbr' => null,
                'url' => '/expediente/nuevo',
                'icon' => 'fa-plus-circle',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Para Recibir',
                'abbr' => null,
                'url' => '/expediente/para_recibir',
                'icon' => 'fa-inbox',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Enviados',
                'abbr' => null,
                'url' => '/expediente/enviados',
                'icon' => 'fa-paper-plane',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de DOCUMENTOS
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'DOCUMENTOS',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-file',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 11, // ID del menú 'DOCUMENTOS'
                'name' => 'Emisión de Documentos',
                'abbr' => null,
                'url' => '/documentos/emision',
                'icon' => 'fa-file-export',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 11, // ID del menú 'DOCUMENTOS'
                'name' => 'Recepción de Documentos',
                'abbr' => null,
                'url' => '/documentos/recepcion',
                'icon' => 'fa-file-import',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 11, // ID del menú 'DOCUMENTOS'
                'name' => 'Emisión Personal',
                'abbr' => null,
                'url' => '/documentos/emision_personal',
                'icon' => 'fa-user-edit',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de CONSULTAS
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'CONSULTAS',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-search',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Documentos Emitidos',
                'abbr' => null,
                'url' => '/consultas/emitidos',
                'icon' => 'fa-file-alt',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Documentos Recibidos',
                'abbr' => null,
                'url' => '/consultas/recibidos',
                'icon' => 'fa-file-invoice',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Documentos Personales',
                'abbr' => null,
                'url' => '/consultas/personales',
                'icon' => 'fa-user',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Seguimiento de Emitidos',
                'abbr' => null,
                'url' => '/consultas/seguimiento_emitidos',
                'icon' => 'fa-search-plus',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Seguimiento de Recibidos',
                'abbr' => null,
                'url' => '/consultas/seguimiento_recibidos',
                'icon' => 'fa-search-minus',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de MESA DE PARTES
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'MESA DE PARTES',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-deskpro',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 21, // ID del menú 'MESA DE PARTES'
                'name' => 'Para Despacho',
                'abbr' => null,
                'url' => '/mesa_de_partes/para_despacho',
                'icon' => 'fa-envelope-open',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 21, // ID del menú 'MESA DE PARTES'
                'name' => 'En Proyecto',
                'abbr' => null,
                'url' => '/mesa_de_partes/en_proyecto',
                'icon' => 'fa-project-diagram',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 21, // ID del menú 'MESA DE PARTES'
                'name' => 'No leídos',
                'abbr' => null,
                'url' => '/mesa_de_partes/no_leidos',
                'icon' => 'fa-envelope',
                'status' => 'active',
                'separator' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert the data into the 'menus' table
        $this->db->table('menus')->insertBatch($data);
    }
}