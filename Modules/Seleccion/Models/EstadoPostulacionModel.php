<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EstadoPostulacionModel extends Model
{
    protected $table = 'selec_estados_postulacion';
    protected $primaryKey = 'epo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'epo_codigo',
        'epo_nombre',
        'epo_descripcion',
        'epo_orden',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

