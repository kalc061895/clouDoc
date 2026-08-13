<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\EstadoPostulacionModel;

class EstadoPostulacionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            EstadoPostulacionModel::class,
            'epo_ide',
            'epo_codigo',
            [
                'epo_codigo' => 'required|max_length[50]',
                'epo_nombre' => 'required|max_length[100]',
                'epo_descripcion' => 'permit_empty|max_length[255]',
                'epo_orden' => 'permit_empty|integer',
            ],
            ['epo_codigo', 'epo_nombre']
        );
    }
}

