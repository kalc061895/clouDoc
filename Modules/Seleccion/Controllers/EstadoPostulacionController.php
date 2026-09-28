<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\EstadoPostulacionService;

class EstadoPostulacionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new EstadoPostulacionService();
    }
}

