<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ProgramacionModel extends Model
{
    protected $table      = 'casis_programacion';
    protected $primaryKey = 'prog_ide';

    protected $returnType = 'array';

    protected $allowedFields = [

        'prog_perl_ide',
        'prog_fecha',
        'prog_th_ide',

        'prog_eup_ide',
        'prog_eus_ide',

        'prog_estado',
        'prog_observacion',

        'prog_es_cambio',
        'prog_origen_id',
        'prog_reg',
    ];

    protected $useTimestamps = false;

    /**
     * Relación lógica (opcional helper)
     */
    public function getProgramacionConDetalle($id)
    {
        return $this->select('
                casis_programacion.*,
                casis_turno_horario.th_hora_ingreso,
                casis_turno_horario.th_hora_salida,
                casis_turno.tur_nombre
            ')
            ->join('casis_turno_horario', 'casis_turno_horario.th_ide = casis_programacion.prog_th_ide')
            ->join('casis_turno', 'casis_turno.tur_ide = casis_turno_horario.th_tur_ide')
            ->where('prog_ide', $id)
            ->first();
    }
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
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
}
