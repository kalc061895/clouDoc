<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ActaModel extends Model
{
    protected $table = 'selec_actas';
    protected $primaryKey = 'act_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'act_con_ide',
        'act_eta_ide',
        'act_numero',
        'act_tipo',
        'act_fecha',
        'act_descripcion',
        'act_acuerdos',
        'act_observaciones',
        'act_documento_ide',
        'act_estado',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

