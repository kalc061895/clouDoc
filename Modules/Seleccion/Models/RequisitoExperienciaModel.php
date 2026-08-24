<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class RequisitoExperienciaModel extends Model
{
    protected $table = 'selec_requisito_experiencia';
    protected $primaryKey = 'rex_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'rex_req_ide',
        'rex_anios',
        'rex_meses',
        'rex_dias',
        'rex_tipo_experiencia',
        'rex_especifica',
        'rex_descripcion',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

