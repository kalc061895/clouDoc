<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoDeclaracionModel;

class TipoDeclaracionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoDeclaracionModel::class,
            'tde_ide',
            'tde_codigo',
            [
                'tde_codigo' => 'required|max_length[50]',
                'tde_nombre' => 'required|max_length[150]',
                'tde_contenido' => 'permit_empty',
                'tde_estado' => 'permit_empty|max_length[20]',
            ],
            ['tde_codigo', 'tde_nombre']
        );
    }
}

