<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoNotificacionModel extends Model
{
    protected $table = 'selec_tipos_notificacion';
    protected $primaryKey = 'tno_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tno_codigo',
        'tno_nombre',
        'tno_asunto',
        'tno_plantilla',
        'tno_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

