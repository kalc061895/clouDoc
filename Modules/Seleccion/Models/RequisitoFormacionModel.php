<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class RequisitoFormacionModel extends Model
{
    protected $table = 'selec_requisito_formacion';
    protected $primaryKey = 'rfo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'rfo_req_ide',
        'rfo_nfo_ide',
        'rfo_pro_ide',
        'rfo_grado',
        'rfo_obligatorio',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

