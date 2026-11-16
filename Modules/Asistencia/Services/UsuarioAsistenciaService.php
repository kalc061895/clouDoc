<?php

namespace Modules\Asistencia\Services;

use App\Models\UsuarioModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Validation\ValidationRules;

class UsuarioAsistenciaService
{
    public function __construct(private $db = null)
    {
        $this->db ??= db_connect();
    }

    public static function gruposPermitidos(array $grupos): bool
    {
        return $grupos !== [] && count(array_filter($grupos, static fn($g) => is_string($g) && str_starts_with($g, 'asi'))) === count($grupos);
    }

    public function perfiles(): array
    {
        $configurados = setting('AuthGroups.groups') ?? config('AuthGroups')->groups;
        return array_values(array_filter($this->db->table('group_user')->like('name', 'asi', 'after')->orderBy('name')->get()->getResultArray(), static fn($p) => isset($configurados[$p['name']])));
    }

    public function listar(): array
    {
        $rows = $this->db->table('users u')->select('u.id, u.username, u.dni, u.nombres, u.paterno, u.materno, u.active, g.group')
            ->join('auth_groups_users g', 'g.user_id=u.id')->where('u.deleted_at', null)->orderBy('u.username')->get()->getResultArray();
        $usuarios = [];
        foreach ($rows as $row) {
            $id = $row['id']; $grupo = $row['group']; unset($row['group']);
            $usuarios[$id] ??= $row + ['grupos'=>[]];
            $usuarios[$id]['grupos'][] = $grupo;
        }
        return array_values(array_filter($usuarios, static fn($u) => self::gruposPermitidos($u['grupos'])));
    }

    public function exigirUsuario(int $id): void
    {
        $grupos = array_column($this->db->table('auth_groups_users')->where('user_id', $id)->get()->getResultArray(), 'group');
        if (!self::gruposPermitidos($grupos)) throw new \DomainException('Esta cuenta se administra desde el módulo principal porque tiene perfiles ajenos a Asistencia.');
        if (!$this->db->table('users')->where('id', $id)->where('deleted_at', null)->countAllResults()) throw new \DomainException('Usuario no encontrado.');
    }

    public function detalle(int $id): array
    {
        $this->exigirUsuario($id);
        $user = (new UsuarioModel())->findById($id);
        $data = ['id'=>$id, 'email'=>$user->email, 'grupos'=>array_column($this->db->table('auth_groups_users')->where('user_id',$id)->get()->getResultArray(),'group')];
        foreach (['username','dni','nombres','paterno','materno','telefono','cargo','active'] as $campo) $data[$campo] = $user->$campo;
        return $data;
    }

    public function buscarDni(string $dni): ?array
    {
        if (!preg_match('/^[0-9]{8}$/D', $dni)) throw new \InvalidArgumentException('Ingrese un DNI de ocho dígitos.');
        return $this->db->table('casis_persona')->select('per_numero_documento AS dni, per_nombre AS nombres, per_paterno AS paterno, per_materno AS materno, per_email AS email, per_telefono AS telefono')
            ->where('per_numero_documento', $dni)->where('deleted_at', null)->get()->getRowArray();
    }

    public function guardar(array $input, int $actor): int
    {
        $id = (int)($input['id'] ?? 0);
        if ($id) $this->exigirUsuario($id);
        $grupos = $input['grupos'] ?? [];
        if (!is_array($grupos) || !self::gruposPermitidos($grupos) || array_diff($grupos, array_column($this->perfiles(), 'name'))) throw new \InvalidArgumentException('Seleccione al menos un perfil válido de Asistencia.');
        if ($id && $id === $actor) throw new \DomainException('Administre su propia cuenta desde Mi perfil; utilice otra cuenta administradora para cambiar sus accesos.');
        $data = [];
        foreach (['username','email','dni','nombres','paterno','materno','telefono','cargo'] as $campo) $data[$campo] = trim((string)($input[$campo] ?? ''));
        $data['password'] = (string)($input['password'] ?? '');
        $data['password_confirm'] = (string)($input['password_confirm'] ?? '');
        $rules = (new ValidationRules())->getRegistrationRules();
        // La unicidad se comprueba excluyendo únicamente la cuenta que se edita.
        $rules['username'] = config('Auth')->usernameValidationRules;
        $rules['email'] = config('Auth')->emailValidationRules;
        if ($id && $data['password'] === '') unset($rules['password'], $rules['password_confirm']);
        $rules += ['dni'=>'required|regex_match[/^[0-9]{8}$/D]', 'nombres'=>'required|max_length[100]', 'paterno'=>'required|max_length[100]', 'materno'=>'permit_empty|max_length[100]', 'telefono'=>'permit_empty|max_length[20]', 'cargo'=>'permit_empty|max_length[100]'];
        $validation = service('validation'); $validation->reset(); $validation->setRules($rules);
        if (!$validation->run($data)) throw new \InvalidArgumentException(implode(' ', $validation->getErrors()));
        foreach (['username','dni'] as $campo) {
            if ($this->db->table('users')->where($campo,$data[$campo])->where('id !=',$id)->countAllResults()) throw new \InvalidArgumentException('Ya existe una cuenta con ese ' . $campo . '.');
        }
        if ($this->db->table('auth_identities')->where('type','email_password')->where('secret',$data['email'])->where('user_id !=',$id)->countAllResults()) throw new \InvalidArgumentException('El correo ya está registrado en otra cuenta.');
        unset($data['password_confirm']);
        if ($id && $data['password'] === '') unset($data['password']);
        $data['active'] = (string)($input['active'] ?? '0') === '1' ? 1 : 0;
        $this->db->transException(true)->transBegin();
        try {
            $model = new UsuarioModel($this->db);
            $user = $id ? $model->findById($id) : new User();
            $user->fill($data);
            if (!$model->save($user)) throw new \InvalidArgumentException(implode(' ', $model->errors()));
            $id = $id ?: (int)$model->getInsertID();
            $user = $model->findById($id);
            $user->syncGroups(...array_unique($grupos));
            $this->auditar($actor, empty($input['id']) ? 'CREAR_USUARIO' : 'EDITAR_USUARIO', $id, ['grupos'=>$grupos, 'active'=>$data['active']]);
            $this->db->transCommit();
            return $id;
        } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
    }

    public function eliminar(int $id, int $actor): void
    {
        $this->exigirUsuario($id);
        if ($id === $actor) throw new \DomainException('No puede eliminar su propia cuenta.');
        $this->db->transException(true)->transBegin();
        try {
            if (!(new UsuarioModel($this->db))->delete($id)) throw new \RuntimeException('No se pudo eliminar la cuenta.');
            $this->auditar($actor, 'ELIMINAR_USUARIO', $id, []);
            $this->db->transCommit();
        } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
    }

    private function auditar(int $actor, string $accion, int $id, array $detalle): void
    {
        $this->db->table('casis_acceso_auditoria')->insert(['actor_id'=>$actor, 'accion'=>$accion, 'entidad_tipo'=>'usuario', 'entidad_id'=>$id, 'detalle'=>json_encode($detalle, JSON_THROW_ON_ERROR), 'created_at'=>date('Y-m-d H:i:s')]);
    }
}
