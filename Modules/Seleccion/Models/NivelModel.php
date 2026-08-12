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
}

