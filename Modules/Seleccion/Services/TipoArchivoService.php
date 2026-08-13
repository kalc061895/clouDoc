<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\TipoArchivoModel;

class TipoArchivoService extends CatalogCrudService
{
    public function __construct()
    {
        $this->configure(
            TipoArchivoModel::class,
            'tar_ide',
            'tar_codigo',
            [
                'tar_codigo' => 'required|max_length[50]',
                'tar_nombre' => 'required|max_length[100]',
                'tar_extension' => 'permit_empty|max_length[20]',
                'tar_mime' => 'permit_empty|max_length[150]',
                'tar_estado' => 'required|max_length[20]',
            ],
            ['tar_codigo', 'tar_nombre', 'tar_extension', 'tar_mime']
        );
    }
}

