<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoCargoService;

class TipoCargoController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoCargoService();
    }
}

