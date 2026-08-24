<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ReglaPuntajeModel extends Model
{
    protected $table = 'selec_reglas_puntaje';
    protected $primaryKey = 'rpu_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'rpu_cri_ide',
        'rpu_tipo',
        'rpu_condicion',
        'rpu_valor_min',
        'rpu_valor_max',
        'rpu_puntaje',
        'rpu_formula',
        'rpu_orden',
        'rpu_estado',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

