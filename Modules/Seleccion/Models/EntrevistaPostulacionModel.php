<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EntrevistaPostulacionModel extends Model
{
    protected $table = 'selec_entrevista_postulaciones';
    protected $primaryKey = 'enp_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'enp_ent_ide',
        'enp_pto_ide',
        'enp_fecha',
        'enp_puntaje',
        'enp_estado',
        'enp_observacion',
        'created_by',
    ];
}

