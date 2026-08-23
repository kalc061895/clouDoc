<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class CriterioEvaluacionModel extends Model
{
    protected $table = 'selec_criterios_evaluacion';
    protected $primaryKey = 'cri_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cri_fie_ide',
        'cri_codigo',
        'cri_nombre',
        'cri_descripcion',
        'cri_tipo',
        'cri_puntaje_maximo',
        'cri_obligatorio',
        'cri_orden',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}

