<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoBonificacionService;

class TipoBonificacionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoBonificacionService();
    }
}

