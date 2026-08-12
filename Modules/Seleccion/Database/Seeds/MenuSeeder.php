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
                'id'         => 500,
                'type'       => 'module',
                'parent_id'  => null,
                'name'       => 'CONTRATACIÓN Y SELECCIÓN',
                'abbr'       => 'CONTRATO',
                'url'        => '#',
                'icon'       => 'fas fa-briefcase',
                'order'      => 500,
                'status'     => 1,
                'separator'  => 1
            ],

            // ==========================================
            // 1. ÁREA ADMINISTRATIVA (ADMIN)
            // ==========================================
            [
                'id'         => 510,
                'type'       => 'folder',
                'parent_id'  => 500,
                'name'       => 'Administración',
                'abbr'       => 'ADM',
                'url'        => '#',
                'icon'       => 'fas fa-user-shield',
                'order'      => 510,
                'status'     => 1,
                'separator'  => 0
            ],
            // Submenús Admin
            [
                'id'         => 511,
                'type'       => 'item',
                'parent_id'  => 510,
                'name'       => 'Convocatorias',
                'abbr'       => 'CONV',
                'url'        => 'contrato/admin/convocatorias',
                'icon'       => 'fas fa-bullhorn',
                'order'      => 511,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 512,
                'type'       => 'item',
                'parent_id'  => 510,
                'name'       => 'Plazas',
                'abbr'       => 'PLZ',
                'url'        => 'contrato/admin/plazas',
                'icon'       => 'fas fa-chair',
                'order'      => 512,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 513,
                'type'       => 'item',
                'parent_id'  => 510,
                'name'       => 'Resumen Postulaciones',
                'abbr'       => 'POST-ADM',
                'url'        => 'contrato/admin/postulacion/resumen',
                'icon'       => 'fas fa-chart-pie',
                'order'      => 513,
                'status'     => 1,
                'separator'  => 0
            ],

            // ==========================================
            // 2. PORTAL DEL POSTULANTE
            // ==========================================
            [
                'id'         => 520,
                'type'       => 'folder',
                'parent_id'  => 500,
                'name'       => 'Portal Postulante',
                'abbr'       => 'POST',
                'url'        => '#',
                'icon'       => 'fas fa-user-edit',
                'order'      => 520,
                'status'     => 1,
                'separator'  => 0
            ],
            // Submenús Postulante
            [
                'id'         => 521,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Convocatorias Vigentes',
                'abbr'       => 'CONV-VIG',
                'url'        => 'contrato/postulante/convocatorias',
                'icon'       => 'fas fa-folder-open',
                'order'      => 521,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 522,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Mis Datos Personales',
                'abbr'       => 'DAT-PERS',
                'url'        => 'contrato/postulante/ver-datos',
                'icon'       => 'fas fa-id-card',
                'order'      => 522,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 523,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Formación Académica',
                'abbr'       => 'FORM',
                'url'        => 'contrato/postulante/formacion/listar',
                'icon'       => 'fas fa-graduation-cap',
                'order'      => 523,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 524,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Experiencia Laboral',
                'abbr'       => 'EXP',
                'url'        => 'contrato/postulante/experiencia/listar',
                'icon'       => 'fas fa-user-tie',
                'order'      => 524,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 525,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Capacitaciones',
                'abbr'       => 'CAP',
                'url'        => 'contrato/postulante/capacitaciones/listar',
                'icon'       => 'fas fa-certificate',
                'order'      => 525,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 526,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Información Extra',
                'abbr'       => 'EXTRA',
                'url'        => 'contrato/postulante/extra/listar',
                'icon'       => 'fas fa-plus-circle',
                'order'      => 526,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 527,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Plazas Disponibles',
                'abbr'       => 'PLZ-DISP',
                'url'        => 'contrato/postulante/listarplazas',
                'icon'       => 'fas fa-list-ol',
                'order'      => 527,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 528,
                'type'       => 'item',
                'parent_id'  => 520,
                'name'       => 'Mis Resultados',
                'abbr'       => 'RES',
                'url'        => 'contrato/postulante/resultado',
                'icon'       => 'fas fa-poll-h',
                'order'      => 528,
                'status'     => 1,
                'separator'  => 0
            ],

            // ==========================================
            // 3. COMISIÓN EVALUADORA
            // ==========================================
            [
                'id'         => 530,
                'type'       => 'folder',
                'parent_id'  => 500,
                'name'       => 'Comisión Evaluadora',
                'abbr'       => 'COM',
                'url'        => '#',
                'icon'       => 'fas fa-users-cog',
                'order'      => 530,
                'status'     => 1,
                'separator'  => 0
            ],
            // Submenús Comisión
            [
                'id'         => 531,
                'type'       => 'item',
                'parent_id'  => 530,
                'name'       => 'Evaluación de Postulantes',
                'abbr'       => 'EVAL',
                'url'        => 'contrato/comision/evaluacion',
                'icon'       => 'fas fa-clipboard-check',
                'order'      => 531,
                'status'     => 1,
                'separator'  => 0
            ],
            [
                'id'         => 532,
                'type'       => 'item',
                'parent_id'  => 530,
                'name'       => 'Reportes y Resultados',
                'abbr'       => 'REP-EVAL',
                'url'        => 'contrato/comision/reporte',
                'icon'       => 'fas fa-file-invoice',
                'order'      => 532,
                'status'     => 1,
                'separator'  => 0
            ],
        ];

        // Inserción en lote utilizando el Query Builder
        $this->db->table('menus')->insertBatch($data);
    }
}
