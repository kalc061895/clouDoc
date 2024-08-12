<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipoExpedienteSeeder extends Seeder
{
   public function run()
   {
      $data = [
         [
            'nombre' => 'SOLICITUD',
            'descripcion' => 'Documento que solicita algo específico.',
         ],
         [
            'nombre' => 'CARTA',
            'descripcion' => 'Documento formal de comunicación escrita.',
         ],
         [
            'nombre' => 'OFICIO',
            'descripcion' => 'Documento oficial emitido por una autoridad.',
         ],
         [
            'nombre' => 'INFORME',
            'descripcion' => 'Documento que presenta información detallada sobre un tema específico.',
         ],
         [
            'nombre' => 'MEMORANDUM',
            'descripcion' => 'Documento que remite una jefatura a empleado.',
         ],
         [
            'nombre' => 'ACTA DE REUNION',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA CIRCULAR',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA DE DISPENZA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA DE GARANTIA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA DE RENUNCIA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA MULTIPLE',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA NOTARIAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA PODER',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CARTA RENUNCIA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CASO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CEDULA DE NOTIFICACION',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CERTIFICACION PRESUPUESTAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'CREDENCIAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'DESCARGO DE INFORME',
            'descripcion' => ''
         ],
         [
            'nombre' => 'DOCUMENTO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'ESCRITO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'EXHORTACION',
            'descripcion' => ''
         ],
         [
            'nombre' => 'INFORME LEGAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'INFORME MEDICO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'INFORME PSIQUIATRICO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'MEMORANDUM',
            'descripcion' => ''
         ],
         [
            'nombre' => 'MEMORANDUM MULTIPLE',
            'descripcion' => ''
         ],
         [
            'nombre' => 'MEMORIAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'NOTIFICACION',
            'descripcion' => ''
         ],
         [
            'nombre' => 'OFICIO CIRCULAR',
            'descripcion' => ''
         ],
         [
            'nombre' => 'OFICIO MULTIPLE',
            'descripcion' => ''
         ],
         [
            'nombre' => 'ORDEN DE SERVICIO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'ORDEN DE COMPRA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'PRONUNCIAMIENTO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'PROVEIDO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'PROVEIDO LEGAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'RESOLUCION',
            'descripcion' => ''
         ],
         [
            'nombre' => 'RESOLUCION JEFATURAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'RESOLUCION ADMINISTRATIVA',
            'descripcion' => ''
         ],
         [
            'nombre' => 'RESOLUCION DIRECTORAL',
            'descripcion' => ''
         ],
         [
            'nombre' => 'SOBRE CERRADO',
            'descripcion' => ''
         ],
         [
            'nombre' => 'SUMILLA',
            'descripcion' => ''
         ],
         // Agrega más tipos de expediente según sea necesario
      ];

      // Inserta los datos en la tabla tipo_expediente
      $this->db->table('tipo_expediente')->insertBatch($data);
   }
}
