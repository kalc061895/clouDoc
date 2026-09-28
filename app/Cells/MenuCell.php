<?php

namespace App\Cells;
use App\Models\MenuModel;
use Google\Service\Datastore\KindExpression;

// Opcional: puedes usar tu Servicio o Modelo de Menús directamente aquí
// use App\Models\MenuModel;

class MenuCell
{
    /**
     * Este método se ejecutará automáticamente cuando llamemos a la célula desde el Layout
     */
    public function render($tipo='vertical'): string
    {
        $menu = new MenuModel();
        $set = array(
            "menu"=> $menu->getMenuTree(),
        );
        switch ($tipo) {
            case 'horizontal':
                return view('partials/menuHorizontalLayout', $set);
            case 'vertical':
            default:
                return view('partials/menuVerticalLayout', $set);
        }

    }


}