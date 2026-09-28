<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class AnexoModel extends Model
{
    protected $table = 'selec_anexos';
    protected $primaryKey = 'ane_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'ane_con_ide',
        'ane_codigo',
        'ane_nombre',
        'ane_descripcion',
        'ane_obligatorio',
        'ane_condicion',
        'ane_archivo_ide',
        'ane_estado',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;

    public function getAnexosPorConvocatoria(int $convocatoriaId): array
    {
        return $this->select('selec_anexos.*,selec_convocatoria_documentos.cod_ruta')
             ->where('ane_con_ide', $convocatoriaId)
             ->join('selec_convocatoria_documentos', 'cod_ide = ane_archivo_ide', 'left')    
            ->findAll();
    }
}

