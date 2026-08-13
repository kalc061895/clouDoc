<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\NivelFormacionModel;

class NivelFormacionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            NivelFormacionModel::class,
            'nfo_ide',
            'nfo_codigo',
            [
                'nfo_codigo' => 'required|max_length[50]',
                'nfo_nombre' => 'required|max_length[100]',
                'nfo_estado' => 'required|max_length[20]',
            ],
            ['nfo_codigo', 'nfo_nombre']
        );
    }
}

