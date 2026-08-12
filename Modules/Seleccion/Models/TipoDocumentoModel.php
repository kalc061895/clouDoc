<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoDocumentoModel extends Model
{
    protected $table = 'selec_tipos_documento';
    protected $primaryKey = 'tdo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tdo_codigo',
        'tdo_nombre',
        'tdo_estado',
    ];
}

