<?php

namespace App\Models;

use CodeIgniter\Model;

class AnioModel extends Model
{
    protected $table = 'anios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; // Puedes cambiarlo a 'object' si prefieres trabajar con objetos

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

    // Reglas de validación opcionales
    protected $validationRules = [
        'nombre' => 'required|min_length[1]|max_length[255]',
        'abreviatura' => 'required|min_length[1]|max_length[10]',
        'numero' => 'required|integer',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
