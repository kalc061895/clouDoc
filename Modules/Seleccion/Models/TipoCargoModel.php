<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoCargoModel extends Model
{
    protected $table = 'selec_tipos_cargo';
    protected $primaryKey = 'tca_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tca_codigo',
        'tca_nombre',
        'tca_estado',
    ];
}

