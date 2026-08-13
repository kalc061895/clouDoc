<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoDocumentoService;

class TipoDocumentoController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoDocumentoService();
    }
}

