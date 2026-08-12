<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaModel extends Model
{

    protected $table            = 'selec_convocatorias';
    protected $primaryKey       = 'con_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Manejo puro con arreglos asociativos
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'con_codigo',
        'con_numero',
        'con_nombre',
        'con_tco_ide',
        'con_eco_ide',
        'con_regimen_laboral',
        'con_anio',
        'con_resolucion',
        'con_descripcion',
        'con_fecha_publicacion',
        'con_fecha_inicio',
        'con_fecha_cierre',
        'con_responsable_ide',
        'con_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'con_codigo'  => 'required|max_length[50]|is_unique[selec_convocatorias.con_codigo,con_ide,{con_ide}]',
        'con_numero'  => 'required|max_length[50]',
        'con_nombre'  => 'required|max_length[255]',
        'con_tco_ide' => 'required|is_natural_no_zero',
        'con_eco_ide' => 'required|is_natural_no_zero',
        'con_anio'    => 'required|numeric|exact_length[4]',
    ];

    public function getListadoDatatable(): array
    {
        return $this->select('selec_convocatorias.*, t.tco_nombre as tipo, e.eco_nombre as estado')
            ->join('selec_tipos_convocatoria t', 't.tco_ide = selec_convocatorias.con_tco_ide', 'left')
            ->join('selec_estados_convocatoria e', 'e.eco_ide = selec_convocatorias.con_eco_ide', 'left')
            ->findAll();
    }
}
