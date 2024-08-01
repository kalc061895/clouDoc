<?php

declare(strict_types=1);

namespace App\Models;


use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UsuarioModel extends ShieldUserModel
{
    protected $allowedFields    = [
        'username', 'email', 'nombres',
        'paterno', 'materno', 'dni', 'cargo', 'telefono', 'oficina_id', 'active'
    ];
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'username', 'email', 'nombres',
            'paterno', 'materno', 'dni', 'cargo', 'telefono', 'oficina_id', 'active'
        ];
    }
    public function findInfo()
    {
        return $this->select('users.*, oficinas.nombre as nombre_oficina')->join('oficinas', 'oficinas.id = users.oficina_id', 'JOIN')->get()->getResultObject();
    }

    public function getGrupos()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('group_user');

        $query = $builder->get();
        return $query->getResultObject();
    }
    public function getGroup($userId)
    {
        $db = \Config\Database::connect();

        return $db->table('auth_groups_users')->select('auth_groups_users.group')
            ->where('auth_groups_users.user_id', $userId)
            ->get()->getResultObject();
    }
    public function actualizarGrupo($userId, $grupo)
    {
        $db = \Config\Database::connect();

        // Verifica si el usuario ya tiene un grupo asignado
        $existingGroup = $db->table('auth_groups_users')
            ->where('user_id', $userId)
            ->get()
            ->getRow();

        if ($existingGroup) {
            // Actualiza el grupo del usuario si ya tiene uno asignado
            return $db->table('auth_groups_users')
                ->where('user_id', $userId)
                ->update(['group' => $grupo]);
        } else {
            // Inserta un nuevo grupo para el usuario si no tiene uno asignado
            return $db->table('auth_groups_users')
                ->insert(['user_id' => $userId, 'group' => $grupo]);
        }
    }
}
