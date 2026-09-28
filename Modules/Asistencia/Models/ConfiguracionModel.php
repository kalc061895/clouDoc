<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table      = 'casis_configuracion';
    protected $primaryKey = 'cfg_ide';

    protected $returnType = 'array';

    protected $allowedFields = [
        'cfg_red_ide',
        'cfg_user_ide',
        'cfg_key',
        'cfg_value',
        'cfg_type',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    public function getConfig($key, $user_id = null, $red_id = null)
    {
        $builder = $this;

        // 1. Buscar configuración por usuario (prioridad alta)
        if ($user_id) {
            $user = $builder->where([
                'cfg_user_ide' => $user_id,
                'cfg_key'      => $key
            ])->first();

            if ($user) {
                return $this->castValue($user['cfg_value'], $user['cfg_type']);
            }
        }

        // 2. Buscar configuración por red
        if ($red_id) {
            $red = $builder->where([
                'cfg_red_ide'  => $red_id,
                'cfg_user_ide'  => null,
                'cfg_key'      => $key
            ])->first();

            if ($red) {
                return $this->castValue($red['cfg_value'], $red['cfg_type']);
            }
        }

        return null;
    }
    public function setUserConfig($user_id, $key, $value, $type = 'string')
    {
        return $this->save([
            'cfg_user_ide' => $user_id,
            'cfg_key'      => $key,
            'cfg_value'    => $value,
            'cfg_type'     => $type
        ]);
    }
    public function setRedConfig($red_id, $key, $value, $type = 'string')
    {
        return $this->save([
            'cfg_red_ide' => $red_id,
            'cfg_key'     => $key,
            'cfg_value'   => $value,
            'cfg_type'    => $type
        ]);
    }
    private function castValue($value, $type)
    {
        return match ($type) {

            'int'    => (int) $value,
            'bool'   => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json'   => json_decode($value, true),
            default  => $value,
        };
    }
}
