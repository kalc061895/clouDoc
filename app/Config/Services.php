<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function notificacion($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('notificacion');
        }

        return new \App\Libraries\Notificacion();
    }
    public function crearMultiple(
        array $usuarios,
        string $titulo,
        string $mensaje,
        string $tipo = 'info',
        ?string $url = null
    ) {
        $data = [];

        foreach ($usuarios as $userId) {

            $data[] = [
                'not_user_id'  => $userId,
                'not_tipo'     => strtoupper($tipo),
                'not_titulo'   => $titulo,
                'not_mensaje'  => $mensaje,
                'not_url'      => $url,
                'not_icono'    => $tipo,
                'not_leido'    => 0,
                'not_mostrado' => 0,
            ];
        }

        return $this->model->insertBatch($data);
    }
}
