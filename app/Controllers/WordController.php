<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\WordProcessor;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\EmpresaConfiguracionModel;
use App\Models\PersonalModel;
use App\Models\AsisReporteBodyModel;

class WordController extends BaseController
{
    public function index()
    {
        //
    }

    public function generateDocument2()
    {
        $templatePath = WRITEPATH . 'templates/template_local.docx'; // Ruta a la plantilla
        $processor = new WordProcessor($templatePath);

        // Datos a reemplazar en el documento
        $data = [
            '[LUGAR_EMISION]' => 'Lima',
            '[FECHA_ACTUAL]' => date('d/m/Y'),
            '[TIPO_DOCUMENTO]' => 'Carta',
            '[NUMERO_DOCUMENTO]' => '001',
            '[AÑO]' => date('Y'),
            '[OFICINA_SIGLAS]' => 'OF',
            '[TITULO_TRATAMIENTO_DESTINATARIO]' => 'Sr.',
            '[NOMBRE_DESTINATARIO]' => 'Juan Pérez',
            '[CARGO_DESTINATARIO]' => 'Gerente',
            '[OFICINA_DESTINATARIO]' => 'Oficina de Ventas',
            '[DIRECCION_DESTINATARIO]' => 'Av. Principal 123',
            '[LUGAR_DESTINATARIO]' => 'Lima',
            '[ASUNTO]' => 'Solicitud de información',
            '[REFERENCIA]' => 'Ref123',
            '[CUERPO_DOCUMENTO]' => 'Por la presente...',
            '[TITULO_TRATAMIENTO_EMISARIO]' => 'Sra.',
            '[NOMBRE_EMISARIO]' => 'Ana Gómez',
            '[CARGO_EMISARIO]' => 'Coordinadora',
            '[OFICINA_EMISARIO]' => 'Departamento de Recursos Humanos'
        ];

        $processor->setValues($data);

        // Guardar el documento generado
        $savePath = WRITEPATH . 'documents/generated_document.docx';
        $processor->saveAs($savePath);

        // O bien, para enviar el archivo al navegador
        return $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->setHeader('Content-Disposition', 'attachment; filename="document.docx"')
            ->setBody($processor->output());
    }
    public function generateDocument($idInfo = false)
    {
        $set = '';
        // Ruta a la plantilla
        if ($idInfo != false) {
            $set = array(
                'NOMBRE_ANIO' => 'Año del bicentenario de la consolidacion de nuestra independencia y de la conmemoración de la heroicas  batallas de Junín y Ayacucho',
                'LUGAR_EMISION' => 'dsakdalsndla',
            );
        } else {
            $set = array(
                'TIPO_DOCUMENTO' => 'OFICIO',
                'NUMERO_DOCUMENTO' => '025',
            );
        }

        //$templatePath = WRITEPATH . 'templates/template_local.docx';
        $templatePath = WRITEPATH . 'templates/template_local.docx';

        // Crear una instancia de TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Reemplazar los marcadores con los datos deseados
        foreach ($set as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $templateProcessor->setValue('LUGAR_EMISION', 'Juliaca');
        $templateProcessor->setValue('FECHA_ACTUAL', date('d') . ' de ' . date('F',) . '' . date('Y'));

        $templateProcessor->setValue('ANIO', date('Y'));
        $templateProcessor->setValue('OFICINA_SIGLAS', 'URRH/ACAP');
        $templateProcessor->setValue('TITULO_TRATAMIENTO_DESTINATARIO', 'Sr.');
        $templateProcessor->setValue('NOMBRE_DESTINATARIO', 'GODO VASQUEZ MAMANI');
        $templateProcessor->setValue('CARGO_DESTINATARIO', 'Jefe de la Unidad de Recursos Humano');
        $templateProcessor->setValue('OFICINA_DESTINATARIO', 'JEFATURA DE RECURSOS HUMANOS - RED DE SALUD SAN ROMÁN');
        $templateProcessor->setValue('DIRECCION_DESTINATARIO', 'Av. Huancane km2.');
        $templateProcessor->setValue('LUGAR_DESTINATARIO', 'Juliaca');
        $templateProcessor->setValue('ASUNTO', 'solicito vacaciones 2023');
        $templateProcessor->setValue('REFERENCIA', 'Exp. 001895-2024');
        $templateProcessor->setValue('CUERPO_DOCUMENTO', 'Por la presente se...');
        $templateProcessor->setValue('TITULO_TRATAMIENTO_EMISARIO', 'Ing.');
        $templateProcessor->setValue('NOMBRE_EMISARIO', 'HENRY HAROLD SALAZAR FLORES');
        $templateProcessor->setValue('CARGO_EMISARIO', 'Jefe de Control de Asistencia');
        $templateProcessor->setValue('OFICINA_EMISARIO', 'Area de Control de Asistencia');
        $templateProcessor->setValue('CODIGO_DOCUMENTO', 'DS56HSD');
        $templateProcessor->setValue('DIRECCION_EMISARIO', 'Av. Huancane km 2');
        $templateProcessor->setValue('LUGAR_EMISARIO', 'Juliaca');
        $templateProcessor->setValue('TELEFONO_EMISARIO', '935316849');
        $templateProcessor->setValue('CORREO_EMISARIO', 'acap@cloudoc.com');

        // Guardar el documento modificado
        $filename = 'platilla_numero.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $templateProcessor->saveAs($temp_file);

        // Descargar el documento
        return $this->response->download($temp_file, null)->setFileName($filename);
    }
    public function generarPlantilla($idInfo)
    {

        $empresaModel = new EmpresaConfiguracionModel();

        $data = $empresaModel->find($idInfo);
        $set = json_decode($data['value']);
        
        $empresaModel->delete($idInfo);

        // Informacion del documento
        /*$set['NUMERO_DOCUMENTO']=$infoArray['numDocExp'];
        //echo $infoArray['nombreNew'];
        //print_r($infoArray);
        $set = '';
        // Ruta a la plantilla
        if ($idInfo != false) {
            $set = array(
                'NOMBRE_ANIO' => 'Año del bicentenario de la consolidacion de nuestra independencia y de la conmemoración de la heroicas  batallas de Junín y Ayacucho',
                'LUGAR_EMISION' => 'dsakdalsndla',
            );
        } else {
            $set = array(
                'TIPO_DOCUMENTO' => 'OFICIO',
                'NUMERO_DOCUMENTO' => '025',
            );
        }
        */
        //$templatePath = WRITEPATH . 'templates/template_local.docx';
        $templatePath = WRITEPATH . 'templates/template_local.docx';

        // Crear una instancia de TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Reemplazar los marcadores con los datos deseados
        foreach ($set as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        // Guardar el documento modificado
        $filename = $set->TIPO_DOCUMENTO.'_'.$set->NUMERO_DOCUMENTO.'.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $templateProcessor->saveAs($temp_file);

        // Descargar el documento
        return $this->response->download($temp_file, null)->setFileName($filename);
    }

    public function generarReporteAscenso()
    {
        $asisPersonalModel = new PersonalModel();
        $asisReporteBodyModel = new AsisReporteBodyModel();
        $data = $this->request->getPost();
        $dni = $data['dni'];

        if (!$dni) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DNI no proporcionado.']);
        }

        // --- 1. Obtener datos del personal ---
        // Asume que tienes una forma de obtener el perl_ide o los datos de asis_personal por DNI.
        // Podrías añadir una función en AsisPersonalModel para buscar por DNI si no la tienes.
        $personalData = $asisPersonalModel->getPersonalWithDetails( $dni);

        if (!$personalData) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Personal no encontrado para el DNI proporcionado.']);
        }

        // --- 2. Obtener resumen de asistencia y tardanzas ---
        // Usamos la función que creamos en AsisReporteBodyModel
        // Las fechas del semestre deben ser consistentes con la plantilla
        $fechaHoy = date('Y-m-d');
        $ultimoSemestreInicio = date('Y-m-01', strtotime('-6 months', strtotime($fechaHoy)));
        $ultimoSemestreFin = date('Y-m-t', strtotime($fechaHoy)); // Fin del mes actual

        $attendanceSummary=$asisReporteBodyModel->getPersonalAttendanceSummaryByDNI(
            $dni,
            null, // No filtramos por rep_ide específico aquí
            $ultimoSemestreInicio,
            $ultimoSemestreFin
        );

        // Valores por defecto si no hay resumen de asistencia
        $totalFaltas = $attendanceSummary['total_faltas'] ?? 0;
        $totalTardanzas = $attendanceSummary['total_tardanzas'] ?? 0;

        // --- 3. Preparar los datos para la plantilla Word en un array ---
        $dataToFill = [
            'ULTIMO_SEMESTRE' => date('Y-m', strtotime($ultimoSemestreInicio)) . ' - ' . date('Y-m', strtotime($ultimoSemestreFin)),
            'FECHA_HOY' => date('d/m/Y'),
            '[APELLIDOS_NOMBRES]' => $personalData['per_paterno'] . ' ' . $personalData['per_materno'] . ', ' . $personalData['per_nombre'],
            'CARGO' => $personalData['perl_tipo_contrato'],
            'SERVICIO' => $personalData['nombre_servicio'] ?? 'N/A',
            'PTS_FALTA' => $totalFaltas,
            'PTS_TARD' => $totalTardanzas,
            // Agrega aquí otros marcadores de posición si los tienes en tu plantilla
        ];

        // --- 4. Ruta a la plantilla y generación del documento ---
        $templatePath = WRITEPATH . 'templates/template_ascenso_auxiliares.docx';

        // VERIFICACIÓN CLAVE: Asegúrate de que la plantilla existe
        if (!file_exists($templatePath)) {
            log_message('error', 'Plantilla DOCX no encontrada en: ' . $templatePath);
            return $this->response->setJSON(['status' => 'error', 'message' => 'La plantilla del reporte no fue encontrada. Por favor, asegúrese de que "ascenso_auxiliares.docx" esté en ' . WRITEPATH . 'templates/']);
        }

        // Define la ruta de salida en la carpeta public (¡NO RECOMENDADO POR SEGURIDAD!)
        $publicUploadDir = ROOTPATH . 'public/uploads/reportes/';
        
        // Asegúrate de que la carpeta de subida pública exista
        if (!is_dir($publicUploadDir)) {
            // Se recomienda establecer permisos más restrictivos en un entorno de producción (ej. 0755)
            mkdir($publicUploadDir, 0777, true); 
        }

        // Generar nombre de archivo dinámico
        $filename = 'Reporte_Ascenso_' . $dni . '_' . date('Ymd_His') . '.docx';
        $outputPath = $publicUploadDir . $filename; // Ruta completa del archivo en public

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // Rellenar los marcadores de posición con los datos del array
            foreach ($dataToFill as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }

            // Guardar el documento rellenado en la carpeta pública
            $templateProcessor->saveAs($outputPath);

            // Devolver la URL pública del documento generado
            $fileUrl = base_url('uploads/reportes/' . $filename); // URL accesible desde el navegador

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Reporte de ascenso generado con éxito.',
                'file_url' => $fileUrl // Se devuelve la URL pública
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al generar el reporte Word: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al generar el reporte Word: ' . $e->getMessage()]);
        }
    }
}
