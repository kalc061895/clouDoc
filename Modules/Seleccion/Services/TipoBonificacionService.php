<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoBonificacionModel;

class TipoBonificacionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoBonificacionModel::class,
            'tbo_ide',
            'tbo_codigo',
            [
                'tbo_codigo' => 'required|max_length[50]',
                'tbo_nombre' => 'required|max_length[150]',
                'tbo_descripcion' => 'permit_empty',
                'tbo_tipo_calculo' => 'permit_empty|max_length[50]',
                'tbo_estado' => 'permit_empty|max_length[20]',
            ],
            ['tbo_codigo', 'tbo_nombre']
        );
    }
}

