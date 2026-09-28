<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class RequisitoEvaluacionModel extends Model
{
    protected $table = 'selec_requisito_evaluaciones';
    protected $primaryKey = 'reqe_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'reqe_eva_ide',
        'reqe_req_ide',
        'reqe_resultado',
        'reqe_cumple',
        'reqe_observacion',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

