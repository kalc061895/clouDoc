<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoDeclaracionService;

class TipoDeclaracionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoDeclaracionService();
    }
}

