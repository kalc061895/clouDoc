<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EvaluacionReclamoModel extends Model
{
    protected $table = 'selec_evaluacion_reclamos';
    protected $primaryKey = 'ere_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'ere_rec_ide',
        'ere_com_ide',
        'created_by',
        'ere_decision',
        'ere_fundamento',
        'ere_observacion',
        'ere_fecha',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

