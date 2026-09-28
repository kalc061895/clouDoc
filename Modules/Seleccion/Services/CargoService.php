<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\CargoModel;

class CargoService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            CargoModel::class,
            'car_ide',
            'car_codigo',
            [
                'car_codigo'       => 'required|max_length[50]',
                'car_denominacion' => 'required|max_length[150]',
                'car_especialidad' => 'permit_empty|max_length[150]',
                'car_tca_ide'      => 'permit_empty|is_natural_no_zero',
                'car_gru_ide'      => 'permit_empty|is_natural_no_zero',
                'car_niv_ide'      => 'permit_empty|is_natural_no_zero',
                'car_pro_ide'      => 'permit_empty|is_natural_no_zero',
                'car_descripcion'  => 'permit_empty|max_length[255]',
                'car_estado'       => 'required|max_length[20]',
            ],
            ['car_codigo', 'car_denominacion', 'car_especialidad']
        );
    }
}
