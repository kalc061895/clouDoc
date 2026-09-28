<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoConvocatoriaModel;

class TipoConvocatoriaService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoConvocatoriaModel::class,
            'tco_ide',
            'tco_codigo',
            [
                'tco_codigo' => 'required|max_length[50]',
                'tco_nombre' => 'required|max_length[150]',
                'tco_descripcion' => 'permit_empty|max_length[255]',
                'tco_estado' => 'required|max_length[20]',
            ],
            ['tco_codigo', 'tco_nombre']
        );
    }
}

