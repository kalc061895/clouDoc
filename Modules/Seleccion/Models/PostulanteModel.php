<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulanteModel extends Model
{
    protected $table = 'selec_postulantes';
    protected $primaryKey = 'pos_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pos_tdo_ide',
        'pos_documento',
        'pos_nombres',
        'pos_apellido_paterno',
        'pos_apellido_materno',
        'pos_fecha_nacimiento',
        'pos_sexo',
        'pos_direccion',
        'pos_dep_ide',
        'pos_prv_ide',
        'pos_dis_ide',
        'pos_telefono',
        'pos_email',
        'pos_password',
        'pos_email_verificado',
        'pos_estado',
        'pos_user_id',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

