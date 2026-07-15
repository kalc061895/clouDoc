<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('menus');

        // Desactivar temporalmente las llaves foráneas para evitar problemas al limpiar la tabla
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $builder->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 1. Definición de los Agrupadores / Separadores Principales (Padres)
        // Usamos una clave temporal como llave del array para asociar los hijos fácilmente
        $agrupadores = [
            'CONFIGURACION' => [
                'name'      => 'CONFIGURACIÓN',
                'type'      => 'separator',
                'icon'      => 'cogs',
                'order'     => 10,
                'separator' => 'Gestión del Sistema',
            ],
            'PERSONAL' => [
                'name'      => 'PERSONAL',
                'type'      => 'separator',
                'icon'      => 'users',
                'order'     => 20,
                'separator' => 'Recursos Humanos',
            ],
            'ROL_MENSUAL' => [
                'name'      => 'ROL MENSUAL',
                'type'      => 'separator',
                'icon'      => 'calendar-alt',
                'order'     => 30,
                'separator' => 'Planificación',
            ],
            'ROL_DE_TURNOS' => [
                'name'      => 'ROL DE TURNOS',
                'type'      => 'separator',
                'icon'      => 'calendar-minus',
                'order'     => 40,
                'separator' => 'Turnos del Usuario',
            ],
            'ASISTENCIA' => [
                'name'      => 'ASISTENCIA',
                'type'      => 'separator',
                'icon'      => 'calendar-check',
                'order'     => 50,
                'separator' => 'Control de Asistencias',
            ],
            'DISPOSITIVO' => [
                'name'      => 'DISPOSITIVO',
                'type'      => 'separator',
                'icon'      => 'hdd',
                'order'     => 60,
                'separator' => 'Biométricos',
            ],
            'REPORTES' => [
                'name'      => 'REPORTES',
                'type'      => 'separator',
                'icon'      => 'chart-bar',
                'order'     => 70,
                'separator' => 'Informes y Calificaciones',
            ],
            'PERFIL' => [
                'name'      => 'PERFIL',
                'type'      => 'separator',
                'icon'      => 'user-circle',
                'order'     => 80,
                'separator' => 'Mi Perfil',
            ],
            'CAMBIOS' => [
                'name'      => 'CAMBIOS',
                'type'      => 'separator',
                'icon'      => 'exchange-alt',
                'order'     => 90,
                'separator' => 'Trámites',
            ],
            'VACACIONES' => [
                'name'      => 'VACACIONES',
                'type'      => 'separator',
                'icon'      => 'umbrella-beach',
                'order'     => 100,
                'separator' => 'Descansos',
            ],
            'RED_DE_SALUD' => [
                'name'      => 'RED DE SALUD',
                'type'      => 'separator',
                'icon'      => 'hospital-symbol',
                'order'     => 110,
                'separator' => 'Red San Román',
            ],
            'BASE_DE_DATOS' => [
                'name'      => 'BASE DE DATOS',
                'type'      => 'separator',
                'icon'      => 'database',
                'order'     => 120,
                'separator' => 'Mantenimiento DB',
            ],
            'MARCAR_ASISTENCIA' => [
                'name'      => 'MARCAR ASISTENCIA',
                'type'      => 'separator',
                'icon'      => 'fingerprint',
                'order'     => 130,
                'separator' => 'Registro Remoto',
            ],
            'RELOJ' => [
                'name'      => 'RELOJ',
                'type'      => 'separator',
                'icon'      => 'clock',
                'order'     => 140,
                'separator' => 'Marcación Local',
            ],
            'KARDEX' => [
                'name'      => 'KARDEX',
                'type'      => 'separator',
                'icon'      => 'archive',
                'order'     => 150,
                'separator' => 'Historial Laboral',
            ],
            'ASISTENCIA_POR_PUESTO' => [
                'name'      => 'ASISTENCIA POR PUESTO',
                'type'      => 'separator',
                'icon'      => 'map-marker-alt',
                'order'     => 160,
                'separator' => 'Monitoreo Periférico',
            ],
            'ESTABLECIMIENTOS' => [
                'name'      => 'ESTABLECIMIENTOS',
                'type'      => 'separator',
                'icon'      => 'map-marked-alt',
                'order'     => 170,
                'separator' => 'Georreferenciación',
            ],
            'CALIFICACION' => [
                'name'      => 'CALIFICACIÓN',
                'type'      => 'separator',
                'icon'      => 'medal',
                'order'     => 180,
                'separator' => 'Calificaciones',
            ],
        ];

        // Insertar los padres y guardar sus IDs asignados en la base de datos
        $padresIds = [];
        foreach ($agrupadores as $key => $data) {
            $builder->insert($data);
            $padresIds[$key] = $db->insertID();
        }

        // 2. Definición de los Submenús (Hijos)
        // Mapeamos el 'tar_menu' original de CI3 al padre correspondiente creado arriba
        $submenus = [
            // CONFIGURACION
            ['parent' => 'CONFIGURACION', 'name' => 'Configurar Sistema', 'url' => 'configuracion/config', 'icon' => 'cogs', 'order' => 1],
            ['parent' => 'CONFIGURACION', 'name' => 'Ver Personal (Admin)', 'url' => 'administrador/personal', 'icon' => 'users', 'order' => 2],
            ['parent' => 'CONFIGURACION', 'name' => 'Aspecto', 'url' => 'configuracion/aspecto', 'icon' => 'palette', 'order' => 3],

            // PERSONAL
            ['parent' => 'PERSONAL', 'name' => 'Ver Planilla', 'url' => 'administrador/planilla', 'icon' => 'users', 'order' => 1],
            ['parent' => 'PERSONAL', 'name' => 'Nuevo Personal', 'url' => 'administrador/nuevo_personal', 'icon' => 'user-plus', 'order' => 2],
            ['parent' => 'PERSONAL', 'name' => 'Interno y Residente', 'url' => 'administrador/planilla_capacitacion', 'icon' => 'user-graduate', 'order' => 3],
            ['parent' => 'PERSONAL', 'name' => 'Ver Personal', 'url' => 'administrador/planilla_observacion', 'icon' => 'eye', 'order' => 4],
            ['parent' => 'PERSONAL', 'name' => 'Personal MicroRed', 'url' => 'administrador/planilla_microred', 'icon' => 'users', 'order' => 5],
            ['parent' => 'PERSONAL', 'name' => 'Gestor de Personal', 'url' => 'administrador/planilla', 'icon' => 'user-cog', 'order' => 6],

            // ROL MENSUAL
            ['parent' => 'ROL_MENSUAL', 'name' => 'Asignar Prog. Turnos', 'url' => 'administrador/asignar_plan', 'icon' => 'user-clock', 'order' => 1],
            ['parent' => 'ROL_MENSUAL', 'name' => 'Ver Prog. de Turnos', 'url' => 'administrador/ver_plan', 'icon' => 'calendar-alt', 'order' => 2],
            ['parent' => 'ROL_MENSUAL', 'name' => 'Cambio de Turno (Admin)', 'url' => 'administrador/cambio_turno_user', 'icon' => 'exchange-alt', 'order' => 3],

            // ROL DE TURNOS
            ['parent' => 'ROL_DE_TURNOS', 'name' => 'Mi Rol de Turnos', 'url' => 'usuario/rol', 'icon' => 'calendar-check', 'order' => 1],
            ['parent' => 'ROL_DE_TURNOS', 'name' => 'Roles Por Usuario', 'url' => 'configuracion/cargar_rolusuario', 'icon' => 'users', 'order' => 2],
            ['parent' => 'ROL_DE_TURNOS', 'name' => 'Carga Masiva', 'url' => 'administrador/load_file_turnos', 'icon' => 'file-excel', 'order' => 3],

            // ASISTENCIA
            ['parent' => 'ASISTENCIA', 'name' => 'Consultar Asistencia', 'url' => 'administrador/reporte_asistencia', 'icon' => 'calendar-check', 'order' => 1],
            ['parent' => 'ASISTENCIA', 'name' => 'Rectificar Asistencia', 'url' => 'administrador/asistencia', 'icon' => 'edit', 'order' => 2],
            ['parent' => 'ASISTENCIA', 'name' => 'Mi Asistencia', 'url' => 'usuario/asistencia', 'icon' => 'user-check', 'order' => 3],

            // DISPOSITIVO
            ['parent' => 'DISPOSITIVO', 'name' => 'Subir Datos', 'url' => 'marcar/prueba2', 'icon' => 'upload', 'order' => 1],
            ['parent' => 'DISPOSITIVO', 'name' => 'Cargar desde Ztkeco', 'url' => 'administrador/load_file', 'icon' => 'hdd', 'order' => 2],
            ['parent' => 'DISPOSITIVO', 'name' => 'Archivo HikVision', 'url' => 'administrador/load_file_hv', 'icon' => 'file-excel', 'order' => 3],

            // REPORTES
            ['parent' => 'REPORTES', 'name' => 'Asistencia Mensual', 'url' => 'administrador/reporte_total', 'icon' => 'calendar-alt', 'order' => 1],
            ['parent' => 'REPORTES', 'name' => 'Asistencia Hoy', 'url' => 'administrador/asistencia_hoy', 'icon' => 'clock', 'order' => 2],
            ['parent' => 'REPORTES', 'name' => 'Control de Roles', 'url' => 'administrador/faltas_tardanzas', 'icon' => 'user-slash', 'order' => 3],
            ['parent' => 'REPORTES', 'name' => 'Rango de Fechas', 'url' => 'administrador/reporte_total_rango', 'icon' => 'calendar-week', 'order' => 4],
            ['parent' => 'REPORTES', 'name' => 'Inasistencias', 'url' => 'administrador/reporte_total_inasistencia', 'icon' => 'user-times', 'order' => 5],
            ['parent' => 'REPORTES', 'name' => 'Tardanzas y Faltas', 'url' => 'administrador/faltas_tardanzas', 'icon' => 'exclamation-circle', 'order' => 6],
            ['parent' => 'REPORTES', 'name' => 'Reporte Específico', 'url' => 'administrador/reporte_total_especifico', 'icon' => 'file-alt', 'order' => 7],
            ['parent' => 'REPORTES', 'name' => 'Ver Rol de Turnos', 'url' => 'administrador/reporte_rol_turno', 'icon' => 'clipboard-list', 'order' => 8],
            ['parent' => 'REPORTES', 'name' => 'Reporte Antiguo', 'url' => 'administrador/reporte_nombrados', 'icon' => 'archive', 'order' => 9],
            ['parent' => 'REPORTES', 'name' => 'CAP Nominal', 'url' => 'administrador/capnominal', 'icon' => 'id-card', 'order' => 10],
            ['parent' => 'REPORTES', 'name' => 'Calificador Mensual', 'url' => 'administrador/calificador', 'icon' => 'award', 'order' => 11],
            ['parent' => 'REPORTES', 'name' => 'Ver Reportes', 'url' => 'administrador/listarreportes', 'icon' => 'folder-open', 'order' => 12],
            ['parent' => 'REPORTES', 'name' => 'Reporte Turnos TUASALUD', 'url' => 'tuasalud/reporte_rol_tuasalud', 'icon' => 'file-csv', 'order' => 13],
            ['parent' => 'REPORTES', 'name' => 'Reporte 40', 'url' => 'administrador/reporte_40', 'icon' => 'file-medical-alt', 'order' => 14],

            // PERFIL
            ['parent' => 'PERFIL', 'name' => 'Mis Datos', 'url' => 'usuario/perfil', 'icon' => 'user-id', 'order' => 1],

            // CAMBIOS
            ['parent' => 'CAMBIOS', 'name' => 'Cambio de Turno (Solicitud)', 'url' => 'usuario/cambio_turno', 'icon' => 'exchange-alt', 'order' => 1],

            // VACACIONES
            ['parent' => 'VACACIONES', 'name' => 'Mis Vacaciones', 'url' => 'usuario/vacaciones', 'icon' => 'sun', 'order' => 1],

            // RED DE SALUD
            ['parent' => 'RED_DE_SALUD', 'name' => 'Usuarios del Sistema', 'url' => 'administrador/periferie', 'icon' => 'network-wired', 'order' => 1],

            // BASE DE DATOS (CRUDs)
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Gestor de Datos', 'url' => 'gestordb/listar_tablas', 'icon' => 'database', 'order' => 1],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Tareas', 'url' => 'gestordb/tarea', 'icon' => 'tasks', 'order' => 2],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Oficinas', 'url' => 'gestordb/oficina', 'icon' => 'briefcase', 'order' => 3],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Licencia', 'url' => 'gestordb/licencia', 'icon' => 'calendar-minus', 'order' => 4],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Turno', 'url' => 'gestordb/turno', 'icon' => 'hourglass-half', 'order' => 5],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Horario de Turnos', 'url' => 'gestordb/turno_horario', 'icon' => 'clock', 'order' => 6],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Cargo', 'url' => 'gestordb/cargo', 'icon' => 'user-tag', 'order' => 7],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Nivel de Acceso', 'url' => 'gestordb/rol', 'icon' => 'users-cog', 'order' => 8],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Distrito', 'url' => 'gestordb/distrito', 'icon' => 'map-marker', 'order' => 9],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Sector', 'url' => 'gestordb/microred', 'icon' => 'map-signs', 'order' => 10],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Usuarios de Sistema', 'url' => 'gestordb/usuario', 'icon' => 'users', 'order' => 11],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Establecimientos', 'url' => 'gestordb/establecimiento', 'icon' => 'hospital', 'order' => 12],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Asignar Tareas', 'url' => 'gestordb/asignar_tarea', 'icon' => 'tasks', 'order' => 13],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Modalidad', 'url' => 'gestordb/tipo_contrato', 'icon' => 'handshake', 'order' => 14],
            ['parent' => 'BASE_DE_DATOS', 'name' => 'Permisos', 'url' => 'gestordb/permiso', 'icon' => 'id-card', 'order' => 15],

            // MARCAR ASISTENCIA
            ['parent' => 'MARCAR_ASISTENCIA', 'name' => 'Marcar mi Asistencia', 'url' => 'usuario/reloj', 'icon' => 'fingerprint', 'order' => 1],

            // RELOJ
            ['parent' => 'RELOJ', 'name' => 'Marcar Asistencia', 'url' => 'usuario/reloj', 'icon' => 'clock', 'order' => 1],

            // KARDEX
            ['parent' => 'KARDEX', 'name' => 'Kardex Total', 'url' => 'administrador/reporte_total_kardex', 'icon' => 'book', 'order' => 1],

            // ASISTENCIA POR PUESTO
            ['parent' => 'ASISTENCIA_POR_PUESTO', 'name' => 'Asistencia Por Establecimiento', 'url' => 'usuario/asistencia_est', 'icon' => 'map-pin', 'order' => 1],

            // ESTABLECIMIENTOS
            ['parent' => 'ESTABLECIMIENTOS', 'name' => 'Mapa de Establecimientos', 'url' => 'administrador/mapa_establecimiento', 'icon' => 'map-marked-alt', 'order' => 1],

            // CALIFICACION
            ['parent' => 'CALIFICACION', 'name' => 'Calificador MicroRed', 'url' => 'administrador/calificador_microred', 'icon' => 'award', 'order' => 1],
            ['parent' => 'CALIFICACION', 'name' => 'Ver Reportes (Microred)', 'url' => 'administrador/listarreportes_microred', 'icon' => 'folder-open', 'order' => 2],
        ];

        // Insertar submenús vinculándolos a su parent_id correspondiente
        foreach ($submenus as $sub) {
            if (isset($padresIds[$sub['parent']])) {
                $builder->insert([
                    'type'       => 'secondary',
                    'parent_id'  => $padresIds[$sub['parent']],
                    'name'       => $sub['name'],
                    'abbr'       => null,
                    'url'        => $sub['url'],
                    'icon'       => $sub['icon'],
                    'status'     => 'active',
                    'order'      => $sub['order'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
