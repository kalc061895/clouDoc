<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaEtapaModel extends Model
{
    protected $table = 'selec_convocatoria_etapas';
    protected $primaryKey = 'cet_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cet_con_ide',
        'cet_eta_ide',
        'cet_fecha_inicio',
        'cet_hora_inicio',
        'cet_fecha_cierre',
        'cet_hora_cierre',
        'cet_estado',
        'cet_responsable_ide',
        'cet_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;

    /**
     * Obtiene el cronograma de la convocatoria ordenado cronológicamente.
     */
    public function getEtapasPorConvocatoria(int $convocatoriaId): array
    {
        return $this->select('
                selec_convocatoria_etapas.*,
                e.eta_nombre,
                e.eta_orden,
                e.eta_codigo,
                e.eta_descripcion
            ')
            ->join('selec_etapas e', 'e.eta_ide = selec_convocatoria_etapas.cet_eta_ide')
            ->where('cet_con_ide', $convocatoriaId)
            ->orderBy('e.eta_orden', 'ASC')
            ->orderBy('cet_fecha_inicio', 'ASC')
            ->findAll();
    }
}
