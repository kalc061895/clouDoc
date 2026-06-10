<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroPermisoModel extends Model
{
    protected $table            = 'casis_registro_permiso';
    protected $primaryKey       = 'rp_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'rp_pero_ide',
        'rp_perl_ide',
        'rp_fecha',
        'rp_hora_salida',
        'rp_hora_retorno',
        'rp_total_horas',
        'rp_motivo',
        'rp_justificacion',
        'rp_numero_documento',
        'rp_fecha_documento',
        'rp_archivo',
        'rp_estado',
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
        'rp_pero_ide'      => 'required|integer',
        'rp_perl_ide'      => 'required|integer',
        'rp_fecha'         => 'required|valid_date',
        'rp_hora_salida'   => 'required',
        'rp_hora_retorno'  => 'required',
    ];

    public function obtenerPorPersonal($perlIde)
    {
        return $this->where('rp_perl_ide', $perlIde)
                    ->orderBy('rp_fecha', 'DESC')
                    ->findAll();
    }

    public function aprobados()
    {
        return $this->where('rp_estado', 'APROBADO')
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
}
