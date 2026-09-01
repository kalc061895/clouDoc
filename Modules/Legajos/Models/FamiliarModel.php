<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class FamiliarModel extends Model
{
    protected $table            = 'leg_familiares';
    protected $primaryKey       = 'fam_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'fam_ser_ide',
        'fam_parentesco',
        'fam_tipo_documento',
        'fam_numero_documento',
        'fam_nombres',
        'fam_apellido_paterno',
        'fam_apellido_materno',
        'fam_fecha_nacimiento',
        'fam_sexo',
        'fam_es_derechohabiente',
        'fam_es_contacto_emergencia',
        'fam_telefono',
        'fam_direccion',
        'fam_adjunto_sustento',
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
        'fam_ser_ide'          => 'required|is_natural_no_zero',
        'fam_parentesco'       => 'required',
        'fam_nombres'          => 'required|min_length[2]|max_length[100]',
        'fam_apellido_paterno' => 'required|min_length[2]|max_length[100]',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('fam_ser_ide', $servidorId)
            ->orderBy('fam_parentesco', 'ASC')
            ->findAll();
    }
}

