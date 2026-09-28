<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class DocumentoDigitalModel extends Model
{
    protected $table            = 'leg_documentos_digitales';
    protected $primaryKey       = 'doc_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'doc_ser_ide',
        'doc_sec_ide',
        'doc_titulo',
        'doc_numero_folio',
        'doc_fecha_emision',
        'doc_ruta_archivo',
        'doc_nombre_original',
        'doc_mime_type',
        'doc_peso_kb',
        'doc_observacion',
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
        'doc_ser_ide'      => 'required|is_natural_no_zero',
        'doc_titulo'       => 'required|min_length[3]|max_length[200]',
        'doc_ruta_archivo' => 'required',
    ];

    public function getPorServidor(int $servidorId, ?int $seccionId = null): array
    {
        $builder = $this->select('leg_documentos_digitales.*, leg_secciones.sec_nombre, leg_secciones.sec_numero')
            ->join('leg_secciones', 'leg_secciones.sec_ide = leg_documentos_digitales.doc_sec_ide', 'left')
            ->where('doc_ser_ide', $servidorId);

        if ($seccionId) {
            $builder->where('doc_sec_ide', $seccionId);
        }

        return $builder->orderBy('doc_ide', 'DESC')->findAll();
    }
}

