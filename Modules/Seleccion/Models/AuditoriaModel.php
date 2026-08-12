<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class AuditoriaModel extends Model
{
    protected $table = 'selec_auditoria';
    protected $primaryKey = 'aud_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'aud_usu_ide',
        'aud_rol_ide',
        'aud_fecha',
        'aud_ip',
        'aud_accion',
        'aud_modulo',
        'aud_tabla',
        'aud_registro_ide',
        'aud_valor_anterior',
        'aud_valor_nuevo',
        'aud_motivo',
        'aud_pto_ide',
        'aud_con_ide',
        'aud_eta_ide',
    ];
}

