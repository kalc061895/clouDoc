<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulantesOtroModel extends Model
{
    protected $table            = 'selec_postulante_otros';
    protected $primaryKey       = 'otr_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'otr_pos_ide',
        'otr_tipo',
        'otr_nombre',
        'otr_institucion',
        'otr_descripcion',
        'otr_fecha_expedicion',
        'otr_fecha_inicio',
        'otr_fecha_fin',
        'otr_folios',
        'otr_documento_ide',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Obtiene los registros opcionales junto con los datos de su archivo adjunto
     */
    public function obtenerConDocumento(int $postulanteId, ?string $tipo = null)
    {
        $builder = $this->select('selec_postulante_otros.*, doc.exd_nombre_original, doc.exd_ruta, doc.exd_mime')
            ->join('selec_expediente_documentos doc', 'doc.exd_ide = selec_postulante_otros.otr_documento_ide', 'left')
            ->where('otr_pos_ide', $postulanteId);

        if (!empty($tipo)) {
            $builder->where('otr_tipo', $tipo);
        }

        return $builder->findAll();
    }
}
