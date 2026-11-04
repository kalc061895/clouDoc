<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    //public string $defaultGroup = 'user';
    public string $defaultGroup = 'postulante';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Control completo de las configuracion.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Gestión de oficinas, usuarios, areas, y otros ',
        ],
        'tramite' => [
            'title'       => 'Trámite Documentario',
            'description' => 'Recepción y derivación de documentos recepcionados a nivel externo e interno',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'Usuario Normal',
        ],
        'oficina' => [
            'title'       => 'Encargados de Oficina',
            'description' => 'Gestión de Documentos Derivados a la Oficina',
        ],
        'area' => [
            'title'       => 'Encargados de Areas de Oficinas',
            'description' => 'Gestion de Documentos derivados por jefaturas',
        ],
        'asistencia' => [
            'title'       => 'Encargado del Area de Control y asistencia y permanencia',
            'description' => 'Gestion de Asistencia y permanencia de los trabajadores',
        ],
        'asi_sua' => [
            'title' => 'Asistencia - Superadministrador',
            'description' => 'Gestión integral y catálogos de Asistencia. No equivale al superadmin global.',
        ],
        'asi_adm' => [
            'title' => 'Asistencia - Administrador',
            'description' => 'Gestión operativa de personal, turnos, marcaciones, roles y reportes.',
        ],
        'asi_apo' => [
            'title' => 'Asistencia - Apoyo',
            'description' => 'Apoyo en personal, gestor, marcaciones, historial de roles y reportes.',
        ],
        'asi_dm' => ['title' => 'Asistencia - Director del módulo', 'description' => 'Acceso superadministrador de Asistencia.'],
        'asi_apoyo' => ['title' => 'Asistencia - Apoyo global', 'description' => 'Apoyo global con restricciones de administración y catálogos.'],
        'asi_diresa' => ['title' => 'Asistencia - DIRESA', 'description' => 'Gestión de Asistencia limitada a su DIRESA.'],
        'asi_diresa_apoyo' => ['title' => 'Asistencia - Apoyo DIRESA', 'description' => 'Operación de Asistencia con restricciones en su DIRESA.'],
        'asi_diresa_registro' => ['title' => 'Asistencia - Registro DIRESA', 'description' => 'Consulta y registro de personal de su DIRESA.'],
        'asi_diresa_consulta' => ['title' => 'Asistencia - Consulta DIRESA', 'description' => 'Consulta de información de su DIRESA.'],
        'asi_red' => ['title' => 'Asistencia - Red', 'description' => 'Gestión de Asistencia limitada a su red.'],
        'asi_red_apoyo' => ['title' => 'Asistencia - Apoyo Red', 'description' => 'Operación de Asistencia con restricciones en su red.'],
        'asi_red_registro' => ['title' => 'Asistencia - Registro Red', 'description' => 'Consulta y registro de personal de su red.'],
        'asi_red_consulta' => ['title' => 'Asistencia - Consulta Red', 'description' => 'Consulta de información de su red.'],
        'asi_mred' => ['title' => 'Asistencia - Microred', 'description' => 'Gestión de Asistencia limitada a su microred.'],
        'asi_mred_apoyo' => ['title' => 'Asistencia - Apoyo Microred', 'description' => 'Operación de Asistencia con restricciones en su microred.'],
        'asi_mred_registro' => ['title' => 'Asistencia - Registro Microred', 'description' => 'Consulta y registro de personal de su microred.'],
        'asi_mred_consulta' => ['title' => 'Asistencia - Consulta Microred', 'description' => 'Consulta de información de su microred.'],
        'asi_est' => ['title' => 'Asistencia - Establecimiento', 'description' => 'Gestión de Asistencia limitada a su establecimiento.'],
        'asi_est_apoyo' => ['title' => 'Asistencia - Apoyo Establecimiento', 'description' => 'Operación de Asistencia con restricciones en su establecimiento.'],
        'asi_est_registro' => ['title' => 'Asistencia - Registro Establecimiento', 'description' => 'Consulta y registro de personal de su establecimiento.'],
        'asi_est_consulta' => ['title' => 'Asistencia - Consulta Establecimiento', 'description' => 'Consulta de información de su establecimiento.'],
        'asi_auditoria' => ['title' => 'Asistencia - Auditoría', 'description' => 'Consulta global de reportes, eventos, accesos y registros de auditoría.'],
        'postulante' => [
            'title'       => 'Postulante',
            'description' => 'Usuario que participa en una convocatoria',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'asi_sua' => [],
        'asi_adm' => [],
        'asi_apo' => [],
        'asi_dm' => [],
        'asi_apoyo' => [],
        'asi_diresa' => [],
        'asi_diresa_apoyo' => [],
        'asi_diresa_registro' => [],
        'asi_diresa_consulta' => [],
        'asi_red' => [],
        'asi_red_apoyo' => [],
        'asi_red_registro' => [],
        'asi_red_consulta' => [],
        'asi_mred' => [],
        'asi_mred_apoyo' => [],
        'asi_mred_registro' => [],
        'asi_mred_consulta' => [],
        'asi_est' => [],
        'asi_est_apoyo' => [],
        'asi_est_registro' => [],
        'asi_est_consulta' => [],
        'asi_auditoria' => [],
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],
        'user' => [],
        'postulante' => [],
        'beta' => [
            'beta.access',
        ],
    ];
}
