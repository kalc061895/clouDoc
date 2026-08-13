<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\NivelFormacionService;

class NivelFormacionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new NivelFormacionService();
    }
}

