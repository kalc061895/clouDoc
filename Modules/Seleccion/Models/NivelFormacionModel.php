<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class NivelFormacionModel extends Model
{
    protected $table = 'selec_niveles_formacion';
    protected $primaryKey = 'nfo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nfo_codigo',
        'nfo_nombre',
        'nfo_estado',
    ];
}

