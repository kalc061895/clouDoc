<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\ProfesionService;

class ProfesionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new ProfesionService();
    }
}

