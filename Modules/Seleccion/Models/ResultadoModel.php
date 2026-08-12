<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ResultadoModel extends Model
{
    protected $table = 'selec_resultados';
    protected $primaryKey = 'res_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'res_pto_ide',
        'res_version',
        'res_tipo',
        'res_puntaje_curricular',
        'res_puntaje_entrevista',
        'res_puntaje_bonificacion',
        'res_puntaje_total',
        'res_orden_merito',
        'res_condicion',
        'res_publicado',
        'res_fecha',
        'created_by',
        'res_anterior_ide',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}

