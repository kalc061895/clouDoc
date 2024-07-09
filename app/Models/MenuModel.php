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


    public function getMenusByRole($role)
    {
        // Puedes implementar lógica para filtrar los menús por rol si es necesario
        return $this->where('status', 'active')->findAll();
    }

    public function getSubMenus($parentId)
    {
        return $this->where('parent_id', $parentId)->where('status', 'active')->findAll();
    }
    public function getAll()
    {
        return $this->orderBy('order ASC')->findAll();
    }
}
