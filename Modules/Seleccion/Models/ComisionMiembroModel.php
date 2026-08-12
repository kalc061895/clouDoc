<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ComisionMiembroModel extends Model
{
    protected $table = 'selec_comision_miembros';
    protected $primaryKey = 'cmi_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cmi_com_ide',
        'cmi_usu_ide',
        'cmi_tipo',
        'cmi_fecha_inicio',
        'cmi_fecha_fin',
        'cmi_documento_ide',
        'cmi_estado',
    ];
}

