<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class GrupoCorteModel extends Model
{
    protected $table            = 'casis_grupos_corte';
    protected $primaryKey       = 'gco_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'diresa_id',
        'red_id',
        'microred_id',
        'establecimiento_id',
        'gco_nombre',
        'gco_mco_ide',
        'gco_dia_inicio',
        'gco_dia_fin',
        'gco_mes_desfasado',
        'gco_estado',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $validationRules = [
        'gco_nombre'     => 'required|min_length[3]|max_length[150]',
        'gco_mco_ide'    => 'permit_empty|integer',
        'gco_dia_inicio' => 'required|integer|greater_than[0]|less_than[32]',
        'gco_dia_fin'    => 'required|integer|greater_than[0]|less_than[32]',
        'gco_estado'     => 'required|in_list[ACTIVO,INACTIVO]'
    ];

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreatedBy'];
    protected $beforeUpdate   = ['setUpdatedBy'];
    protected $beforeDelete   = ['setDeletedBy'];

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
            $db  = \Config\Database::connect();
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
     * Resuelve el grupo de corte en UNA SOLA consulta aplicando prioridad por jerarquía y modalidad.
     */
    public function resolverGrupoCorte(array $personal)
    {
        $diresaId          = !empty($personal['diresa_id']) ? (int) $personal['diresa_id'] : null;
        $redId             = !empty($personal['red_id']) ? (int) $personal['red_id'] : null;
        $microredId        = !empty($personal['microred_id']) ? (int) $personal['microred_id'] : null;
        $establecimientoId = !empty($personal['perl_est_ide']) ? (int) $personal['perl_est_ide'] : null;
        $modalidadId       = !empty($personal['perl_mco_ide']) ? (int) $personal['perl_mco_ide'] : null;

        $builder = $this->where('gco_estado', 'ACTIVO');

        // 1. Filtrar por coincidencias geográficas / administrativas posibles
        $builder->groupStart();
        if ($establecimientoId) $builder->orWhere('establecimiento_id', $establecimientoId);
        if ($microredId)        $builder->orWhere('microred_id', $microredId);
        if ($redId)             $builder->orWhere('red_id', $redId);
        if ($diresaId)          $builder->orWhere('diresa_id', $diresaId);
        $builder->groupEnd();

        // 2. Filtrar por Modalidad (La específica del trabajador O la general NULL)
        if ($modalidadId !== null) {
            $builder->groupStart()
                ->where('gco_mco_ide', $modalidadId)
                ->orWhere('gco_mco_ide', null)
                ->groupEnd();
        } else {
            $builder->where('gco_mco_ide', null);
        }

        // 3. PRIORIZACIÓN (Jerarquía: Est > Mic > Red > Dire)
        $orderJerarquia = "FIELD(COALESCE(establecimiento_id, microred_id, red_id, diresa_id), " .
            implode(',', array_filter([$establecimientoId, $microredId, $redId, $diresaId])) . ")";

        $builder->orderBy($orderJerarquia, 'ASC', false);

        // 4. PRIORIZACIÓN (Modalidad específica antes que la general NULL)
        if ($modalidadId !== null) {
            $builder->orderBy("CASE WHEN gco_mco_ide = {$modalidadId} THEN 1 ELSE 2 END", 'ASC', false);
        }

        return $builder->first();
    }
}
