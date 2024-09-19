<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\WordProcessor;
use PhpOffice\PhpWord\TemplateProcessor;

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
        $templateProcessor->setValue('ASUNTO', 'REMITO INFORME SOBRE LARUTA CALCINA KEVIN ARNOLD');
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
    public function generarPlantilla()
    {
        // Ruta a la plantilla
        $templatePath = WRITEPATH . 'templates/template_ficha_social.docx';

        // Crear una instancia de TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Reemplazar los marcadores con los datos deseados
        $templateProcessor->setValue('NOMBRE_DE_ANIO', 'Año del bicentenario de la consolidacion de nuestra independencia y de la conmemoración de la heroicas  batallas de Junín y Ayacucho');
        $templateProcessor->setValue('LUGAR_EMISION', 'Juliaca');
        $templateProcessor->setValue('FECHA_ACTUAL', date('d') . ' de ' . date('F',) . '' . date('Y'));
        $templateProcessor->setValue('TIPO_DOCUMENTO', 'OFICIO');
        $templateProcessor->setValue('NUMERO_DOCUMENTO', '025');
        $templateProcessor->setValue('ANIO', date('Y'));
        $templateProcessor->setValue('OFICINA_SIGLAS', 'URRH/ACAP');
        $templateProcessor->setValue('TITULO_TRATAMIENTO_DESTINATARIO', 'Sr.');
        $templateProcessor->setValue('NOMBRE_DESTINATARIO', 'GODO VASQUEZ MAMANI');
        $templateProcessor->setValue('CARGO_DESTINATARIO', 'Jefe de la Unidad de Recursos Humano');
        $templateProcessor->setValue('OFICINA_DESTINATARIO', 'JEFATURA DE RECURSOS HUMANOS - RED DE SALUD SAN ROMÁN');
        $templateProcessor->setValue('DIRECCION_DESTINATARIO', 'Av. Huancane km2.');
        $templateProcessor->setValue('LUGAR_DESTINATARIO', 'Juliaca');
        $templateProcessor->setValue('ASUNTO', 'REMITO INFORME SOBRE LARUTA CALCINA KEVIN ARNOLD');
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

        // Retornar la URL temporal del archivo
        return $this->response->setJSON(['file_url' => base_url('temp/' . basename($temp_file))]);
    }
}
