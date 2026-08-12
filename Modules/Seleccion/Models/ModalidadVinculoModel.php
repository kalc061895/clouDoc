<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ModalidadVinculoModel extends Model
{
    protected $table = 'selec_modalidades_vinculo';
    protected $primaryKey = 'mvi_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'mvi_codigo',
        'mvi_nombre',
        'mvi_estado',
    ];
}

