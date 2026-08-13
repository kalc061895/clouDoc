<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\EstadoExpedienteModel;

class EstadoExpedienteService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            EstadoExpedienteModel::class,
            'eex_ide',
            'eex_codigo',
            [
                'eex_codigo' => 'required|max_length[50]',
                'eex_nombre' => 'required|max_length[100]',
            ],
            ['eex_codigo', 'eex_nombre']
        );
    }
}

