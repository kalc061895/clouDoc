<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ReclamoModel extends Model
{
    protected $table = 'selec_reclamos';
    protected $primaryKey = 'rec_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'rec_codigo',
        'rec_pto_ide',
        'rec_fecha',
        'rec_motivo',
        'rec_estado',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

