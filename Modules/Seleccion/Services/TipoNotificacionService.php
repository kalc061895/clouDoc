<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoNotificacionModel;

class TipoNotificacionService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoNotificacionModel::class,
            'tno_ide',
            'tno_codigo',
            [
                'tno_codigo' => 'required|max_length[50]',
                'tno_nombre' => 'permit_empty|max_length[150]',
                'tno_asunto' => 'permit_empty|max_length[255]',
                'tno_plantilla' => 'permit_empty',
                'tno_estado' => 'permit_empty|max_length[20]',
            ],
            ['tno_codigo', 'tno_nombre', 'tno_asunto']
        );
    }
}

