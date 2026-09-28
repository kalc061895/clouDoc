<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroLicenciaModel extends Model
{

    protected $table = 'casis_registro_licencia';
    protected $primaryKey = 'rl_ide';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'rl_lic_ide',
        'rl_perl_ide',
        'rl_motivo',
        'rl_fecha_inicio',
        'rl_fecha_fin',
        'rl_justificacion',
        'rl_numero_documento',
        'rl_fecha_documento',
        'rl_archivo',
        'rl_estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'rl_lic_ide' => 'required|integer',
        'rl_perl_ide' => 'required|integer',
        'rl_fecha_inicio' => 'required|valid_date',
        'rl_fecha_fin' => 'required|valid_date',
    ];

    public function obtenerPorPersonal($perlIde)
    {
        return $this->where('rl_perl_ide', $perlIde)
            ->orderBy('rl_fecha_inicio', 'DESC')
            ->findAll();
    }

    public function obtenerPorPersonalConTipo($perlIde)
    {
        return $this->select('casis_registro_licencia.*, casis_licencia.lic_nombre, casis_licencia.lic_abreviatura, casis_licencia.lic_remunerado')
            ->join('casis_licencia', 'casis_licencia.lic_ide = casis_registro_licencia.rl_lic_ide')
            ->where('casis_registro_licencia.rl_perl_ide', $perlIde)
            ->orderBy('casis_registro_licencia.rl_fecha_inicio', 'DESC')
            ->findAll();
    }

    public function vigentes()
    {
        $hoy = date('Y-m-d');

        return $this->where('rl_fecha_inicio <=', $hoy)
            ->where('rl_fecha_fin >=', $hoy)
            ->findAll();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat = 'datetime';

    // Validation
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
