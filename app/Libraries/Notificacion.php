<?php

namespace App\Libraries;

use App\Models\SysNotificacionModel;

class Notificacion
{
    protected $model;

    public function __construct()
    {
        $this->model = new SysNotificacionModel();
    }

    public function crear(
        int $userId,
        string $titulo,
        string $mensaje,
        string $tipo = 'info',
        ?string $url = null
    ): bool {

        return $this->model->insert([
            'not_user_id'  => $userId,
            'not_tipo'     => strtoupper($tipo),
            'not_titulo'   => $titulo,
            'not_mensaje'  => $mensaje,
            'not_url'      => $url,
            'not_icono'    => $tipo,
            'not_leido'    => 0,
            'not_mostrado' => 0,
        ]);
    }
}
