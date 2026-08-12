<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table = 'selec_evaluaciones';
    protected $primaryKey = 'eva_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eva_pto_ide',
        'eva_fie_ide',
        'eva_com_ide',
        'eva_usu_ide',
        'eva_tipo',
        'eva_estado',
        'eva_puntaje_total',
        'eva_fecha_inicio',
        'eva_fecha_fin',
        'eva_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

