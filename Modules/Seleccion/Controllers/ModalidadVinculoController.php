<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\ModalidadVinculoService;

class ModalidadVinculoController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new ModalidadVinculoService();
    }
}

