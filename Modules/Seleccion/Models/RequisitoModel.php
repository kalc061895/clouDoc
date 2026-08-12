<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class RequisitoModel extends Model
{
    protected $table = 'selec_requisitos';
    protected $primaryKey = 'req_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'req_cco_ide',
        'req_codigo',
        'req_nombre',
        'req_descripcion',
        'req_tipo',
        'req_obligatorio',
        'req_puntaje',
        'req_orden',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

