<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\EtapaService;

class EtapaController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new EtapaService();
    }
}

