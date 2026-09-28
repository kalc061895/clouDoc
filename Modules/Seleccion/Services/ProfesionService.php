<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ProfesionModel;

class ProfesionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            ProfesionModel::class,
            'pro_ide',
            'pro_codigo',
            [
                'pro_codigo' => 'permit_empty|max_length[50]',
                'pro_nombre' => 'required|max_length[150]',
                'pro_estado' => 'required|max_length[20]',
            ],
            ['pro_codigo', 'pro_nombre']
        );
    }
}

