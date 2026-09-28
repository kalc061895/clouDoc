<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CasisAsistenciaModel extends Model
{
    protected $table      = 'casis_asistencia';
    protected $primaryKey = 'asi_ide';

    protected $returnType = 'array';

    protected $allowedFields = [

        'asi_imp_ide',
        'asi_perl_ide',
        'asi_numero_documento',
        'asi_fecha_hora',
        'asi_dispositivo',
        'asi_origen',
        'asi_ip_log',
        'asi_user_ide',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /**
     * Obtener marcaciones por persona
     */
    public function getPorPersona($perlIde)
    {
        return $this->where('asi_perl_ide', $perlIde)
            ->orderBy('asi_fecha_hora', 'ASC')
            ->findAll();
    }

    /**
     * Obtener marcaciones por fecha
     */
    public function getPorFecha($fecha)
    {
        return $this->where('DATE(asi_fecha_hora)', $fecha)
            ->findAll();
    }

    /**
     * Obtener marcaciones por rango
     */
    public function getPorRango($inicio, $fin)
    {
        return $this->where('asi_fecha_hora >=', $inicio)
            ->where('asi_fecha_hora <=', $fin)
            ->orderBy('asi_fecha_hora', 'ASC')
            ->findAll();
    }

    /**
     * Obtener marcaciones de una importación
     */
    public function getPorImportacion($impIde)
    {
        return $this->where('asi_imp_ide', $impIde)
            ->findAll();
    }

    /**
     * Validar si existe marcación
     */
    public function existeMarcacion($perlIde, $fechaHora)
    {
        return $this->where([
            'asi_perl_ide'   => $perlIde,
            'asi_fecha_hora' => $fechaHora
        ])->first();
    }

    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
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
}
