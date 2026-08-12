<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulanteCapacitacionModel extends Model
{
    protected $table = 'selec_postulante_capacitaciones';
    protected $primaryKey = 'pca_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pca_pos_ide',
        'pca_nombre',
        'pca_institucion',
        'pca_tipo',
        'pca_fecha',
        'pca_horas',
        'pca_modalidad',
        'pca_documento_ide',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

