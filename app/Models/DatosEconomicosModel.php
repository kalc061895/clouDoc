<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosEconomicosModel extends Model
{
    protected $table            = 'datos_economicos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'dato_personal_id',
        'ingreso_mensual_trabajador',
        'ingreso_mensual_conyugue',
        'ingreso_mensual_otros',
        'egreso_servicios_agua',
        'egreso_servicios_luz',
        'egreso_servicios_telefono',
        'egreso_servicios_cable',
        'egreso_servicios_internet',
        'egreso_servicios_otros',
        'egreso_servicios_total',
        'egreso_familiar_alimentacion',
        'egreso_familiar_medicamento',
        'egreso_familiar_vestimenta',
        'egreso_familiar_educacion',
        'egreso_familiar_movilidad',
        'egreso_familiar_otros',
        'egreso_familiar_total',
        'egreso_otros_deudas',
        'egreso_otros_seguro_salud',
        'egreso_otros_seguro_vida',
        'egreso_otros_seguro_sepelio',
        'egreso_otros_vehiculo',
        'egreso_otros_otros',
        'egreso_otros_total',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validaciones
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
