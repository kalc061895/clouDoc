<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class BonificacionModel extends Model
{
    protected $table = 'selec_bonificaciones';
    protected $primaryKey = 'bon_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'bon_pto_ide',
        'bon_tbo_ide',
        'bon_porcentaje',
        'bon_puntaje',
        'bon_documento_ide',
        'bon_resultado',
        'bon_fundamento',
        'created_by',
        'bon_fecha',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}

