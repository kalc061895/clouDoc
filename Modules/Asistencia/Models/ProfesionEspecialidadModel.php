<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ProfesionEspecialidadModel extends Model
{
    protected $table            = 'casis_profesion_especialidad';
    protected $primaryKey       = 'pes_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'pes_pro_ide',
        'pes_se_ide',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'pes_pro_ide' => 'required|integer',
        'pes_se_ide'  => 'required|integer',
    ];

    protected $skipValidation = false;

    /**
     * Especialidades por profesión
     */
    public function obtenerEspecialidadesPorProfesion(int $proIde)
    {
        return $this->select('
                casis_profesion_especialidad.*,
                casis_segunda_especialidad.se_ide,
                casis_segunda_especialidad.se_nombre,
                casis_segunda_especialidad.se_abreviatura
            ')
            ->join(
                'casis_segunda_especialidad',
                'casis_segunda_especialidad.se_ide = casis_profesion_especialidad.pes_se_ide'
            )
            ->where('pes_pro_ide', $proIde)
            ->findAll();
    }

    /**
     * Profesiones por especialidad
     */
    public function obtenerProfesionesPorEspecialidad(int $seIde)
    {
        return $this->select('
                casis_profesion_especialidad.*,
                casis_profesion.pro_nombre,
                casis_profesion.pro_abreviatura
            ')
            ->join(
                'casis_profesion',
                'casis_profesion.pro_ide = casis_profesion_especialidad.pes_pro_ide'
            )
            ->where('pes_se_ide', $seIde)
            ->findAll();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationMessages   = [];
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
}
