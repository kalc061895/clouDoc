<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AsisReporteController extends BaseController
{
    public function index()
    {
        //
    }
    public function ReporteAscenso(): String
    {
        // Aquí puedes implementar la lógica para generar el reporte de ascenso
        // Por ejemplo, podrías cargar un modelo y obtener los datos necesarios

        // Cargar el modelo
        $asisReporteModel = model('App\Models\AsisReporteModel');

        // Obtener los datos del reporte
        $data = $asisReporteModel->where('rep_estado', 1)->findAll();

        // Devolver una respuesta con los datos del reporte
        //return $this->response->setJSON($data);
        $set = array(
            'title' => 'Reporte de Ascenso',
            'data'  => $data,
        );
        
        return view('asistencia/reporte/ascenso_2',$set);
    }
    public function ReporteAscensoBody()    
    {
        // Aquí puedes implementar la lógica para generar el reporte de ascenso
        // Por ejemplo, podrías cargar un modelo y obtener los datos necesarios

        // Cargar el modelo
        $asisReporteBodyModel = model('App\Models\AsisReporteBodyModel');
        $asisPersonalModel = model('App\Models\PersonalModel');

        // Obtener los datos del reporte
        $dni = $this->request->getPost('dni');
        $data = $asisReporteBodyModel->getPersonalAttendanceSummaryByDNI($dni);
        $info = $asisPersonalModel->getPersonalWithDetails($dni);

        // Devolver una respuesta con los datos del reporte
        //return $this->response->setJSON($data);
        $set = array(
            'title' => 'Reporte de Ascenso',
            'data'  => $data,
            'info'  => $info,
        );
        
        return $this->response->setJSON($data);
        //return view('asistencia/reporte/ascenso',$set);
    }
    public function buscarPorDni()
    {
        $dni = $this->request->getPost('dni');
        
        if (!$dni) {
            return $this->response->setJSON(['message' => 'DNI no proporcionado'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Aquí puedes implementar la lógica para buscar el reporte por DNI
        // Por ejemplo, podrías cargar un modelo y obtener los datos necesarios

        // Cargar el modelo
       $personalModel = model('App\Models\PersonalModel');

        // Obtener los datos del reporte
        $data = $personalModel->getPersonalWithDetails($dni);

        if (empty($data)) {
            return $this->response->setJSON(['message' => 'No se encontraron reportes para el DNI proporcionado'])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON($data);
    }

}
