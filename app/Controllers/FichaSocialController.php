<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DatosEconomicosModel;
use App\Models\DatosEducacionModel;
use App\Models\DatosFamiliaModel;
use App\Models\DatosPersonalesModel;
use App\Models\DatosSaludModel;
use App\Models\DatosTrabajoModel;
use App\Models\DatosViviendaComunidadModel;
use CodeIgniter\HTTP\ResponseInterface;

class FichaSocialController extends BaseController
{
    public function index()
    {
        return view('ficha_social/formulario_ficha_social');
    }

    public function store()
    {

        $_d_personales =  new DatosPersonalesModel();
        $_d_economicos = new DatosEconomicosModel();
        $_d_educacion =  new DatosEducacionModel();
        $_d_salud = new DatosSaludModel();
        $_d_familia = new DatosFamiliaModel();
        $_d_trabajo = new DatosTrabajoModel();
        $_d_vivi_comu = new DatosViviendaComunidadModel();
        $_post = $this->request->getPost();

        $_existeTrabajador = $_d_personales->where('dni', $_post['dni'])->first();
        $info = '';

        if ($_existeTrabajador != null) {
            $_post['dato_personal_id'] = $_existeTrabajador['id'];
            $_d_personales->update($_existeTrabajador['id'], $_post);

            $_id_economico = $_d_economicos->where('dato_personal_id', $_existeTrabajador['id'])->first();
            $_d_economicos->update($_id_economico['id'], $_post);

            $_id_salud = $_d_salud->where('dato_personal_id', $_existeTrabajador['id'])->first();
            $_d_salud->update($_id_salud['id'], $_post);

            $_id_trabajo = $_d_trabajo->where('dato_personal_id', $_existeTrabajador['id'])->first();
            $_d_trabajo->update($_id_trabajo['id'], $_post);

            $_id_vivi_comu = $_d_vivi_comu->where('dato_personal_id', $_existeTrabajador['id'])->first();
            $_d_vivi_comu->update($_id_vivi_comu['id'], $_post);

            // se elimina todo de educacion y familia
            $_d_educacion->where('dato_personal_id', $_post['dato_personal_id'])->delete();
            $educacionDataArray = json_decode($_post['educacionArrayData'], true);
            foreach ($educacionDataArray as $item) {
                $item['dato_personal_id'] = $_post['dato_personal_id'];
                $_d_educacion->insert($item);
            }
            
            // se elimina todo de educacion y familia
            $_d_familia->where('dato_personal_id', $_post['dato_personal_id'])->delete();
            $familiaDataArray = json_decode($_post['familiaArrayData'], true);
            foreach ($familiaDataArray as $item) {
                $item['dato_personal_id'] = $_post['dato_personal_id'];
                $_d_familia->insert($item);
            }
            
            $response = array(
                'status' => 'new',
                'type' => 'success',
                'msg' => 'Se Actualizó Correctamente el Personal',
                'data' => $info,
            );
        } else {
            $_d_personales->insert($this->request->getPost());

            $_postData = $this->request->getPost();
            $_postData['dato_personal_id'] = $_d_personales->getInsertID();
            $_d_economicos->insert($_postData);
            $_d_salud->insert($_postData);
            //$_d_familia->insert($_postData);
            $educacionDataArray = json_decode($_postData['educacionArrayData'], true);
            foreach ($educacionDataArray as $item) {
                $item['dato_personal_id'] = $_postData['dato_personal_id'];
                $_d_educacion->insert($item);
            }
            $familiaDataArray = json_decode($_postData['familiaArrayData'], true);
            foreach ($familiaDataArray as $item) {
                $item['dato_personal_id'] = $_postData['dato_personal_id'];
                $_d_familia->insert($item);
            }
            $_d_trabajo->insert($_postData);
            $_d_vivi_comu->insert($_postData);
            $response = array(
                'status' => 'new',
                'msg' => 'Se creó nuevo Personal',
                'data' => $info,
                'type' => 'success'
            );
        }
        return $this->response->setJSON($response);
    }
    public function searchByDni()
    {
        $_d_personales =  new DatosPersonalesModel();
        $_d_economicos = new DatosEconomicosModel();
        $_d_educacion =  new DatosEducacionModel();
        $_d_salud = new DatosSaludModel();
        $_d_familia = new DatosFamiliaModel();
        $_d_trabajo = new DatosTrabajoModel();
        $_d_vivi_comu = new DatosViviendaComunidadModel();

        $dni = $this->request->getPost('dni');
        // Realiza la consulta en la base de datos usando el DNI
        $data = $_d_personales->where('dni', $dni)->first();
        if ($data != null) {
            $data  = array(
                'personales' => $data,
                'economicos' => $_d_economicos->where('dato_personal_id', $data['id'])->first(),
                'salud' => $_d_salud->where('dato_personal_id', $data['id'])->first(),
                'familia' => $_d_familia->where('dato_personal_id', $data['id'])->get()->getResultObject(),
                'educacion' => $_d_educacion->where('dato_personal_id', $data['id'])->get()->getResultObject(),
                'trabajo' => $_d_trabajo->where('dato_personal_id', $data['id'])->first(),
                'vivienda_comunidad' => $_d_vivi_comu->where('dato_personal_id', $data['id'])->first(),

            );
        }
        // Devuelve los datos en formato JSON
        $set = array(
            'personales' => $data
        );
        return $this->response->setJSON($data);
    }
}
