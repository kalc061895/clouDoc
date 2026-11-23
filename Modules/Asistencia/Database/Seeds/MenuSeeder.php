<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    /** Opciones reservadas: sin ruta hasta implementar sus pantallas. */
    public const RETIRED_IDS = [1041, 1042];
    public const PENDING_IDS = [1052, 1053, 1056];
    public const REPORT_IDS = [1050, 1051, 1052, 1053, 1054, 1055, 1056];
    public const GRUPOS_CATALOGOS = [1105, 1106, 1107, 1108];

    /** IDs permanentes: no renumerar al agregar opciones. */
    public static function menus(): array
    {
        $definitions = [
            [1090, null, 'CALIFICACIÓN MENSUAL', null, 'calendar-mark-line-duotone'],
            [1091, 1090, 'Calificar y revisar', 'asistencia/calificacion', 'document-text-line-duotone'],
            [1092, 1090, 'Historial y PDF', 'asistencia/calificacion#historial', 'history-line-duotone'],
            [1093, 1090, 'Criterios de calificación', 'asistencia/calificacion#criterios', 'settings-line-duotone'],
            [1000, null, 'ASISTENCIA', null, 'calendar-mark-line-duotone'],
            [1006, 1000, 'Dashboard de asistencia', 'asistencia', 'chart-square-line-duotone'],
            [1001, 1000, 'Personal', 'asistencia/personal', 'users-group-rounded-line-duotone'],
            [1002, 1000, 'Nuevo personal', 'asistencia/personal/nuevo', 'user-plus-line-duotone'],
            [1003, 1000, 'Gestor de personal', 'asistencia/personal/gestorpersonal', 'users-group-rounded-line-duotone'],
            [1088, 1000, 'Importar Personal', 'asistencia/personal/importar', 'upload-line-duotone'],
            [1005, 1000, 'Marcaciones', 'asistencia/marcaciones', 'clock-circle-line-duotone'],
            [1070, null, 'DISPOSITIVOS', null, 'devices-line-duotone'],
            [1071, 1070, 'Configuración de dispositivos', 'asistencia/dispositivos', 'settings-line-duotone'],
            [1072, 1070, 'Asistencia desde archivo', 'asistencia/dispositivos/archivos', 'upload-line-duotone'],
            [1040, null, 'ROLES', null, 'document-text-line-duotone'],
            [1041, 1040, 'Generación de roles', 'asistencia/roles/generacion', 'calendar-line-duotone'],
            [1042, 1040, 'Historial de roles', 'asistencia/roles/historial', 'history-line-duotone'],
            [1043, 1040, 'Firmar roles', 'asistencia/roles/firmar', 'pen-new-square-line-duotone'],
            [1080, null, 'GESTIÓN DE ROLES', null, 'calendar-mark-line-duotone'],
            [1044, 1080, 'Roles por oficina', 'asistencia/gestion-roles', 'document-text-line-duotone'],
            [1004, 1080, 'Programación de turnos', 'asistencia/programacion', 'calendar-line-duotone'],
            [1081, 1080, 'Períodos de roles', 'asistencia/gestion-roles/periodos', 'calendar-mark-line-duotone'],
            [1082, 1080, 'Plantillas de reglas', 'asistencia/gestion-roles/plantillas', 'settings-line-duotone'],
            [1083, 1080, 'Envíos y aprobaciones', 'asistencia/gestion-roles/envios', 'document-text-line-duotone'],
            [1098, 1080, 'Recursos Humanos: visto bueno', 'asistencia/gestion-roles/recursos-humanos', 'check-circle-line-duotone'],
            [1099, 1080, 'Dirección: aprobación final', 'asistencia/gestion-roles/direccion', 'check-circle-line-duotone'],
            [1084, 1080, 'Delegaciones temporales', 'asistencia/gestion-roles/delegaciones', 'users-group-rounded-line-duotone'],
            [1086, 1000, 'Perfiles y accesos', 'asistencia/accesos', 'users-group-rounded-line-duotone'],
            [1100, 1000, 'Usuarios de Asistencia', 'asistencia/usuarios', 'user-plus-line-duotone'],
            [1087, 1080, 'Grupos de programación', 'asistencia/gestion-roles/grupos', 'network-line-duotone'],
            [1089, 1080, 'Programacion pendiente', 'asistencia/gestion-roles/pendientes', 'list-check-line-duotone'],
            [1104, 1080, 'Presupuesto de guardias', 'asistencia/gestion-roles/presupuesto-guardias', 'calculator-line-duotone'],
            [1050, null, 'REPORTES', null, 'chart-square-line-duotone'],
            [1051, 1050, 'Reporte mensual', 'asistencia/reportes/mensual', 'document-text-line-duotone'],
            [1052, 1050, 'Auditoría', null, 'history-line-duotone'],
            [1053, 1050, 'Gestión de turnos', null, 'calendar-line-duotone'],
            [1054, 1050, 'TUA SUSALUD', null, 'document-text-line-duotone'],
            [1055, 1054, 'Reporte TUA SUSALUD', 'asistencia/reportes/tua-susalud', 'document-text-line-duotone'],
            [1056, 1054, 'Reporte SETIPRESS', null, 'document-text-line-duotone'],
            [1095, 1054, 'Asignación de servicios por oficina', 'asistencia/reportes/tua-susalud/asignaciones', 'settings-line-duotone'],
            [1010, null, 'CATÁLOGOS DE ASISTENCIA', null, 'database-line-duotone'],
            [1011, 1105, 'Tipos de oficina', 'asistencia/gestordb/tipo-oficina', 'database-line-duotone'],
            [1012, 1105, 'Oficinas', 'asistencia/gestordb/oficina', 'database-line-duotone'],
            [1013, 1105, 'Diresas', 'asistencia/gestordb/diresas', 'database-line-duotone'],
            [1014, 1105, 'Redes', 'asistencia/gestordb/redes', 'database-line-duotone'],
            [1015, 1105, 'Microredes', 'asistencia/gestordb/microredes', 'database-line-duotone'],
            [1016, 1105, 'Establecimientos', 'asistencia/gestordb/establecimiento', 'database-line-duotone'],
            [1017, 1108, 'Licencias', 'asistencia/gestordb/licencia', 'database-line-duotone'],
            [1018, 1108, 'Turnos', 'asistencia/gestordb/turno', 'database-line-duotone'],
            [1019, 1108, 'Horarios de turnos', 'asistencia/gestordb/turnohorario', 'database-line-duotone'],
            [1020, 1107, 'Modalidades de contrato', 'asistencia/gestordb/modalidad', 'database-line-duotone'],
            [1021, 1108, 'Permisos', 'asistencia/gestordb/permiso', 'database-line-duotone'],
            [1022, 1107, 'Cargos', 'asistencia/gestordb/cargo', 'database-line-duotone'],
            [1034, 1107, 'Remuneraciones', 'asistencia/gestordb/remuneraciones', 'database-line-duotone'],
            [1023, 1107, 'Profesiones', 'asistencia/gestordb/profesion', 'database-line-duotone'],
            [1024, 1107, 'Colegiaturas', 'asistencia/gestordb/colegiatura', 'database-line-duotone'],
            [1025, 1108, 'Feriados', 'asistencia/gestordb/feriado', 'database-line-duotone'],
            [1026, 1107, 'Tipos de documento', 'asistencia/gestordb/tipodocumento', 'database-line-duotone'],
            [1027, 1106, 'UPSS', 'asistencia/gestordb/upss', 'database-line-duotone'],
            [1028, 1106, 'Servicios UPSS', 'asistencia/gestordb/upss-servicio', 'database-line-duotone'],
            [1096, 1106, 'UPSS por establecimiento', 'asistencia/gestordb/establecimiento-upss', 'hospital-line-duotone'],
            [1097, 1106, 'Servicios por establecimiento', 'asistencia/gestordb/establecimiento-upss-servicios', 'link-line-duotone'],
            [1029, 1107, 'Personas', 'asistencia/gestordb/persona', 'database-line-duotone'],
            [1030, 1107, 'Segundas especialidades', 'asistencia/gestordb/segunda-especialidad', 'database-line-duotone'],
            [1031, 1107, 'Profesión y especialidades', 'asistencia/gestordb/profesion-especialidad', 'database-line-duotone'],
            [1032, 1108, 'Periodos', 'asistencia/gestordb/periodos', 'database-line-duotone'],
            [1033, 1108, 'Grupos de corte', 'asistencia/gestordb/grupo-corte', 'database-line-duotone'],
            [1060, null, 'MI CUENTA DE ASISTENCIA', null, 'user-circle-line-duotone'],
            [1061, 1060, 'Mi perfil y apariencia', 'asistencia/mi-perfil', 'user-circle-line-duotone'],
            [1094, 1050, 'Auditoría institucional', 'asistencia/auditoria', 'history-line-duotone'],
            [1105, 1010, 'Organización territorial y oficinas', null, 'buildings-line-duotone'],
            [1106, 1010, 'Servicios asistenciales', null, 'hospital-line-duotone'],
            [1107, 1010, 'Personal y régimen laboral', null, 'users-group-rounded-line-duotone'],
            [1108, 1010, 'Turnos y control de asistencia', null, 'calendar-mark-line-duotone'],
        ];
        $menus = [];
        foreach ($definitions as $order => [$id, $parent, $name, $url, $icon]) {
            $menus[] = [
                'id' => $id, 'parent_id' => $parent,
                'type' => $parent === null ? 'separator' : 'secondary',
                'name' => $name, 'url' => $url, 'icon' => $icon,
                'status' => in_array($id, array_merge(self::PENDING_IDS, self::RETIRED_IDS), true) ? 'inactive' : 'active', 'order' => $order + 1000,
                'separator' => $parent === null ? $name : null,
            ];
        }
        return $menus;
    }

    public function run()
    {
        $menus = self::menus();
        // Verificar todas las colisiones antes de escribir.
        foreach ($menus as $menu) {
            $existing = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if ($existing && ($existing['url'] !== $menu['url']
                || ($menu['url'] === null && $existing['name'] !== $menu['name']))) {
                throw new \RuntimeException('El menú ' . $menu['id'] . ' ya pertenece a otra opción.');
            }
        }
        $this->db->transStart();
        foreach ($menus as $menu) {
            $builder = $this->db->table('menus');
            if ($builder->where('id', $menu['id'])->countAllResults()) {
                $builder->where('id', $menu['id'])->update($menu);
            } else {
                $builder->insert($menu);
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('No se pudieron guardar los menús de Asistencia.');
        }
    }
}
