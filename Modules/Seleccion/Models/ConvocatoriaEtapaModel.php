<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaEtapaModel extends Model
{
    protected $table = 'selec_convocatoria_etapas';
    protected $primaryKey = 'cet_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cet_con_ide',
        'cet_eta_ide',
        'cet_fecha_inicio',
        'cet_hora_inicio',
        'cet_fecha_cierre',
        'cet_hora_cierre',
        'cet_estado',
        'cet_responsable_ide',
        'cet_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

