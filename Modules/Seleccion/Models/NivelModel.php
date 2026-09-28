<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class NivelModel extends Model
{
    protected $table = 'selec_niveles';
    protected $primaryKey = 'niv_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'niv_codigo',
        'niv_nombre',
        'niv_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

