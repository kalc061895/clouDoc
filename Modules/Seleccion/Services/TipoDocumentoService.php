<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoDocumentoModel;

class TipoDocumentoService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoDocumentoModel::class,
            'tdo_ide',
            'tdo_codigo',
            [
                'tdo_codigo' => 'required|max_length[30]',
                'tdo_nombre' => 'required|max_length[100]',
                'tdo_estado' => 'required|max_length[20]',
            ],
            ['tdo_codigo', 'tdo_nombre']
        );
    }
}

