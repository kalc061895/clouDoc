<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class UnidadOrganizacionalModel extends Model
{
    protected $table            = 'casis_unidades_organizacionales';
    protected $primaryKey       = 'uo_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uo_padre_ide',
        'uo_nivel_tipo',
        'uo_nombre',
        'uo_abreviatura',
        'uo_direccion',
        'uo_ubicacion',
        'uo_jefe_per_ide'
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

    /**
     * Obtiene todas las sub-unidades de un padre específico
     */
    public function getHijos($padre_id)
    {
        return $this->where('uo_padre_ide', $padre_id)->findAll();
    }

    /**
     * Cuenta cuántas personas hay en una unidad específica
     */
    public function contarPersonal($uo_ide)
    {
        return $this->db->table('casis_personal')
            ->where('perl_uo_ide', $uo_ide)
            ->countAllResults();
    }

    /**
     * Obtiene el árbol completo (Recursivo simple)
     */
    public function getArbol($padre_id = null)
    {
        $unidades = $this->where('uo_padre_ide', $padre_id)->findAll();
        foreach ($unidades as &$u) {
            $u['sub_unidades'] = $this->getArbol($u['uo_ide']);
            $u['total_personal'] = $this->contarPersonal($u['uo_ide']);
        }
        return $unidades;
    }
}
