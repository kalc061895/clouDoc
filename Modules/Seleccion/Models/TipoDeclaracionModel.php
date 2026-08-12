<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoDeclaracionModel extends Model
{
    protected $table = 'selec_tipos_declaracion';
    protected $primaryKey = 'tde_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tde_codigo',
        'tde_nombre',
        'tde_contenido',
        'tde_estado',
    ];
}

