<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EstadoExpedienteModel extends Model
{
    protected $table = 'selec_estados_expediente';
    protected $primaryKey = 'eex_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eex_codigo',
        'eex_nombre',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

