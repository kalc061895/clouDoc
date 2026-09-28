<?php

namespace Modules\Seleccion\Controllers;

use Modules\Seleccion\Services\TipoNotificacionService;

class TipoNotificacionController extends CatalogCrudController
{
    public function __construct()
    {
        $this->service = new TipoNotificacionService();
    }
}

