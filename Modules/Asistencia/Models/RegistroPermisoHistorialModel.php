<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroPermisoHistorialModel extends Model
{
    protected $table            = 'casis_registro_permiso_historial';
    protected $primaryKey       = 'rph_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'rph_rp_ide',           // ID del registro de permiso
        'rph_accion',           // 'CREAR', 'EDITAR', 'ELIMINAR', 'APROBAR'
        'rph_datos_anteriores', // JSON con los datos previos (null en CREAR)
        'rph_datos_nuevos',     // JSON con los datos nuevos (null en ELIMINAR)
        'rph_motivo_cambio',    // Razón del ajuste manual
        'rph_ip',               // Dirección IP del usuario
        'created_by',           // ID del usuario logueado
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $updatedField  = ''; // Desactivado (solo escrituras auditadas)
    protected $deletedField  = ''; // No se borra el historial
}
