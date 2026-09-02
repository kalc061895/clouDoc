<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroPermisoModel extends Model
{
    protected $table      = 'casis_registro_permiso';
    protected $primaryKey = 'rp_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'rp_pero_ide', // Foránea a casis_permiso.pero_ide
        'rp_perl_ide',
        'rp_motivo',
        'rp_fecha',
        'rp_hora_salida',
        'rp_hora_retorno',
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
        'rp_pero_ide'    => 'required|integer',
        'rp_perl_ide'    => 'required|integer',
        'rp_fecha'       => 'required|valid_date',
        'rp_hora_salida' => 'required',
        'rp_hora_retorno'    => 'required',
    ];

    public function obtenerPorPersonal($perlIde)
    {
        return $this->where('rp_perl_ide', $perlIde)
            ->orderBy('rp_fecha', 'DESC')
            ->orderBy('rp_hora_salida', 'DESC')
            ->findAll();
    }

    public function obtenerPorPersonalConTipo($perlIde, $mes = null, $anio = null)
    {
        $builder = $this->select('casis_registro_permiso.*, casis_permiso.pero_nombre, casis_permiso.pero_abreviatura, casis_permiso.pero_remunerado, casis_permiso.pero_horas_maximas')
            ->join('casis_permiso', 'casis_permiso.pero_ide = casis_registro_permiso.rp_pero_ide')
            ->where('casis_registro_permiso.rp_perl_ide', $perlIde);

        if (!empty($anio)) {
            $builder->where("YEAR(rp_fecha)", $anio);
        }

        if (!empty($mes)) {
            $builder->where("MONTH(rp_fecha)", $mes);
        }

        return $builder->orderBy('casis_registro_permiso.rp_fecha', 'DESC')
            ->orderBy('casis_registro_permiso.rp_hora_salida', 'DESC')
            ->findAll();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts        = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat = 'datetime';

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