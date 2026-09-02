<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PeriodoLogModel extends Model
{
    protected $table = 'casis_periodos_log';
    protected $primaryKey = 'log_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Desactivamos soft deletes y updates por integridad de auditoría
    protected $useSoftDeletes = false;

    // Solo requerimos el timestamp de creación
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $allowedFields = [
        'per_ide',
        'estado_anterior',
        'estado_nuevo',
        'log_accion',
        'log_motivo',
        'log_documento_sustento',
        'log_ip_address',
        'created_by'
    ];

    protected $validationRules = [
        'per_ide' => 'required|integer',
        'estado_nuevo' => 'required|string',
        'log_accion' => 'required|string|max_length[50]'
    ];

    // Callback para auto-completar usuario e IP antes de insertar
    protected $allowCallbacks = true;
    protected $beforeInsert = ['completarDatosAuditoria'];

    /**
     * Completa el ID del usuario actual y la dirección IP desde la sesión/Request
     */
    protected function completarDatosAuditoria(array $data): array
    {
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        }

        $request = \Config\Services::request();
        $data['data']['log_ip_address'] = $request->getIPAddress();

        return $data;
    }

    // =========================================================================
    // MÉTODOS CONSULTA Y REGISTRO DE AUDITORÍA
    // =========================================================================

    /**
     * Registrar una transición de estado de forma centralizada
     */
    public function registrarEvento(
        int $periodoId,
        ?string $estadoAnterior,
        string $estadoNuevo,
        string $accion,
        ?string $motivo = null,
        ?string $documento = null
    ): bool {
        return (bool) $this->insert([
            'per_ide' => $periodoId,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'log_accion' => $accion,
            'log_motivo' => $motivo,
            'log_documento_sustento' => $documento
        ]);
    }

    /**
     * Obtener el historial completo de cambios de un período con los datos del usuario
     */
    public function obtenerHistorialPorPeriodo(int $periodoId): array
    {
        return $this->select('casis_periodos_log.*, u.username, u.nombre_completo')
            ->join('usuarios u', 'u.id = casis_periodos_log.created_by', 'left')
            ->where('per_ide', $periodoId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}