<?php

namespace App\Models\Contratacion;

use CodeIgniter\Model;

class CalificacionPreviaModel extends Model
{
    protected $table            = 'calificacion_previa';
    protected $primaryKey       = 'id_calificacion_previa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_postulacion',
        'id_postulante',
        'id_convocatoria',
        'titulo',
        'titulo_especialidad',
        'doctorado',
        'maestria',
        'experiencia',
        'experiencia_meses',
        'capacitacion',
        'estado_evaluacion',
        'observacion',
        'evaluado_por',
        'created_at'
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
    public function obtenerResultados(int $idConvocatoria)
    {
        return $this->select('
                pl.codigo_plaza,
                pl.cargo,
                pos.dni,
                UPPER(CONCAT(pos.nombres, " ", pos.paterno, " ", pos.materno)) AS postulante,
                cp.titulo,
                cp.titulo_especialidad,
                cp.doctorado,
                cp.maestria,
                cp.experiencia,
                cp.experiencia_meses,
                cp.capacitacion,
                cp.estado_evaluacion,
                cp.observacion
            ')
            ->from('calificacion_previa cp')
            ->join('postulaciones po', 'po.id_postulacion = cp.id_postulacion')
            ->join('postulantes pos', 'pos.id_postulante = cp.id_postulante')
            ->join('plazas pl', 'pl.id_plaza = po.id_plaza')
            ->where('cp.id_convocatoria', $idConvocatoria)
            ->orderBy('pl.codigo_plaza', 'ASC')
            ->orderBy('pos.paterno', 'ASC')
            ->groupBy('cp.id_calificacion_previa')
            ->get()
            ->getResultArray();
    }
}
