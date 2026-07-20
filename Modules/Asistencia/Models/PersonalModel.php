<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table = 'casis_personal';
    protected $primaryKey = 'perl_ide';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $protectFields = true;

    /*
    |--------------------------------------------------------------------------
    | Campos permitidos
    |--------------------------------------------------------------------------
    */
    protected $allowedFields = [
        'perl_per_ide',
        'perl_est_ide',
        'perl_ofi_ide',
        'perl_car_ide',
        'perl_mco_ide',
        'perl_se_ide',
        'perl_codigo',
        'perl_fecha_inicio',
        'perl_fecha_termino',
        'perl_fecha_cese',
        'perl_numero_colegiatura',
        'perl_plaza',
        'perl_nivel',
        'perl_estado',
        'perl_regimen_laboral',
        'perl_observacion',

        // Auditoría
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    */
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    /*
    |--------------------------------------------------------------------------
    | Timestamps
    |--------------------------------------------------------------------------
    */
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /*
    |--------------------------------------------------------------------------
    | Callbacks Auditoría
    |--------------------------------------------------------------------------
    */
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    /*
    |--------------------------------------------------------------------------
    | Validaciones
    |--------------------------------------------------------------------------
    */
    protected $validationRules = [

        'perl_per_ide' => 'required|is_natural_no_zero',

        'perl_est_ide' => 'required|is_natural_no_zero',

        'perl_ofi_ide' => 'permit_empty|is_natural_no_zero',

        'perl_car_ide' => 'permit_empty|is_natural_no_zero',

        'perl_mco_ide' => 'required|is_natural_no_zero',

        'perl_se_ide' => 'permit_empty|is_natural_no_zero',

        'perl_codigo' =>
            'permit_empty|max_length[20]|is_unique[casis_personal.perl_codigo,perl_ide,{perl_ide}]',

        'perl_fecha_inicio' =>
            'permit_empty|valid_date',

        'perl_fecha_termino' =>
            'permit_empty|valid_date',

        'perl_estado' =>
            'permit_empty',
    ];

    protected $validationMessages = [

        'perl_per_ide' => [
            'required' => 'Debe seleccionar una persona.'
        ],

        'perl_est_ide' => [
            'required' => 'Debe seleccionar un establecimiento.'
        ],

        'perl_mco_ide' => [
            'required' => 'Debe seleccionar una modalidad.'
        ],

        'perl_codigo' => [
            'is_unique' => 'El código del trabajador ya existe.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /*
    |--------------------------------------------------------------------------
    | Auditoría automática (Shield)
    |--------------------------------------------------------------------------
    */

    protected function setCreatedBy(array $data)
    {
        if (function_exists('auth') && auth()->loggedIn()) {
            $data['data']['created_by'] = auth()->id();
        }

        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (function_exists('auth') && auth()->loggedIn()) {
            $data['data']['updated_by'] = auth()->id();
        }

        return $data;
    }

    protected function setDeletedBy(array $data)
    {
        if (!function_exists('auth')) {
            return $data;
        }

        if (!auth()->loggedIn()) {
            return $data;
        }

        if (!empty($data['id'])) {

            $this->builder()
                ->whereIn($this->primaryKey, (array) $data['id'])
                ->update([
                    'deleted_by' => auth()->id()
                ]);
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Consultas
    |--------------------------------------------------------------------------
    */

    public function activos()
    {
        return $this->where('perl_estado', 'ACTIVO');
    }

    public function porEstablecimiento(int $estIde)
    {
        return $this->where('perl_est_ide', $estIde);
    }

    public function porOficina(int $ofiIde)
    {
        return $this->where('perl_ofi_ide', $ofiIde);
    }

    public function porCargo(int $carIde)
    {
        return $this->where('perl_car_ide', $carIde);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener ficha completa del trabajador
    |--------------------------------------------------------------------------
    */

    public function obtenerFicha(int $perlIde): ?array
    {
        return $this->select("
                casis_personal.*,

                p.per_numero_documento,
                p.per_paterno,
                p.per_materno,
                p.per_nombre,

                establecimiento.est_nombre,

                cargo.car_nombre,

                oficina.ofi_nombre,

                modalidad.mco_nombre
            ")

            ->join(
                'casis_persona p',
                'p.per_ide = casis_personal.perl_per_ide'
            )

            ->join(
                'casis_establecimiento establecimiento',
                'establecimiento.est_ide = casis_personal.perl_est_ide',
                'left'
            )

            ->join(
                'casis_cargo cargo',
                'cargo.car_ide = casis_personal.perl_car_ide',
                'left'
            )

            ->join(
                'casis_oficina oficina',
                'oficina.ofi_ide = casis_personal.perl_ofi_ide',
                'left'
            )

            ->join(
                'casis_modalidad_contrato modalidad',
                'modalidad.mco_ide = casis_personal.perl_mco_ide',
                'left'
            )

            ->where('casis_personal.perl_ide', $perlIde)

            ->first();
    }

    // ... Tus propiedades anteriores se mantienen ...

    protected $afterInsert = ['guardarHistorialInsert'];
    protected $afterUpdate = ['guardarHistorialUpdate'];
    protected $beforeDelete = ['guardarHistorialDelete', 'setDeletedBy']; // Mantienes tu auditoría y agregas la nuestra

    protected function guardarHistorialInsert(array $data)
    {
        if (!$data['result'])
            return $data;
        $db = \Config\Database::connect();
        $registro = $this->find($data['id']);
        if ($registro) {
            $registro['hist_accion'] = 'INSERT';
            $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
            $registro['hist_creado_en'] = date('Y-m-d H:i:s');
            $db->table('casis_personal_historial')->insert($registro);
        }
        return $data;
    }

    protected function guardarHistorialUpdate(array $data)
    {
        if (!$data['result'])
            return $data;
        $db = \Config\Database::connect();
        $ids = (array) $data['id'];
        foreach ($ids as $id) {
            $registro = $this->find($id);
            if ($registro) {
                $registro['hist_accion'] = 'UPDATE';
                $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
                $registro['hist_creado_en'] = date('Y-m-d H:i:s');
                $db->table('casis_personal_historial')->insert($registro);
            }
        }
        return $data;
    }

    protected function guardarHistorialDelete(array $data)
    {
        $db = \Config\Database::connect();
        $ids = (array) $data['id'];
        foreach ($ids as $id) {
            $registro = $this->find($id);
            if ($registro) {
                $registro['hist_accion'] = 'DELETE';
                $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
                $registro['hist_creado_en'] = date('Y-m-d H:i:s');
                $db->table('casis_personal_historial')->insert($registro);
            }
        }
        return $data;
    }
}
