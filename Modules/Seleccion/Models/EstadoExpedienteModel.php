<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EstadoExpedienteModel extends Model
{
    protected $table = 'selec_estados_expediente';
    protected $primaryKey = 'eex_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eex_codigo',
        'eex_nombre',
    ];
}

