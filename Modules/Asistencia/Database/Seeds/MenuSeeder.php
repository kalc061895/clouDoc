<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    /** IDs permanentes: no renumerar al agregar opciones. */
    public static function menus(): array
    {
        $definitions = [
            [1000, null, 'ASISTENCIA', null, 'calendar-mark-line-duotone'],
            [1001, 1000, 'Personal', 'asistencia/personal', 'users-group-rounded-line-duotone'],
            [1002, 1000, 'Nuevo personal', 'asistencia/personal/nuevo', 'user-plus-line-duotone'],
            [1003, 1000, 'Gestor de personal', 'asistencia/personal/gestorpersonal', 'users-group-rounded-line-duotone'],
            [1004, 1000, 'Programación de turnos', 'asistencia/programacion', 'calendar-line-duotone'],
            [1005, 1000, 'Marcaciones', 'asistencia/marcaciones', 'clock-circle-line-duotone'],
            [1010, null, 'CATÁLOGOS DE ASISTENCIA', null, 'database-line-duotone'],
            [1011, 1010, 'Tipos de oficina', 'asistencia/gestordb/tipo-oficina', 'database-line-duotone'],
            [1012, 1010, 'Oficinas', 'asistencia/gestordb/oficina', 'database-line-duotone'],
            [1013, 1010, 'Diresas', 'asistencia/gestordb/diresas', 'database-line-duotone'],
            [1014, 1010, 'Redes', 'asistencia/gestordb/redes', 'database-line-duotone'],
            [1015, 1010, 'Microredes', 'asistencia/gestordb/microredes', 'database-line-duotone'],
            [1016, 1010, 'Establecimientos', 'asistencia/gestordb/establecimiento', 'database-line-duotone'],
            [1017, 1010, 'Licencias', 'asistencia/gestordb/licencia', 'database-line-duotone'],
            [1018, 1010, 'Turnos', 'asistencia/gestordb/turno', 'database-line-duotone'],
            [1019, 1010, 'Horarios de turnos', 'asistencia/gestordb/turnohorario', 'database-line-duotone'],
            [1020, 1010, 'Modalidades de contrato', 'asistencia/gestordb/modalidad', 'database-line-duotone'],
            [1021, 1010, 'Permisos', 'asistencia/gestordb/permiso', 'database-line-duotone'],
            [1022, 1010, 'Cargos', 'asistencia/gestordb/cargo', 'database-line-duotone'],
            [1023, 1010, 'Profesiones', 'asistencia/gestordb/profesion', 'database-line-duotone'],
            [1024, 1010, 'Colegiaturas', 'asistencia/gestordb/colegiatura', 'database-line-duotone'],
            [1025, 1010, 'Feriados', 'asistencia/gestordb/feriado', 'database-line-duotone'],
            [1026, 1010, 'Tipos de documento', 'asistencia/gestordb/tipodocumento', 'database-line-duotone'],
            [1027, 1010, 'UPSS', 'asistencia/gestordb/upss', 'database-line-duotone'],
            [1028, 1010, 'Servicios UPSS', 'asistencia/gestordb/upss-servicio', 'database-line-duotone'],
            [1029, 1010, 'Personas', 'asistencia/gestordb/persona', 'database-line-duotone'],
            [1030, 1010, 'Segundas especialidades', 'asistencia/gestordb/segunda-especialidad', 'database-line-duotone'],
            [1031, 1010, 'Profesión y especialidades', 'asistencia/gestordb/profesion-especialidad', 'database-line-duotone'],
            [1032, 1010, 'Periodos', 'asistencia/gestordb/periodos', 'database-line-duotone'],
            [1033, 1010, 'Grupos de corte', 'asistencia/gestordb/grupo-corte', 'database-line-duotone'],
        ];
        $menus = [];
        foreach ($definitions as $order => [$id, $parent, $name, $url, $icon]) {
            $menus[] = [
                'id' => $id, 'parent_id' => $parent,
                'type' => $parent === null ? 'separator' : 'secondary',
                'name' => $name, 'url' => $url, 'icon' => $icon,
                'status' => 'active', 'order' => $order + 1000,
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
