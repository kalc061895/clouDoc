<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoConvocatoriaService;

class TipoConvocatoriaController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoConvocatoriaService();
    }
}

