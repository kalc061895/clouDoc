<?php

declare(strict_types=1);

namespace App\Models;


use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UsuarioModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'username', 'status', 'status_message', 'nombres',
            'paterno', 'materno', 'dni', 'cargo', 'telefono', 'oficina_id'
        ];
    }
    public function findInfo(){
        return $this->select('users.*, oficinas.nombre as nombre_oficina')->join('oficinas', 'oficinas.id = users.oficina_id', 'JOIN')->get()->getResultObject();
    }
    
    public function getGrupos() {
        $db = \Config\Database::connect();

        $builder = $db->table('group_user');
        
        $query = $builder->get();
        return $query->getResultObject();
    }
}
