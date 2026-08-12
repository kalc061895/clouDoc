<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ExpedienteVersionModel extends Model
{
    protected $table = 'selec_expediente_versiones';
    protected $primaryKey = 'exv_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'exv_pto_ide',
        'exv_version',
        'exv_hash',
        'exv_fecha',
        'created_by',
        'exv_motivo',
        'exv_estado',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}

