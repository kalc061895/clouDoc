<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoConvocatoriaModel extends Model
{
    protected $table = 'selec_tipos_convocatoria';
    protected $primaryKey = 'tco_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tco_codigo',
        'tco_nombre',
        'tco_descripcion',
        'tco_estado',
    ];
}

