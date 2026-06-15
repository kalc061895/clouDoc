<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SysNotificacionModel;

class NotificacionController extends BaseController
{
    protected SysNotificacionModel $notificacionModel;

    public function __construct()
    {
        $this->notificacionModel = new SysNotificacionModel();
    }
    public function index()
    {
        //
    }
    public function contador()
    {
        $userId = auth()->id();

        $total = $this->notificacionModel
            ->where('not_user_id', $userId)
            ->where('not_leido', 0)
            ->countAllResults();

        return $this->response->setJSON([
            'total' => $total
        ]);
    }
    public function pendientes()
    {
        $userId = auth()->id();

        $items = $this->notificacionModel
            ->where('not_user_id', $userId)
            ->where('not_mostrado', 0)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        if (! empty($items)) {

            $ids = array_column($items, 'not_id');

            $this->notificacionModel
                ->whereIn('not_id', $ids)
                ->set([
                    'not_mostrado' => 1
                ])
                ->update();
        }

        return $this->response->setJSON([
            'data' => $items
        ]);
    }
    public function listado()
    {
        $userId = auth()->id();

        $items = $this->notificacionModel
            ->where('not_user_id', $userId)
            ->where('not_leido', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll(10);

        $noLeidas = $this->notificacionModel
            ->where('not_user_id', $userId)
            ->where('not_leido', 0)
            ->countAllResults();

        return $this->response->setJSON([
            'items' => $items,
            'total' => $noLeidas
        ]);
    }
    public function marcarLeida($id)
    {
        $userId = auth()->id();

        $this->notificacionModel
            ->where('not_id', $id)
            ->where('not_user_id', $userId)
            ->set([
                'not_leido' => 1
            ])
            ->update();

        return $this->response->setJSON([
            'success' => true
        ]);
    }
    public function marcarTodasLeidas()
    {
        $userId = auth()->id();

        $this->notificacionModel
            ->where('not_user_id', $userId)
            ->set([
                'not_leido' => 1
            ])
            ->update();

        return $this->response->setJSON([
            'success' => true
        ]);
    }
}
