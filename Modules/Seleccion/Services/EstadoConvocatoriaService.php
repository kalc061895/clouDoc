<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\EstadoConvocatoriaModel;

class EstadoConvocatoriaService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            EstadoConvocatoriaModel::class,
            'eco_ide',
            'eco_codigo',
            [
                'eco_codigo' => 'required|max_length[50]',
                'eco_nombre' => 'required|max_length[100]',
                'eco_descripcion' => 'permit_empty|max_length[255]',
                'eco_orden' => 'permit_empty|integer',
                'eco_estado' => 'required|max_length[20]',
            ],
            ['eco_codigo', 'eco_nombre']
        );
    }
}

