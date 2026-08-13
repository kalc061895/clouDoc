<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\EstadoConvocatoriaService;

class EstadoConvocatoriaController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new EstadoConvocatoriaService();
    }
}

