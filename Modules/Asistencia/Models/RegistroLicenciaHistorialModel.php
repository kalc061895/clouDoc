<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroLicenciaHistorialModel extends Model
{
    protected $table            = 'casis_registro_licencia_historial';
    protected $primaryKey       = 'his_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'his_rl_ide',           // ID de la licencia
        'his_accion',           // 'CREAR', 'EDITAR', 'ELIMINAR', 'APROBAR'
        'his_datos_anteriores', // JSON con los datos previos (null en CREAR)
        'his_datos_nuevos',     // JSON con los datos nuevos (null en ELIMINAR)
        'his_motivo_cambio',    // Razón del ajuste manual
        'his_ip',               // Dirección IP del usuario
        'created_by',           // ID del usuario logueado
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $updatedField  = ''; // Desactivado (solo escrituras auditadas)
    protected $deletedField  = ''; // No se borra el historial
}