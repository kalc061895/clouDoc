<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class GrupoCorteModel extends Model
{
    protected $table = 'casis_grupos_corte';
    protected $primaryKey = 'gco_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'diresa_id',
        'red_id',
        'microred_id',
        'establecimiento_id',
        'gco_nombre',
        'gco_regimen_laboral',
        'gco_dia_inicio',
        'gco_dia_fin',
        'gco_mes_desfasado',
        'gco_estado',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $validationRules = [
        'gco_nombre' => 'required|min_length[3]|max_length[150]',
        'gco_dia_inicio' => 'required|integer|greater_than[0]|less_than[32]',
        'gco_dia_fin' => 'required|integer|greater_than[0]|less_than[32]',
        'gco_estado' => 'required|in_list[ACTIVO,INACTIVO]'
    ];

    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];
    protected $beforeDelete = ['setDeletedBy'];

    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }

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
    // LÓGICA DE HERENCIA Y RESOLUCIÓN DE CORTE SEGÚN JERARQUÍA
    // =========================================================================

    /**
     * Resuelve el Grupo de Corte aplicable para un trabajador / establecimiento.
     * Busca de lo más específico a lo más general:
     * 1. Corte propio del Establecimiento
     * 2. Corte de la Microred
     * 3. Corte de la Red de Salud
     * 4. Corte General de la DIRESA
     */
    public function resolverGrupoCorte(
        int $diresaId,
        ?int $redId = null,
        ?int $microredId = null,
        ?int $establecimientoId = null,
        ?string $regimenLaboral = null
    ): ?array {
        $builder = $this->where('gco_estado', 'ACTIVO');

        if ($regimenLaboral) {
            $builder->groupStart()
                ->where('gco_regimen_laboral', $regimenLaboral)
                ->orWhere('gco_regimen_laboral', null)
                ->groupEnd();
        }

        // 1. Intentar por Establecimiento
        if ($establecimientoId) {
            $corte = (clone $builder)->where('establecimiento_id', $establecimientoId)->first();
            if ($corte)
                return $corte;
        }

        // 2. Intentar por Microred
        if ($microredId) {
            $corte = (clone $builder)->where('microred_id', $microredId)->where('establecimiento_id', null)->first();
            if ($corte)
                return $corte;
        }

        // 3. Intentar por Red
        if ($redId) {
            $corte = (clone $builder)->where('red_id', $redId)->where('microred_id', null)->where('establecimiento_id', null)->first();
            if ($corte)
                return $corte;
        }

        // 4. Fallback: Corte General de la DIRESA
        return (clone $builder)->where('diresa_id', $diresaId)
            ->where('red_id', null)
            ->where('establecimiento_id', null)
            ->first();
    }
}
