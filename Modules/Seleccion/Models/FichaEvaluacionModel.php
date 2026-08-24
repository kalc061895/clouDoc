<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class FichaEvaluacionModel extends Model
{
    protected $table = 'selec_fichas_evaluacion';
    protected $primaryKey = 'fie_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'fie_con_ide',
        'fie_nombre',
        'fie_tipo',
        'fie_version',
        'fie_estado',
        'fie_puntaje_maximo',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

