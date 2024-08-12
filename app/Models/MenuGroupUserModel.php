<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuGroupUserModel extends Model
{
    protected $table            = 'menu_group_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'menu_id', 'group_user_id', 'created_at', 'updated_at'
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

    // Server-side processing for DataTables
    public function getDatatables($request)
    {
        $column_order = array('menu_id', 'group_user_id', 'created_at');
        $column_search = array('menu_id', 'group_user_id');
        $order = array('id' => 'asc');

        $builder = $this->db->table($this->table);
        $i = 0;

        foreach ($column_search as $item) {
            if ($request->getPost('search')['value']) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $request->getPost('search')['value']);
                } else {
                    $builder->orLike($item, $request->getPost('search')['value']);
                }

                if (count($column_search) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }

        if ($request->getPost('order')) {
            $builder->orderBy($column_order[$request->getPost('order')['0']['column']], $request->getPost('order')['0']['dir']);
        } else if (isset($order)) {
            $builder->orderBy(key($order), $order[key($order)]);
        }

        $builder->limit($request->getPost('length'), $request->getPost('start'));
        $query = $builder->get();
        $data = array();
        foreach ($query->getResult() as $row) {
            $data[] = $row;
        }

        $total_filtered = $this->db->table($this->table)->countAllResults();
        $total_records = $this->countAllResults();

        $output = array(
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_filtered,
            "data" => $data,
        );

        return $output;
    }
}
