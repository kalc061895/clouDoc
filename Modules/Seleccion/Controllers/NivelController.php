<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\NivelService;

class NivelController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new NivelService();
    }
}

