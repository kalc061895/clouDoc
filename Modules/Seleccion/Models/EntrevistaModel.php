<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class EntrevistaModel extends Model
{
    protected $table = 'selec_entrevistas';
    protected $primaryKey = 'ent_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'ent_con_ide',
        'ent_nombre',
        'ent_fie_ide',
        'ent_puntaje_maximo',
        'ent_estado',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

}

