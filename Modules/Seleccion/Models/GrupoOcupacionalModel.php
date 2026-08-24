<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class GrupoOcupacionalModel extends Model
{
    protected $table = 'selec_grupos_ocupacionales';
    protected $primaryKey = 'gru_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'gru_codigo',
        'gru_nombre',
        'gru_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

