<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\EtapaModel;

class EtapaService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            EtapaModel::class,
            'eta_ide',
            'eta_codigo',
            [
                'eta_codigo' => 'required|max_length[50]',
                'eta_nombre' => 'required|max_length[150]',
                'eta_descripcion' => 'permit_empty|max_length[255]',
                'eta_orden' => 'permit_empty|integer',
                'eta_estado' => 'required|max_length[20]',
            ],
            ['eta_codigo', 'eta_nombre']
        );
    }
}

