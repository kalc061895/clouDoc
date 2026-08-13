<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoCargoModel;

class TipoCargoService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoCargoModel::class,
            'tca_ide',
            'tca_codigo',
            [
                'tca_codigo' => 'required|max_length[50]',
                'tca_nombre' => 'required|max_length[100]',
                'tca_estado' => 'required|max_length[20]',
            ],
            ['tca_codigo', 'tca_nombre']
        );
    }
}

