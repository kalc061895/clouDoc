<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EtapaModel extends Model
{
    protected $table = 'selec_etapas';
    protected $primaryKey = 'eta_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eta_codigo',
        'eta_nombre',
        'eta_descripcion',
        'eta_orden',
        'eta_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

