<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
        protected $protectFields    = true;
    protected $allowedFields = [
        'type', 'parent_id', 'name', 'abbr', 'url', 'icon', 'status', 'separator'
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getMenusByRole()
    {
        $tipoUsuario = auth()->user()->groups;

        $db = \Config\Database::connect();

        $builder = $db->table('menus');
        $builder->select('menus.*');
        $builder->join(
            'group_user',
            "group_user.name = '".$tipoUsuario[0]."'",
            'join'
        );
        $builder->join(
            'menu_group_user',
            "menu_group_user.menu_id = menus.id AND menu_group_user.group_user_id = group_user.id",
            'join'
        );
        $builder->orderBy('menus.order ASC');

        $query = $builder->get();

        return $query->getResultArray();
        // Puedes implementar lógica para filtrar los menús por rol si es necesario
        //return $builder->where('status', 'active')->findAll();
    }

    public function getSubMenus($parentId)
    {
        return $this->where('parent_id', $parentId)->where('status', 'active')->findAll();
    }
    public function getAll()
    {
        return $this->orderBy('order ASC')->findAll();
    }

    public function findAllWithParents()
    {
        return $this->select('menus.*, parent.name as parent_name')
                    ->join('menus as parent', 'parent.id = menus.parent_id', 'left')
                    ->findAll();
    }
}
