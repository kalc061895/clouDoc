<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ActaParticipanteModel extends Model
{
    protected $table = 'selec_acta_participantes';
    protected $primaryKey = 'acp_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'acp_act_ide',
        'acp_usu_ide',
        'acp_nombre',
        'acp_cargo',
        'acp_firma',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;
}

