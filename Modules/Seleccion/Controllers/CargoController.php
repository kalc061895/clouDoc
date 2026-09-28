<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\CargoService;

class CargoController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new CargoService();
    }
}
