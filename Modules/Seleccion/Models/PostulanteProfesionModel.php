<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulanteProfesionModel extends Model
{
    protected $table = 'selec_postulante_profesiones';
    protected $primaryKey = 'ppr_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'ppr_pos_ide',
        'ppr_pro_ide',
        'ppr_institucion',
        'ppr_grado',
        'ppr_titulo',
        'ppr_fecha',
        'ppr_colegiatura',
        'ppr_habilitacion',
        'ppr_documento_ide',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

