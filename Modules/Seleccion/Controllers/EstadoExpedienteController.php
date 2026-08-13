<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\EstadoExpedienteService;

class EstadoExpedienteController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new EstadoExpedienteService();
    }
}

