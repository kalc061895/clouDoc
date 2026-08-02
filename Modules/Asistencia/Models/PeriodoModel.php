<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PeriodoModel extends Model
{
    protected $table            = 'casis_periodos';
    protected $primaryKey       = 'per_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Soft Deletes
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    // Timestamps
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'gco_ide',
        'per_anio',
        'per_mes',
        'per_nombre',
        'per_fecha_inicio',
        'per_fecha_fin',
        'per_estado',
        'per_observacion',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Reglas de Validación
    protected $validationRules = [
        'gco_ide'          => 'required|integer',
        'per_anio'         => 'required|integer|exact_length[4]',
        'per_mes'          => 'required|integer|greater_than[0]|less_than[13]',
        'per_nombre'       => 'required|min_length[3]|max_length[100]',
        'per_fecha_inicio' => 'required|valid_date',
        'per_fecha_fin'    => 'required|valid_date',
        'per_estado'       => 'required|in_list[PROGRAMADO,ABIERTO,EN_EVALUACION,CERRADO,REABIERTO]'
    ];

    protected $validationMessages = [
        'per_estado' => [
            'in_list' => 'El estado del período debe ser PROGRAMADO, ABIERTO, EN_EVALUACION, CERRADO o REABIERTO.'
        ]
    ];

    // Callbacks para Auditoría Automática de Usuarios
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreatedBy'];
    protected $beforeUpdate   = ['setUpdatedBy'];
    protected $beforeDelete   = ['setDeletedBy'];

    /**
     * Callback para registrar el usuario creador
     */
    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Callback para registrar el usuario que actualiza
     */
    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Callback para registrar el usuario que elimina (Soft Delete)
     */
    protected function setDeletedBy(array $data)
    {
        if (session()->has('user_id') && isset($data['id'])) {
            $db = \Config\Database::connect();
            $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
            
            $db->table($this->table)
               ->whereIn($this->primaryKey, $ids)
               ->update(['deleted_by' => session()->get('user_id')]);
        }
        return $data;
    }

    // =========================================================================
    // MÉTODOS DE NEGOCIO PARA EL CONTROL DE ASISTENCIA
    // =========================================================================

    /**
     * Obtener el período activo o editable de un Grupo de Corte para una fecha específica
     */
    public function obtenerPeriodoEditable(int $grupoCorteId, string $fecha): ?array
    {
        return $this->where('gco_ide', $grupoCorteId)
                    ->where('per_fecha_inicio <=', $fecha)
                    ->where('per_fecha_fin >=', $fecha)
                    ->whereIn('per_estado', ['ABIERTO', 'REABIERTO'])
                    ->first();
    }

    /**
     * Verificar si una fecha está bloqueada para un Grupo de Corte
     */
    public function esFechaBloqueada(int $grupoCorteId, string $fecha): bool
    {
        $periodo = $this->obtenerPeriodoEditable($grupoCorteId, $fecha);
        return $periodo === null; // Si no hay período abierto, la fecha está bloqueada
    }

    /**
     * Obtener todos los períodos de un Grupo de Corte ordenados por fecha
     */
    public function obtenerPorGrupo(int $grupoCorteId): array
    {
        return $this->where('gco_ide', $grupoCorteId)
                    ->orderBy('per_anio', 'DESC')
                    ->orderBy('per_mes', 'DESC')
                    ->findAll();
    }
}
