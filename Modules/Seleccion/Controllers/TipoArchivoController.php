<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoArchivoService;

class TipoArchivoController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoArchivoService();
    }
}

