<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\NivelModel;

class NivelService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            NivelModel::class,
            'niv_ide',
            'niv_codigo',
            [
                'niv_codigo' => 'required|max_length[50]',
                'niv_nombre' => 'required|max_length[100]',
                'niv_estado' => 'required|max_length[20]',
            ],
            ['niv_codigo', 'niv_nombre']
        );
    }
}

