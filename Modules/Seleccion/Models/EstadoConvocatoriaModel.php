<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EstadoConvocatoriaModel extends Model
{
    protected $table = 'selec_estados_convocatoria';
    protected $primaryKey = 'eco_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eco_codigo',
        'eco_nombre',
        'eco_descripcion',
        'eco_orden',
        'eco_estado',
    ];
}

