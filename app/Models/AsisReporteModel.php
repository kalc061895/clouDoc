<?php

namespace App\Models;

use CodeIgniter\Model;

class AsisReporteModel extends Model
{
    protected $table            = 'asis_reporte';
    protected $primaryKey       = 'rep_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'rep_user_ide',
        'rep_titulo',
        'rep_descripcion',
        'rep_inicio',
        'rep_fin',
        'rep_estado',
        'rep_fecha' // Aunque tiene un valor por defecto, es bueno incluirlo si planeas manipularlo
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
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


    public function getPersonalAttendanceSummaryByDNI(string $dni, int $rep_ide = null, string $fecha_inicio = null, string $fecha_fin = null)
    {
        $builder = $this->builder(); // Inicia la consulta desde asis_reporte_body

        $builder->selectSum('rb_faltas', 'total_faltas');
        $builder->selectSum('rb_tardanzas', 'total_tardanzas');

        // JOIN con asis_personal usando rb_perl_ide (que es la FK a perl_ide)
        // Usamos 'inner' join porque se espera que cada registro de reporte_body tenga un personal asociado
        $builder->join('asis_personal', 'asis_personal.perl_ide = asis_reporte_body.rb_perl_ide', 'inner');

        // JOIN con asis_persona usando perl_per_dni (que es la FK a per_dni)
        // Esto nos permite filtrar por el DNI de la persona
        $builder->join('asis_persona', 'asis_persona.per_dni = asis_personal.perl_per_dni', 'inner');

        // Filtrar por el DNI proporcionado
        $builder->where('asis_persona.per_dni', $dni);

        // Filtrar por ID de reporte si se proporciona
        if ($rep_ide !== null) {
            $builder->where('asis_reporte_body.rb_rep_ide', $rep_ide);
        }

        // Filtrar por rango de fechas si se proporcionan
        if ($fecha_inicio !== null && $fecha_fin !== null) {
            $builder->where('DATE(asis_reporte_body.rb_fecha) >=', $fecha_inicio);
            $builder->where('DATE(asis_reporte_body.rb_fecha) <=', $fecha_fin);
        }
        
        $query = $builder->get();
        $result = $query->getRowArray(); // Obtiene la primera fila como un array

        if ($result && ($result['total_faltas'] !== null || $result['total_tardanzas'] !== null)) {
            return [
                'total_faltas' => (int)$result['total_faltas'],
                'total_tardanzas' => (int)$result['total_tardanzas']
            ];
        }

        return null; // No se encontraron datos o los totales son nulos
    }
}
