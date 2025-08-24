<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table            = 'asis_personal';
    protected $primaryKey       = 'perl_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'perl_per_dni',
        'perl_codigo',
        'perl_tipo_contrato',
        'perl_fecha_inicio',
        'perl_esp_ide',
        'perl_ser_ide',
        'perl_estado',
        'perl_est_ide',
        'perl_nivel',
        'perl_plaza',
        'perl_segunda_especialidad',
        'perl_fecha_termino',
        'perl_observacion',
        'perl_tipo_colegio',
        'perl_numero_colegio',
        'perl_regimen_laboral',
        // 'perl_fecha_registro' no se incluye aquí porque tiene CURRENT_TIMESTAMP en la BD
        // y se gestiona automáticamente por la base de datos.
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


    /**
     * Obtiene la información del personal con detalles de asis_persona, asis_especialidad, asis_servicio y asis_estado_ide.
     *
     * @param int|null $id Opcional. El ID del personal (perl_ide) para obtener un registro específico.
     * @return array|object|null Retorna un array de resultados, un objeto, o null si no se encuentra.
     */
    public function getPersonalWithDetails($id = null)
    {
        // Selecciona todos los campos de asis_personal
        $this->select('asis_personal.*');

        // Selecciona campos específicos de asis_persona para evitar conflictos de nombres
        $this->select('asis_persona.per_paterno, asis_persona.per_materno, asis_persona.per_nombre, asis_persona.per_email');

        // Selecciona campos específicos de asis_especialidad
        // Asume que la tabla asis_especialidad tiene un campo 'esp_nombre'
        $this->select('asis_especialidad.esp_nombre AS nombre_especialidad');

        // Selecciona campos específicos de asis_servicio
        // Asume que la tabla asis_servicio tiene un campo 'ser_nombre'
        $this->select('asis_servicio.ser_nombre AS nombre_servicio');

        // Selecciona campos específicos de asis_estado_ide
        // Asume que la tabla asis_estado_ide tiene un campo 'est_nombre'
        //$this->select('asis_estado_ide.est_nombre AS nombre_estado');


        // Realiza el JOIN con la tabla asis_persona (usando perl_per_dni)
        $this->join('asis_persona', 'asis_persona.per_dni = asis_personal.perl_per_dni', 'left');

        // Realiza el JOIN con la tabla asis_especialidad (usando perl_esp_ide)
        // Asegúrate de que 'asis_especialidad' y 'esp_ide' sean los nombres correctos de tu tabla y su PK.
        $this->join('asis_especialidad', 'asis_especialidad.esp_ide = asis_personal.perl_esp_ide', 'left');

        // Realiza el JOIN con la tabla asis_servicio (usando perl_ser_ide)
        // Asegúrate de que 'asis_servicio' y 'ser_ide' sean los nombres correctos de tu tabla y su PK.
        $this->join('asis_servicio', 'asis_servicio.ser_ide = asis_personal.perl_ser_ide', 'left');

        // Realiza el JOIN con la tabla asis_estado_ide (usando perl_est_ide)
        // Asegúrate de que 'asis_estado_ide' y 'est_ide' sean los nombres correctos de tu tabla y su PK.
        //$this->join('asis_estado_ide', 'asis_estado_ide.est_ide = asis_personal.perl_est_ide', 'left');


        // Si se proporciona un ID, busca un registro específico
        if ($id !== null) {
            return $this->where('perl_per_dni',$id)->first();
        }

        // Si no se proporciona ID, retorna todos los registros con los JOINs
        return $this->findAll();
    }
}
