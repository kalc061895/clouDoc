<?php

namespace App\Models;

use CodeIgniter\Model;

class MesesModel extends Model
{
    protected $table = 'meses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // Cambia a 'object' si prefieres trabajar con objetos

    // Habilitar borrado suave (Soft Deletes)
    protected $useSoftDeletes = true;

    // Campos permitidos para inserción/actualización masiva
    protected $allowedFields = [
        'nombre',
        'abreviatura',
        'numero',
    ];

    // Configuración de fechas y marcas de tiempo
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Reglas de validación
    protected $validationRules = [
        'nombre' => 'required|min_length[1]|max_length[255]',
        'abreviatura' => 'required|min_length[1]|max_length[10]',
        'numero' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[12]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
