<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulacionAnexoModel extends Model
{
    protected $table = 'selec_postulacion_anexos';
    protected $primaryKey = 'pan_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pan_pto_ide',
        'pan_ane_ide',
        'pan_exd_ide',
        'pan_presentado',
        'pan_observacion',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

