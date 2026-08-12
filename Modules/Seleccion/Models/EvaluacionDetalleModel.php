<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EvaluacionDetalleModel extends Model
{
    protected $table = 'selec_evaluacion_detalles';
    protected $primaryKey = 'evd_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'evd_eva_ide',
        'evd_cri_ide',
        'evd_resultado',
        'evd_cumple',
        'evd_puntaje',
        'evd_observacion',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

