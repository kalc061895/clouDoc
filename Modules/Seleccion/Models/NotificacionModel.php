<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table = 'selec_notificaciones';
    protected $primaryKey = 'not_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'not_tno_ide',
        'not_usu_ide',
        'not_pos_ide',
        'not_email',
        'not_asunto',
        'not_mensaje',
        'not_fecha_envio',
        'not_estado',
        'not_error',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

