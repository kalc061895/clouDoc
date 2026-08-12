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
}

