<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;

/** Administración de los perfiles de Asistencia y sus menús visibles. */
class AccesosController extends BaseModuleController
{
    private function autorizar(): void
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup('superadmin', 'asi_sua')) {
            throw new \DomainException('Solo superadmin o asi_sua puede administrar los accesos de Asistencia.');
        }
    }

    private function respuesta(callable $accion)
    {
        try {
            $this->autorizar();
            return $this->response->setJSON(['ok' => true, 'data' => $accion(), 'csrfHash' => csrf_hash()]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode($e instanceof \DomainException ? 403 : 400)
                ->setJSON(['ok' => false, 'message' => $e->getMessage(), 'csrfHash' => csrf_hash()]);
        }
    }

    public function index()
    {
        try { $this->autorizar(); }
        catch (\Throwable $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
        return view('Modules\\Asistencia\\Views\\accesos\\index');
    }

    public function datos()
    {
        return $this->respuesta(function () {
            $db = db_connect();
            $perfiles = $db->table('group_user')->like('name', 'asi', 'after')->orderBy('name')->get()->getResultArray();
            $usuarios = $db->query("SELECT u.id, u.username, u.nombres, u.paterno, u.materno, u.active, GROUP_CONCAT(DISTINCT agu.`group` ORDER BY agu.`group` SEPARATOR ', ') AS grupos FROM users u JOIN auth_groups_users agu ON agu.user_id=u.id WHERE agu.`group` LIKE 'asi%' GROUP BY u.id, u.username, u.nombres, u.paterno, u.materno, u.active ORDER BY u.username")->getResultArray();
            $todosMenus = $db->table('menus')->select('id, parent_id, name, url, status, `order`')->where('status', 'active')->orderBy('`order`')->get()->getResultArray();
            $mapMenus = array_column($todosMenus, null, 'id');
            $idsMenus = [];
            foreach ($todosMenus as $menu) {
                if (!str_starts_with((string) ($menu['url'] ?? ''), 'asistencia/')) continue;
                $idsMenus[(int) $menu['id']] = true;
                $parent = (int) ($menu['parent_id'] ?? 0);
                while ($parent && isset($mapMenus[$parent])) {
                    $idsMenus[$parent] = true;
                    $parent = (int) ($mapMenus[$parent]['parent_id'] ?? 0);
                }
            }
            $menus = array_values(array_filter($todosMenus, static fn ($m) => isset($idsMenus[(int) $m['id']])));
            $rel = $db->table('menu_group_user m')->select('m.group_user_id, m.menu_id')->join('menus n', 'n.id=m.menu_id')->like('n.url', 'asistencia/', 'after')->get()->getResultArray();
            return ['perfiles'=>$perfiles, 'usuarios'=>$usuarios, 'menus'=>$menus, 'asignaciones'=>$rel];
        });
    }

    public function guardarPerfil()
    {
        return $this->respuesta(function () {
            $nombre = strtolower(trim((string)$this->request->getPost('nombre')));
            if (!preg_match('/^asi[a-z0-9_]{1,38}$/', $nombre)) throw new \DomainException('El nombre debe comenzar con asi y usar letras, números o guion bajo.');
            $db = db_connect();
            if ($db->table('group_user')->where('name', $nombre)->countAllResults()) throw new \DomainException('Ese perfil ya existe.');
            $db->transException(true)->transStart();
            $db->table('group_user')->insert(['name'=>$nombre]);
            $grupos = setting('AuthGroups.groups');
            $grupos[$nombre] = ['title'=>$nombre, 'description'=>trim((string)$this->request->getPost('descripcion')) ?: $nombre];
            setting('AuthGroups.groups', $grupos);
            $db->transComplete();
            $perfil=$db->table('group_user')->where('name',$nombre)->get()->getRowArray();
            return ['id'=>(int)$perfil['id'], 'name'=>$nombre];
        });
    }

    public function menu()
    {
        return $this->respuesta(function () {
            $groupId=(int)$this->request->getPost('perfil_id'); $menuId=(int)$this->request->getPost('menu_id'); $activo=(int)$this->request->getPost('activo') === 1;
            $db=db_connect();
            $grupo=$db->table('group_user')->where('id',$groupId)->like('name','asi','after')->get()->getRowArray();
            $menu=$db->table('menus')->where('id',$menuId)->like('url','asistencia/','after')->get()->getRowArray();
            if (!$grupo || !$menu) throw new \DomainException('Perfil o menú no válido.');
            $rel=['group_user_id'=>$groupId,'menu_id'=>$menuId];
            if ($activo && !$db->table('menu_group_user')->where($rel)->countAllResults()) $db->table('menu_group_user')->insert($rel);
            if (!$activo) $db->table('menu_group_user')->where($rel)->delete();
            return $rel + ['activo'=>$activo];
        });
    }

    /** Persiste de una vez los cambios pendientes del perfil. */
    public function guardarCambios()
    {
        return $this->respuesta(function () {
            $perfilId = (int) $this->request->getPost('perfil_id');
            $cambios = json_decode((string) $this->request->getPost('cambios'), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($cambios) || !is_array($cambios['menus'] ?? null) || !is_array($cambios['usuarios'] ?? null)) {
                throw new \DomainException('El formato de los cambios no es válido.');
            }
            $db = db_connect();
            $perfil = $db->table('group_user')->where('id', $perfilId)->like('name', 'asi', 'after')->get()->getRowArray();
            if (!$perfil) throw new \DomainException('Perfil no encontrado.');
            $db->transException(true)->transBegin();
            try {
                foreach ($cambios['menus'] as $cambio) {
                    $menuId = (int) ($cambio['id'] ?? 0); $activo = !empty($cambio['activo']);
                    $menu = $db->table('menus')->where('id', $menuId)->like('url', 'asistencia/', 'after')->where('status', 'active')->get()->getRowArray();
                    if (!$menu) throw new \DomainException('Se indicó un menú de Asistencia inválido.');
                    $rel = ['group_user_id' => $perfilId, 'menu_id' => $menuId];
                    $existe = $db->table('menu_group_user')->where($rel)->countAllResults() > 0;
                    if ($activo && !$existe) $db->table('menu_group_user')->insert($rel);
                    if (!$activo && $existe) $db->table('menu_group_user')->where($rel)->delete();
                }
                foreach ($cambios['usuarios'] as $cambio) {
                    $userId = (int) ($cambio['id'] ?? 0); $activo = !empty($cambio['activo']);
                    $tienePerfilAsistencia = $db->table('auth_groups_users')->where('user_id', $userId)->like('group', 'asi', 'after')->countAllResults() > 0;
                    if (!$tienePerfilAsistencia) throw new \DomainException('El usuario ya no pertenece a un perfil de Asistencia.');
                    $rel = ['user_id' => $userId, 'group' => $perfil['name']];
                    $existe = $db->table('auth_groups_users')->where($rel)->countAllResults() > 0;
                    if ($activo && !$existe) $db->table('auth_groups_users')->insert($rel + ['created_at' => date('Y-m-d H:i:s')]);
                    if (!$activo && $existe) $db->table('auth_groups_users')->where($rel)->delete();
                }
                $db->transCommit();
            } catch (\Throwable $e) {
                $db->transRollback();
                throw $e;
            }
            return ['perfil_id' => $perfilId, 'menus' => count($cambios['menus']), 'usuarios' => count($cambios['usuarios'])];
        });
    }

    public function usuario()
    {
        return $this->respuesta(function () {
            $id=(int)$this->request->getPost('usuario_id'); $perfilId=(int)$this->request->getPost('perfil_id'); $activo=(int)$this->request->getPost('activo')===1; $db=db_connect();
            $perfil=$db->table('group_user')->where('id',$perfilId)->like('name','asi','after')->get()->getRowArray();
            if (!$perfil || !$db->table('users')->where('id',$id)->countAllResults()) throw new \DomainException('Usuario o perfil no válido.');
            $rel=['user_id'=>$id,'group'=>$perfil['name']];
            if ($activo && !$db->table('auth_groups_users')->where($rel)->countAllResults()) $db->table('auth_groups_users')->insert($rel + ['created_at'=>date('Y-m-d H:i:s')]);
            if (!$activo) $db->table('auth_groups_users')->where($rel)->delete();
            return $rel + ['activo'=>$activo];
        });
    }
}
