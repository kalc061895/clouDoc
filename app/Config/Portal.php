<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/** Contenido público del portal. Los recursos se publican al añadir una URL real. */
class Portal extends BaseConfig
{
    public string $nombre = 'ClouDoc';
    public string $entidad = 'Red de Salud San Román';

    public array $modulos = [
        'tramite-documentario' => [
            'nombre'=>'Trámite documentario', 'categoria'=>'Gestión documental', 'icono'=>'documento', 'color'=>'azul', 'estado'=>'Disponible',
            'descripcion'=>'Gestión administrativa del trámite documentario y mesa de partes.',
            'titulo'=>'Cada documento, en el lugar correcto.',
            'introduccion'=>'Presente documentos, consulte el avance de sus expedientes y acceda a los procedimientos de la institución desde un mismo lugar.',
            'funciones'=>['Mesa de partes y recepción de documentos','Derivación y atención de expedientes','Seguimiento de trámites y consulta de procedimientos'],
            'pasos'=>['Consulte los requisitos del procedimiento que necesita.','Registre su expediente o ingrese para la gestión interna.','Realice el seguimiento con los datos de su expediente.'],
            'publicos'=>[['titulo'=>'Presentar expediente','url'=>'nuevoexpediente'],['titulo'=>'Consultar expediente','url'=>'buscarexpediente'],['titulo'=>'Procedimientos TUPA','url'=>'tupaexpediente']],
        ],
        'legajos' => [
            'nombre'=>'Legajos', 'categoria'=>'Información del personal', 'icono'=>'personal', 'color'=>'violeta', 'estado'=>'Disponible',
            'descripcion'=>'Administración de legajos y documentación del personal.',
            'titulo'=>'La trayectoria del personal, organizada.',
            'introduccion'=>'Un espacio para organizar los datos personales, la formación, la experiencia y los documentos que integran el legajo de cada servidor.',
            'funciones'=>['Datos personales y familiares','Formación académica y experiencia laboral','Organización de documentos del legajo'],
            'pasos'=>['Inicie sesión con su cuenta institucional.','Acceda a los legajos habilitados para su perfil.','Consulte o actualice la información y su documentación.'],
        ],
        'seleccion' => [
            'nombre'=>'Selección', 'categoria'=>'Incorporación de personal', 'icono'=>'seleccion', 'color'=>'ambar', 'estado'=>'Disponible',
            'descripcion'=>'Gestión de concursos y convocatorias de personal.',
            'titulo'=>'Procesos de selección con información organizada.',
            'introduccion'=>'Conozca el módulo que reúne las convocatorias, postulaciones y etapas de evaluación para la incorporación de personal.',
            'funciones'=>['Organización de convocatorias y sus etapas','Registro de postulaciones y documentos','Evaluación y seguimiento del proceso'],
            'pasos'=>['Inicie sesión con su cuenta.','Acceda a las opciones habilitadas para su perfil.','Revise la información y los plazos de la convocatoria.'],
        ],
        'asistencia' => [
            'nombre'=>'Asistencia', 'categoria'=>'Gestión del tiempo', 'icono'=>'calendario', 'color'=>'verde', 'estado'=>'Disponible',
            'descripcion'=>'Control administrativo de asistencia, programación, permisos y licencias.',
            'titulo'=>'El tiempo de trabajo, bajo control.',
            'introduccion'=>'Organice la programación del personal y gestione el control de asistencia, los permisos y las licencias según su responsabilidad y ámbito asignado.',
            'funciones'=>['Programación de turnos y aprobación de roles','Control de marcaciones y calificación mensual','Administración de permisos, licencias y vacaciones'],
            'pasos'=>['Inicie sesión con su usuario de Asistencia.','Revise su tarea y el establecimiento u oficina asignados.','Gestione la programación o los registros de su ámbito.'],
        ],
        'planillas' => [
            'nombre'=>'Planillas', 'categoria'=>'Gestión de remuneraciones', 'icono'=>'planilla', 'color'=>'rosa', 'estado'=>'En preparación',
            'descripcion'=>'Control de pagos y determinación de remuneraciones del personal.',
            'titulo'=>'Un espacio para la gestión de remuneraciones.',
            'introduccion'=>'Esta será la entrada al módulo de Planillas. Aquí se publicarán su alcance, las indicaciones de uso y los materiales de capacitación cuando esté habilitado.',
            'funciones'=>['Determinación de remuneraciones','Organización del control de pagos','Consulta de información de planillas'],
            'pasos'=>['Consulte esta página para conocer la habilitación del módulo.','Revise los manuales cuando estén publicados.','Utilice la misma cuenta institucional cuando se habilite el acceso.'],
        ],
    ];

    /**
     * Ejemplo de publicación (solo material público, sin datos personales):
     * ['modulo'=>'asistencia', 'tipo'=>'pdf', 'titulo'=>'Guía de programación',
     *  'descripcion'=>'Pasos para preparar y enviar un rol.', 'url'=>'manuales/asistencia/programacion.pdf']
     * tipo: pdf o video. URL local pública o https://. No subir aquí documentos de trabajadores.
     */
    public array $recursos = [];
}
