<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ValidacionPostulacionModel extends Model
{
    protected $table = 'selec_validaciones_postulacion';
    protected $primaryKey = 'vpo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'vpo_pto_ide',
        'vpo_codigo',
        'vpo_nombre',
        'vpo_resultado',
        'vpo_observacion',
        'vpo_fecha',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

