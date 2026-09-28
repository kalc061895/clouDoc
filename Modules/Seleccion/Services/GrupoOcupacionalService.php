<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\GrupoOcupacionalModel;

class GrupoOcupacionalService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            GrupoOcupacionalModel::class,
            'gru_ide',
            'gru_codigo',
            [
                'gru_codigo' => 'required|max_length[50]',
                'gru_nombre' => 'required|max_length[100]',
                'gru_estado' => 'required|max_length[20]',
            ],
            ['gru_codigo', 'gru_nombre']
        );
    }
}

