<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulacionModel extends Model
{
    protected $table = 'selec_postulaciones';
    protected $primaryKey = 'pto_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pto_codigo',
        'pto_pos_ide',
        'pto_cco_ide',
        'pto_epo_ide',
        'pto_eex_ide',
        'pto_fecha_presentacion',
        'pto_confirmado',
        'pto_ip',
        'pto_hash_expediente',
        'pto_observacion',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

