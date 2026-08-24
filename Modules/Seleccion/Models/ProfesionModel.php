<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ProfesionModel extends Model
{
    protected $table = 'selec_profesiones';
    protected $primaryKey = 'pro_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pro_codigo',
        'pro_nombre',
        'pro_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

