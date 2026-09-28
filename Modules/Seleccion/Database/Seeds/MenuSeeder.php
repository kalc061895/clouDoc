<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ==========================================
            // MÓDULO PRINCIPAL / SECCIÓN
            // ==========================================
            [
                'id'        => 500,
                'type'      => 'module',
                'parent_id' => null,
                'name'      => 'CONTRATACIÓN Y SELECCIÓN',
                'abbr'      => 'CONTRATO',
                'url'       => '#',
                'icon'      => 'fas fa-briefcase',
                'order'     => 500,
                'status'    => 1,
                'separator' => 1
            ],

            // ==========================================
            // 1. ÁREA ADMINISTRATIVA (ADMIN)
            // ==========================================
            [
                'id'        => 510,
                'type'      => 'folder',
                'parent_id' => 500,
                'name'      => 'Administración',
                'abbr'      => 'ADM',
                'url'       => '#',
                'icon'      => 'fas fa-user-shield',
                'order'     => 510,
                'status'    => 1,
                'separator' => 0
            ],
            // Submenús Admin
            [
                'id'        => 511,
                'type'      => 'item',
                'parent_id' => 510,
                'name'      => 'Convocatorias',
                'abbr'      => 'CONV',
                'url'       => 'seleccion/admin/convocatorias',
                'icon'      => 'fas fa-bullhorn',
                'order'     => 511,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 512,
                'type'      => 'item',
                'parent_id' => 510,
                'name'      => 'Plazas',
                'abbr'      => 'PLZ',
                'url'       => 'seleccion/admin/plazas',
                'icon'      => 'fas fa-chair',
                'order'     => 512,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 513,
                'type'      => 'item',
                'parent_id' => 510,
                'name'      => 'Resumen Postulaciones',
                'abbr'      => 'POST-ADM',
                'url'       => 'seleccion/admin/postulacion/resumen',
                'icon'      => 'fas fa-chart-pie',
                'order'     => 513,
                'status'    => 1,
                'separator' => 0
            ],

            // ------------------------------------------
            // 1.1 CONFIGURACIÓN Y TABLAS MAESTRAS (GESTIÓN)
            // ------------------------------------------
            [
                'id'        => 540,
                'type'      => 'folder',
                'parent_id' => 510,
                'name'      => 'Configuración Mantenedores',
                'abbr'      => 'CONF-GEST',
                'url'       => '#',
                'icon'      => 'fas fa-cogs',
                'order'     => 540,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 541,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Convocatoria',
                'abbr'      => 'TP-CONV',
                'url'       => 'seleccion/gestion/tipos-convocatoria',
                'icon'      => 'fas fa-tags',
                'order'     => 541,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 542,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Estados de Convocatoria',
                'abbr'      => 'EST-CONV',
                'url'       => 'seleccion/gestion/estados-convocatoria',
                'icon'      => 'fas fa-toggle-on',
                'order'     => 542,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 543,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Cargo',
                'abbr'      => 'TP-CARG',
                'url'       => 'seleccion/gestion/tipos-cargo',
                'icon'      => 'fas fa-user-tag',
                'order'     => 543,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 544,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Grupos Ocupacionales',
                'abbr'      => 'GRP-OCUP',
                'url'       => 'seleccion/gestion/grupos-ocupacionales',
                'icon'      => 'fas fa-users',
                'order'     => 544,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 545,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Niveles',
                'abbr'      => 'NIV',
                'url'       => 'seleccion/gestion/niveles',
                'icon'      => 'fas fa-layer-group',
                'order'     => 545,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 546,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Modalidades de Vínculo',
                'abbr'      => 'MOD-VINC',
                'url'       => 'seleccion/gestion/modalidades-vinculo',
                'icon'      => 'fas fa-file-contract',
                'order'     => 546,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 547,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Profesiones',
                'abbr'      => 'PROF',
                'url'       => 'seleccion/gestion/profesiones',
                'icon'      => 'fas fa-user-graduate',
                'order'     => 547,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 548,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Niveles de Formación',
                'abbr'      => 'NIV-FORM',
                'url'       => 'seleccion/gestion/niveles-formacion',
                'icon'      => 'fas fa-award',
                'order'     => 548,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 549,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Documento',
                'abbr'      => 'TP-DOC',
                'url'       => 'seleccion/gestion/tipos-documento',
                'icon'      => 'fas fa-address-card',
                'order'     => 549,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 550,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Estados de Postulación',
                'abbr'      => 'EST-POST',
                'url'       => 'seleccion/gestion/estados-postulacion',
                'icon'      => 'fas fa-tasks',
                'order'     => 550,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 551,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Estados de Expediente',
                'abbr'      => 'EST-EXP',
                'url'       => 'seleccion/gestion/estados-expediente',
                'icon'      => 'fas fa-folder',
                'order'     => 551,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 552,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Archivo',
                'abbr'      => 'TP-ARCH',
                'url'       => 'seleccion/gestion/tipos-archivo',
                'icon'      => 'fas fa-file-alt',
                'order'     => 552,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 553,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Etapas de Selección',
                'abbr'      => 'ETAPAS',
                'url'       => 'seleccion/gestion/etapas',
                'icon'      => 'fas fa-stream',
                'order'     => 553,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 554,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Bonificación',
                'abbr'      => 'TP-BONIF',
                'url'       => 'seleccion/gestion/tipos-bonificacion',
                'icon'      => 'fas fa-percent',
                'order'     => 554,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 555,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Notificación',
                'abbr'      => 'TP-NOTIF',
                'url'       => 'seleccion/gestion/tipos-notificacion',
                'icon'      => 'fas fa-bell',
                'order'     => 555,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 556,
                'type'      => 'item',
                'parent_id' => 540,
                'name'      => 'Tipos de Declaración',
                'abbr'      => 'TP-DECL',
                'url'       => 'seleccion/gestion/tipos-declaracion',
                'icon'      => 'fas fa-file-signature',
                'order'     => 556,
                'status'    => 1,
                'separator' => 0
            ],

            // ==========================================
            // 2. PORTAL DEL POSTULANTE
            // ==========================================
            [
                'id'        => 520,
                'type'      => 'folder',
                'parent_id' => null,
                'name'      => 'Portal Postulante',
                'abbr'      => 'POST',
                'url'       => '#',
                'icon'      => 'fas fa-user-edit',
                'order'     => 520,
                'status'    => 1,
                'separator' => 0
            ],
            // Submenús Postulante
            [
                'id'        => 521,
                'type'      => 'item',
                'parent_id' => null,
                'name'      => 'Convocatorias Vigentes',
                'abbr'      => 'CONV-VIG',
                'url'       => 'seleccion/postulacion',
                'icon'      => 'fas fa-folder-open',
                'order'     => 521,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 522,
                'type'      => 'item',
                'parent_id' => 520,
                'name'      => 'Mis Reclamos',
                'abbr'      => 'DAT-PERS',
                'url'       => 'seleccion/postulante/reclamos',
                'icon'      => 'fas fa-id-card',
                'order'     => 522,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 523,
                'type'      => 'item',
                'parent_id' => 520,
                'name'      => 'Constancia de Potulacion',
                'abbr'      => 'FORM',
                'url'       => 'seleccion/postulante/constancia',
                'icon'      => 'fas fa-graduation-cap',
                'order'     => 523,
                'status'    => 1,
                'separator' => 0
            ],
            
            [
                'id'        => 527,
                'type'      => 'item',
                'parent_id' => 520,
                'name'      => 'Plazas Disponibles',
                'abbr'      => 'PLZ-DISP',
                'url'       => 'seleccion/postulante/listarplazas',
                'icon'      => 'fas fa-list-ol',
                'order'     => 527,
                'status'    => 1,
                'separator' => 0
            ],

            [
                'id'        => 528,
                'type'      => 'item',
                'parent_id' => 520,
                'name'      => 'Mis Resultados',
                'abbr'      => 'RES',
                'url'       => 'seleccion/postulante/resultado',
                'icon'      => 'fas fa-poll-h',
                'order'     => 528,
                'status'    => 1,
                'separator' => 0
            ],

            // ==========================================
            // 3. COMISIÓN EVALUADORA
            // ==========================================
            [
                'id'        => 530,
                'type'      => 'folder',
                'parent_id' => 500,
                'name'      => 'Comisión Evaluadora',
                'abbr'      => 'COM',
                'url'       => '#',
                'icon'      => 'fas fa-users-cog',
                'order'     => 530,
                'status'    => 1,
                'separator' => 0
            ],
            // Submenús Comisión
            [
                'id'        => 531,
                'type'      => 'item',
                'parent_id' => 530,
                'name'      => 'Evaluación de Postulantes',
                'abbr'      => 'EVAL',
                'url'       => 'seleccion/comision/evaluacion',
                'icon'      => 'fas fa-clipboard-check',
                'order'     => 531,
                'status'    => 1,
                'separator' => 0
            ],
            [
                'id'        => 532,
                'type'      => 'item',
                'parent_id' => 530,
                'name'      => 'Reportes y Resultados',
                'abbr'      => 'REP-EVAL',
                'url'       => 'seleccion/comision/reporte',
                'icon'      => 'fas fa-file-invoice',
                'order'     => 532,
                'status'    => 1,
                'separator' => 0
            ],
        ];

        // Inserción en lote utilizando el Query Builder
        $this->db->table('menus')->insertBatch($data);
    }
}