<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class SancionMeritoModel extends Model
{
    protected $table            = 'leg_meritos_sanciones';
    protected $primaryKey       = 'msa_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'msa_ser_ide',
        'msa_tipo',
        'msa_subtipo',
        'msa_acto_resolutivo',
        'msa_fecha_acto',
        'msa_entidad_emisora',
        'msa_numero_expediente_pad',
        'msa_descripcion_motivo',
        'msa_periodo_sancion_dias',
        'msa_fecha_inicio_efecto',
        'msa_fecha_fin_efecto',
        'msa_esta_rehabilitado',
        'msa_adjunto_sustento',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'msa_ser_ide'         => 'required|is_natural_no_zero',
        'msa_tipo'            => 'required|in_list[MERITO,SANCION]',
        'msa_subtipo'         => 'required',
        'msa_acto_resolutivo' => 'required|min_length[3]|max_length[120]',
        'msa_fecha_acto'      => 'required|valid_date',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('msa_ser_ide', $servidorId)
            ->orderBy('msa_fecha_acto', 'DESC')
            ->findAll();
    }
}

