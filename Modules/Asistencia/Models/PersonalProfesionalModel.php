<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonalProfesionalModel extends Model
{
    protected $table = 'casis_personal_profesion';
    protected $primaryKey = 'pp_ide';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $protectFields = true;

    protected $allowedFields = [

        'pp_perl_ide',
        'pp_principal',

        'pp_pro_ide',
        'pp_col_ide',
        'pp_se_ide',

        'pp_numero_colegiatura',
        'pp_rne',

        'pp_habilitado',
        'pp_fecha_habilitacion',
        'pp_fecha_vencimiento',
        'pp_observacion',

        'created_by',
        'updated_by',
        'deleted_by',

    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validaciones
    protected $validationRules = [

        'pp_perl_ide' => 'required|integer',
        'pp_pro_ide' => 'required|integer',

        'pp_principal' => 'permit_empty|in_list[0,1]',

        'pp_col_ide' => 'permit_empty|integer',
        'pp_se_ide' => 'permit_empty|integer',

        'pp_numero_colegiatura' => 'permit_empty|max_length[30]',
        'pp_rne' => 'permit_empty|max_length[30]',

        'pp_habilitado' => 'permit_empty|in_list[0,1]',

        'pp_fecha_habilitacion' => 'permit_empty|valid_date',
        'pp_fecha_vencimiento' => 'permit_empty|valid_date',

        'pp_observacion' => 'permit_empty',

    ];

    protected $validationMessages = [];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    protected $beforeInsert = [];

    protected $beforeUpdate = [];

    protected $beforeFind = [];
    protected $afterFind = [];

    protected $afterDelete = [];
    // ... Tus propiedades anteriores se mantienen ...

    protected $afterInsert = ['guardarHistorialInsert'];
    protected $afterUpdate = ['guardarHistorialUpdate'];
    protected $beforeDelete = ['guardarHistorialDelete'];

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
            $db->table('casis_personal_profesion_historial')->insert($registro);
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
                $db->table('casis_personal_profesion_historial')->insert($registro);
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
                $db->table('casis_personal_profesion_historial')->insert($registro);
            }
        }
        return $data;
    }
}