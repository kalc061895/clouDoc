<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulacionDeclaracionModel extends Model
{
    protected $table = 'selec_postulacion_declaraciones';
    protected $primaryKey = 'pde_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pde_pto_ide',
        'pde_tde_ide',
        'pde_acepta',
        'pde_fecha',
        'pde_ip',
        'pde_hash',
        'pde_exd_ide',
    ];
}

