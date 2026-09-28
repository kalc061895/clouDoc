<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ModalidadVinculoModel;

class ModalidadVinculoService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            ModalidadVinculoModel::class,
            'mvi_ide',
            'mvi_codigo',
            [
                'mvi_codigo' => 'required|max_length[50]',
                'mvi_nombre' => 'required|max_length[100]',
                'mvi_estado' => 'required|max_length[20]',
            ],
            ['mvi_codigo', 'mvi_nombre']
        );
    }
}

