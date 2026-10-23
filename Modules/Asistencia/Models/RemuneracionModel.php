<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RemuneracionModel extends Model
{
    protected $table = 'casis_remuneraciones';
    protected $primaryKey = 'rem_ide';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'rem_car_ide', 'rem_nivel', 'rem_configuracion', 'rem_estado',
        'created_by', 'updated_by',
    ];

    // Recibe y devuelve la configuracion como array; persiste JSON.
    protected array $casts = ['rem_configuracion' => 'json-array'];

    protected $validationRules = [
        'rem_car_ide' => 'required|is_natural_no_zero',
        'rem_nivel' => 'required|max_length[50]',
        'rem_configuracion' => 'required',
        'rem_estado' => 'permit_empty|in_list[0,1]',
    ];

    public function obtenerPorCargoNivel(int $cargoId, string $nivel): ?array
    {
        return $this->where('rem_car_ide', $cargoId)
            ->where('rem_nivel', trim($nivel))
            ->where('rem_estado', 1)
            ->first();
    }
}
