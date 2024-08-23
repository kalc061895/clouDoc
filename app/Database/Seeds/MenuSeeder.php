<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Menús de TRAMITE
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'TRAMITE',
                'abbr' => null,
                'url' => null,
                'icon' => 'fa-deskpro',
                'status' => 'active',
                'separator' => null,
                'order' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'TRAMITE'
                'name' => 'Para Recibir',
                'abbr' => null,
                'url' => '/mesa_de_partes/no_leidos',
                'icon' => 'eye-closed-bold',
                'status' => 'active',
                'separator' => null,
                'order' => 110,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'TRAMITE'
                'name' => 'Derivados',
                'abbr' => null,
                'url' => '/mesa_de_partes/para_despacho',
                'icon' => 'archive-up-outline',
                'status' => 'active',
                'separator' => null,
                'order' => 120,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'TRAMITE'
                'name' => 'Observados',
                'abbr' => null,
                'url' => '/mesa_de_partes/observados',
                'icon' => 'file-remove-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 130,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'TRAMITE'
                'name' => 'Atendidos',
                'abbr' => null,
                'url' => '/mesa_de_partes/atendidos',
                'icon' => 'archive-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 140,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 1, // ID del menú 'TRAMITE'
                'name' => 'Todos',
                'abbr' => null,
                'url' => '/mesa_de_partes/todos_expediente',
                'icon' => 'inbox-archive-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 150,
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
                'icon' => 'folder-open-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 210,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Para Recibir',
                'abbr' => null,
                'url' => '/expediente/para_recibir',
                'icon' => 'inbox-in-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 220,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Derivados',
                'abbr' => null,
                'url' => '/expediente/derivados',
                'icon' => 'move-to-folder-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 230,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Observados',
                'abbr' => null,
                'url' => '/expediente/observados',
                'icon' => 'folder-error-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 240,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 7, // ID del menú 'EXPEDIENTE'
                'name' => 'Todos',
                'abbr' => null,
                'url' => '/expediente/todos',
                'icon' => 'inbox-archive-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 250,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de DOCUMENTOS INTERNOS
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'DOCUMENTOS INTERNOS',
                'abbr' => null,
                'url' => null,
                'icon' => 'folder-path-connect-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 300,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 12, // ID del menú 'DOCUMENTOS INTERNOS'
                'name' => 'Nuevo Documento',
                'abbr' => null,
                'url' => '/documentos/nuevo',
                'icon' => 'add-folder-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 310,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 12, // ID del menú 'DOCUMENTOS INTERNOS'
                'name' => 'Emitidos',
                'abbr' => null,
                'url' => '/documentos/emitidos',
                'icon' => 'folder-cloud-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 320,
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
                'icon' => 'card-search-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 400,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Buscar Expediente',
                'abbr' => null,
                'url' => '/consultas/buscar',
                'icon' => 'card-search-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 420,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 15, // ID del menú 'CONSULTAS'
                'name' => 'Busqueda Avanzada',
                'abbr' => null,
                'url' => '/consultas/buscar_avanzada',
                'icon' => 'incognito-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 430,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // REPORTES
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'REPORTES',
                'abbr' => null,
                'url' => null,
                'icon' => null,
                'status' => 'active',
                'separator' => null,
                'order' => 500,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 18, // ID del menú 'REPORTES'
                'name' => 'Hoja de Registro',
                'abbr' => null,
                'url' => '/reportes/registro',
                'icon' => 'checklist-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 510,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 18, // ID del menú 'REPORTES'
                'name' => 'Hoja de Ruta',
                'abbr' => null,
                'url' => '/reportes/ruta',
                'icon' => 'route-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 520,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 18, // ID del menú 'REPORTES'
                'name' => 'Reporte Personalizado',
                'abbr' => null,
                'url' => '/reportes/personalizado',
                'icon' => 'chart-square-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 530,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 18, // ID del menú 'REPORTES'
                'name' => 'Estadísticas',
                'abbr' => null,
                'url' => '/reportes/estadisticas',
                'icon' => 'chart-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 540,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Menús de CONFIGURACIÓN
            [
                'type' => 'primary',
                'parent_id' => null,
                'name' => 'CONFIGURACIÓN',
                'abbr' => null,
                'url' => null,
                'icon' => '',
                'status' => 'active',
                'separator' => null,
                'order' => 600,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 23, // ID del menú 'Configuración'
                'name' => 'Usuarios',
                'abbr' => null,
                'url' => '/configuracion/usuarios',
                'icon' => 'users-group-rounded-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 610,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 23, // ID del menú 'Configuración'
                'name' => 'Menús',
                'abbr' => null,
                'url' => '/configuracion/menus',
                'icon' => 'hamburger-menu-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 620,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 23, // ID del menú 'Configuración'
                'name' => 'Perfiles',
                'abbr' => null,
                'url' => '/configuracion/perfiles',
                'icon' => 'user-id-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 630,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 23, // ID del menú 'Configuración'
                'name' => 'Oficinas',
                'abbr' => null,
                'url' => '/configuracion/oficinas',
                'icon' => 'buildings-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 640,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'type' => 'secondary',
                'parent_id' => 23, // ID del menú 'Configuración'
                'name' => 'Documentos',
                'abbr' => null,
                'url' => '/configuracion/documentos',
                'icon' => 'folder-with-files-linear',
                'status' => 'active',
                'separator' => null,
                'order' => 650,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

        ];

        // Insert the data into the 'menus' table
        $this->db->table('menus')->insertBatch($data);
    }
}
