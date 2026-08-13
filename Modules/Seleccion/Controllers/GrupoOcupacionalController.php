<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\GrupoOcupacionalService;

class GrupoOcupacionalController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new GrupoOcupacionalService();
    }
}

