<!-- start Step wizard Vertical -->
<?php

use App\Libraries\FormBuilder;

$formBuilder = new FormBuilder();
?>
<div class="card" id="formImpresion">
    <div class="card-body wizard-content">
        <h4 class="card-title mb-0">Ficha Social </h4>
        <h6 class="card-subtitle mb-3"></h6>
        <form class="tab-wizard  wizard-circle mt-5" id="formFichaSocial">
            <!-- Step 1 -->
            <h6>Datos Personales</h6>

            <?php
            $contenido = [
                (object)[
                    'col' => 2,
                    'name' => 'dni',
                    'label' => 'DNI:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'dni'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'apellido_paterno',
                    'label' => 'Apellido Paterno:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'apellido_paterno'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'apellido_materno',
                    'label' => 'Apellido Materno:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'apellido_materno'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'nombres',
                    'label' => 'Nombres:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'nombres'
                    ],
                ],
                /*
                (object)[
                    'col' => 6,
                    'name' => 'foto',
                    'label' => 'Foto:',
                    'type' => 'file',
                    'value' => '',
                    'attributes' => [
                        'id' => 'foto'
                    ],
                ],*/
                (object)[
                    'col' => 2,
                    'name' => 'sexo',
                    'label' => 'Sexo:',
                    'type' => 'select',
                    'value' => '',
                    'options' => ['M' => 'Masculino', 'F' => 'Femenino'],
                    'attributes' => [
                        'id' => 'sexo'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'nacionalidad',
                    'label' => 'Nacionalidad:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'nacionalidad'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fecha_nacimiento',
                    'label' => 'Fecha de Nacimiento:',
                    'type' => 'date',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fecha_nacimiento'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'edad',
                    'label' => 'Edad:',
                    'type' => 'number',
                    'value' => '',
                    'attributes' => [
                        'id' => 'edad'
                    ],
                ],

                (object)[
                    'col' => 3,
                    'name' => 'carne_extranjeria',
                    'label' => 'Carné de Extranjería:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'carne_extranjeria'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'licencia_conducir',
                    'label' => 'Licencia de Conducir:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'licencia_conducir'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'estado_civil',
                    'label' => 'Estado Civil:',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'soltero' => 'Soltero',
                        'convivencia' => 'Convivencia',
                        'casado' => 'Casado',
                        'separado' => 'Separado',
                        'viudo' => 'Viudo',
                        'divorciado' => 'Divorciado'
                    ],
                    'attributes' => [
                        'id' => 'estado_civil'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'numero_hijos',
                    'label' => 'Número de Hijos:',
                    'type' => 'number',
                    'value' => '',
                    'attributes' => [
                        'id' => 'numero_hijos'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'domicilio_actual',
                    'label' => 'Domicilio Actual:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'domicilio_actual'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'distrito',
                    'label' => 'Distrito:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'distrito'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'provincia',
                    'label' => 'Provincia:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'provincia'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'departamento',
                    'label' => 'Departamento:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'departamento'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'telefono_celular',
                    'label' => 'Teléfono Celular:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'telefono_celular'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'telefono_fijo',
                    'label' => 'Teléfono Fijo:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'telefono_fijo'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'correo_electronico',
                    'label' => 'Correo Electrónico:',
                    'type' => 'email',
                    'value' => '',
                    'attributes' => [
                        'id' => 'correo_electronico'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'RUC',
                    'label' => 'RUC:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'RUC'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'banco_nombre',
                    'label' => 'Nombre del Banco:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'banco_nombre'
                    ],
                ],
                (object)[
                    'col' => 6,
                    'name' => 'numero_cuenta',
                    'label' => 'Número de Cuenta:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'numero_cuenta'
                    ],
                ],
                (object)[
                    'col' => 8,
                    'name' => 'persona_emergencia',
                    'label' => 'Nombres de la Persona de Emergencia:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'persona_emergencia'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'telefono_emergencia',
                    'label' => 'Teléfono de Emergencia:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'telefono_emergencia'
                    ],
                ],
            ];
            ?>
            <section>
                <h6 class="card-title"> DATOS PERSONALES </h6>
                <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-primary text-white" id="dniSearch"><iconify-icon icon="solar:magnifer-linear" class=""></iconify-icon></button>
                    </div>
                    <?php foreach ($contenido as $item) : ?>
                        <div class="col-md-<?= $item->col ?>">
                            <div class="mb-2">
                                <?php $formBuilder = new FormBuilder(); ?>
                                <?php if ($item->type == 'select') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->select($item->name, $item->options, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php elseif ($item->type == 'textarea') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->textarea($item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php else : ?>
                                    <!-- FALSE -->
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->input($item->type, $item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </section>
            <!-- Step 2 -->
            <?php
            $contenido = [
                (object)[
                    'col' => 3,
                    'name' => 'cargo',
                    'label' => 'Cargo:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'cargo'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'modalidad',
                    'label' => 'Modalidad:',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'NOMBRADO' => 'NOMBRADO',
                        'ORGANICA' => 'ORGANICA',
                        'CAS' => 'CAS',
                        'OTRO' => 'OTRO',
                    ],
                    'attributes' => [
                        'id' => 'modalidad'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'nivel',
                    'label' => 'Nivel:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'nivel'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'fecha_ingreso',
                    'label' => 'Fecha de Ingreso:',
                    'type' => 'date',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fecha_ingreso'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'numero_contrato',
                    'label' => 'Número de Contrato:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'numero_contrato'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'resolucion_nombramiento',
                    'label' => 'Nro. de Reso. de Nombramiento:',
                    'type' => 'text',
                    'value' => '-',
                    'attributes' => [
                        'id' => 'resolucion_nombramiento'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'establecimiento',
                    'label' => 'Establecimiento:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'establecimiento'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'microred',
                    'label' => 'Microred:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'microred'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'unidad',
                    'label' => 'Unidad:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'unidad'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'servicio',
                    'label' => 'Servicio:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'servicio'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'area',
                    'label' => 'Área:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'area'
                    ],
                ],

                (object)[
                    'col' => 4,
                    'name' => 'remuneracion',
                    'label' => 'Remuneración:',
                    'type' => 'number',
                    'value' => '',
                    'attributes' => [
                        'step' => '0.01', // Atributo para campos decimales
                        'id' => 'remuneracion'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'pension',
                    'label' => 'AFP/ONP:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [ // Atributo para campos decimales
                        'id' => 'pension'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'pension_cuenta',
                    'label' => 'Nro de Cuenta AFP/ONP:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [ // Atributo para campos decimales
                        'id' => 'pension_cuenta'
                    ],
                ],
                (object)[
                    'col' => 4,
                    'name' => 'pension_cuenta',
                    'label' => 'Nro de Cuenta AFP/ONP:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [ // Atributo para campos decimales
                        'id' => 'pension_cuenta'
                    ],
                ],
            ];
            ?>
            <h6>Trabajo </h6>
            <section>
                <h6 class="card-title"> Informacion Remunerativa</h6>
                <div class="row">

                    <?php foreach ($contenido as $item) : ?>
                        <div class="col-md-<?= $item->col ?>">
                            <div class="mb-2">
                                <?php $formBuilder = new FormBuilder(); ?>
                                <?php if ($item->type == 'select') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->select($item->name, $item->options, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php elseif ($item->type == 'textarea') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->textarea($item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php else : ?>
                                    <!-- FALSE -->
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->input($item->type, $item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </section>
            <!-- Step 3 -->
            <?php
            $contenido = [

                (object)[
                    'col' => 4,
                    'name' => 'fam_apellidos_nombres',
                    'label' => 'Apellidos y Nombres:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fam_apellidos_nombres'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'fam_parentesco',
                    'label' => 'Parentesco:',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'hijos' => 'Hijo/a',
                        'padres' => 'Padre/Madre',
                        'conyugue' => 'Conviviente/Conyugue',
                        'hermanos' => 'Hermano/a',
                    ],
                    'attributes' => [
                        'id' => 'fam_parentesco'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'fam_fecha_nacimiento',
                    'label' => 'Fecha de Nacimiento:',
                    'type' => 'date',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fam_fecha_nacimiento'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fam_estado_civil',
                    'label' => 'Estado Civil:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fam_estado_civil'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fam_grado_instruccion',
                    'label' => 'Grado de Instrucción:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fam_grado_instruccion'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fam_profesion',
                    'label' => 'Profesión:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'fam_profesion'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fam_vive_con_usted',
                    'label' => '¿Vive con Usted?',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'si' => 'Sí',
                        'no' => 'No'
                    ],
                    'attributes' => [
                        'id' => 'fam_vive_con_usted'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'fam_dependencia_economica',
                    'label' => '¿Depende Economicamente de Usted?',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'si' => 'Sí',
                        'no' => 'No'
                    ],
                    'attributes' => [
                        'id' => 'fam_dependencia_economica'
                    ],
                ],
            ];
            ?>
            <h6>Datos Familiares</h6>
            <section>
                <h1> Informacion Remunerativa</h1>
                <div class="row">
                    <?php foreach ($contenido as $item) : ?>
                        <div class="col-md-<?= $item->col ?>">
                            <div class="mb-2">
                                <?php $formBuilder = new FormBuilder(); ?>
                                <?php if ($item->type == 'select') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->select($item->name, $item->options, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php elseif ($item->type == 'textarea') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->textarea($item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php else : ?>
                                    <!-- FALSE -->
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->input($item->type, $item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <button type="button" id="addFamilyMember" class="btn btn-primary">Agregar</button>
                            <table id="familyTable" class="table table-sm table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>Apellidos y Nombres</th>
                                        <th>Parentesco</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th>Estado Civil</th>
                                        <th>Grado de Instrucción</th>
                                        <th>Profesión</th>
                                        <th>¿Vive con Usted?</th>
                                        <th>¿Depende Económicamente?</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Step 3 -->
            <?php
            $contenido = [
                (object)[
                    'col' => 3,
                    'name' => 'e_centro_estudios',
                    'label' => 'Centro de Estudios:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'e_centro_estudios'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'e_grado_instruccion',
                    'label' => 'Grado de Instrucción:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'e_grado_instruccion'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'e_anios_cursados',
                    'label' => 'Años Cursados:',
                    'type' => 'number',
                    'value' => '',
                    'attributes' => [
                        'id' => 'e_anios_cursados'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'e_titulo_obtenido',
                    'label' => 'Título Obtenido:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'e_titulo_obtenido'
                    ],
                ],
                (object)[
                    'col' => 2,
                    'name' => 'e_observaciones',
                    'label' => 'Observaciones:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'e_observaciones'
                    ],
                ],
            ];
            ?>
            <h6>Educación</h6>
            <section>
                <h1> Datos de Formación</h1>
                <div class="row">
                    <?php foreach ($contenido as $item) : ?>
                        <div class="col-md-<?= $item->col ?>">
                            <div class="mb-2">
                                <?php $formBuilder = new FormBuilder(); ?>
                                <?php if ($item->type == 'select') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->select($item->name, $item->options, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php elseif ($item->type == 'textarea') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->textarea($item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php else : ?>
                                    <!-- FALSE -->
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->input($item->type, $item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                    <div class="col-md-12">
                        <button type="button" id="addEducation" class="btn btn-primary">Agregar</button>
                        <table id="educationTable" class="table table-sm table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>Centro de Estudios</th>
                                    <th>Grado de Instrucción</th>
                                    <th>Años Cursados</th>
                                    <th>Título Obtenido</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- Step 4 -->
            <?php
            $contenido = [

                (object)[
                    'col' => 6,
                    'name' => 'viv_ubicacion',
                    'label' => 'Ubicación:',
                    'type' => 'text',
                    'value' => '',
                    'attributes' => [
                        'id' => 'viv_ubicacion'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'viv_tenencia',
                    'label' => 'Tenencia:',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'Propio' => 'Propio',
                        'Alquilada' => 'Alquilada',
                        'Guardiania' => 'Guardiania',
                        'Invasion' => 'Invasion',
                        'Familiar' => 'Familiar',
                        'Otro' => 'Otro',
                    ],
                    'attributes' => [
                        'id' => 'viv_tenencia'
                    ],
                ],
                (object)[
                    'col' => 3,
                    'name' => 'viv_material',
                    'label' => 'Material:',
                    'type' => 'select',
                    'value' => '',
                    'options' => [
                        'Noble' => 'Noble',
                        'Madera' => 'Madera',
                        'Adobe' => 'Adobe',
                        'Esteras' => 'Esteras',
                        'Quincha' => 'Quincha',
                        'Mixto' => 'Mixto',
                        'Otro' => 'Otro',
                    ],
                    'attributes' => [
                        'id' => 'viv_material'
                    ],
                ],
            ];
            ?>
            <h6>Vivienda</h6>
            <section>
                <h1> Datos de Vivienda y Comunidad</h1>
                <div class="row">
                    <div class="col-md-12 card mb-3">
                        <h5>Información General</h5>

                        <div class="row">
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-6 card mb-3">
                        <?php
                        $contenido = [
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_luz',
                                'label' => 'Luz:',
                                'type' => 'select',
                                'value' => '1',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'attributes' => [
                                    'id' => 'viv_servicios_luz'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_agua',
                                'label' => 'Agua:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_servicios_agua'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_telefono',
                                'label' => 'Teléfono:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_servicios_telefono'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_desague',
                                'label' => 'Desagüe:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_servicios_desague'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_cable',
                                'label' => 'Cable:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_servicios_cable'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_internet',
                                'label' => 'Internet:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_servicios_internet'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_servicios_otros',
                                'label' => 'Otros:',
                                'type' => 'text',
                                'value' => '',
                                'attributes' => [
                                    'id' => 'viv_servicios_otros'
                                ],
                            ],
                        ];
                        ?>
                        <h6>Servicios con los que cuenta:</h6>
                        <div class="row">
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-6 card mb-3">
                        <?php
                        $contenido = [

                            (object)[
                                'col' => 6,
                                'name' => 'viv_problemas_drogadiccion',
                                'label' => 'Drogadicción:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_problemas_drogadiccion'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_problemas_pandillas',
                                'label' => 'Pandillas:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_problemas_pandillas'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_problemas_robos',
                                'label' => 'Robos:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_problemas_robos'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_problemas_alcoholismo',
                                'label' => 'Alcoholismo:',
                                'type' => 'select',
                                'options' => [
                                    '1' => 'Sí',
                                    '0' => 'No',
                                ],
                                'value' => '1',
                                'attributes' => [
                                    'id' => 'viv_problemas_alcoholismo'
                                ],
                            ],
                            (object)[
                                'col' => 6,
                                'name' => 'viv_problemas_otros',
                                'label' => 'Otros:',
                                'type' => 'text',
                                'value' => '',
                                'attributes' => [
                                    'id' => 'viv_problemas_otros'
                                ],
                            ],
                        ];
                        ?>
                        <h6>Problemas sociales :</h6>
                        <div class="row">
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                </div>
            </section>
            <!-- Step 5 -->
            <h6>Salud</h6>
            <section>
                <h1> Datos de Salud</h1>

                <div class="row">
                    <div class="col-md-4 card">
                        <div class="row ">
                            <div class="col-12">
                                <h5>Alergías</h5>
                            </div>
                            <?php
                            $contenido = [
                                (object)[
                                    'col' => 6,
                                    'name' => 'aler_betalactamicos',
                                    'label' => 'Betalactámicos:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'aler_betalactamicos'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'aler_penicilina',
                                    'label' => 'Penicilina:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'aler_penicilina'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'aler_analgesicos',
                                    'label' => 'Analgésicos:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'aler_analgesicos'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'aler_ketorolaco',
                                    'label' => 'Ketorolaco:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'aler_ketorolaco'
                                    ],
                                ],
                                (object)[
                                    'col' => 12,
                                    'name' => 'aler_otras',
                                    'label' => 'Otras Alergias:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'aler_otras'
                                    ],
                                ],

                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-md-4 card">
                        <div class="row ">
                            <div class="col-12">
                                <h5>Enfermedades</h5>
                            </div>
                            <?php
                            $contenido = [
                                (object)[
                                    'col' => 6,
                                    'name' => 'enf_diabetes',
                                    'label' => 'Diabetes:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'enf_diabetes'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'enf_hipertension',
                                    'label' => 'Hipertensión:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'enf_hipertension'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'enf_asma',
                                    'label' => 'Asma:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'enf_asma'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'enf_epilepsia',
                                    'label' => 'Epilepsia:',
                                    'type' => 'select',
                                    'options' => [
                                        '1' => 'Sí',
                                        '0' => 'No',
                                    ],
                                    'value' => 1,
                                    'attributes' => [
                                        'id' => 'enf_epilepsia'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'enf_otras',
                                    'label' => 'Otras Enfermedades:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'enf_otras'
                                    ],
                                ],
                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row card">
                            <div class="col-12">
                                <h5>Medicamentos </h5>
                            </div>
                            <?php
                            $contenido = [

                                (object)[
                                    'col' => 12,
                                    'name' => 'med_actuales',
                                    'label' => 'Indicar que medicamentos toma actualmente:',
                                    'type' => 'textarea',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'med_actuales'
                                    ],
                                ],
                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

            </section>
            <!-- Step 6 -->
            <h6>Económicos</h6>
            <section>
                <h1> Datos de Vivienda y Comunidad</h1>
                <div class="row">
                    <h6 class="card-title"> Ingreso Promedio Mensual</h6>
                    <?php
                    $contenido = [

                        (object)[
                            'col' => 4,
                            'name' => 'ingreso_mensual_trabajador',
                            'label' => 'Ingreso Mensual del Trabajador:',
                            'type' => 'text',
                            'value' => '',
                            'attributes' => [
                                'id' => 'ingreso_mensual_trabajador',
                                'placeholder' => '0.00',
                                'type' => 'number',
                                'step' => '0.01',
                            ],
                        ],
                        (object)[
                            'col' => 4,
                            'name' => 'ingreso_mensual_conyugue',
                            'label' => 'Ingreso Mensual del Cónyuge:',
                            'type' => 'text',
                            'value' => '',
                            'attributes' => [
                                'id' => 'ingreso_mensual_conyugue',
                                'placeholder' => '0.00',
                                'type' => 'number',
                                'step' => '0.01'
                            ],
                        ],
                        (object)[
                            'col' => 4,
                            'name' => 'ingreso_mensual_otros',
                            'label' => 'Ingreso Mensual de Otros:',
                            'type' => 'text',
                            'value' => '',
                            'attributes' => [
                                'id' => 'ingreso_mensual_otros',
                                'placeholder' => '0.00',
                                'type' => 'number',
                                'step' => '0.01'
                            ],
                        ],
                    ];
                    ?>
                    <?php foreach ($contenido as $item) : ?>
                        <div class="col-md-<?= $item->col ?>">
                            <div class="mb-2">
                                <?php $formBuilder = new FormBuilder(); ?>
                                <?php if ($item->type == 'select') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->select($item->name, $item->options, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php elseif ($item->type == 'textarea') : ?>
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->textarea($item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php else : ?>
                                    <!-- FALSE -->
                                    <?= $formBuilder
                                        ->label($item->name, $item->label)
                                        ->input($item->type, $item->name, $item->value, $item->attributes)
                                        ->getform() ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="row">
                    <h6 class="card-title"> Egreso Promedio Mensual</h6>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <h5>Servicios</h5>
                            </div>
                            <?php
                            $contenido = [

                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_agua',
                                    'label' => 'Agua:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_agua',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_luz',
                                    'label' => 'Luz:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_luz',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_telefono',
                                    'label' => 'Teléfono:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_telefono',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_cable',
                                    'label' => 'Cable:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_cable',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_internet',
                                    'label' => 'Internet:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_internet',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_servicios_otros',
                                    'label' => 'Otros:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_otros',
                                        'type' => 'number',
                                        'placeholder' => '0.00',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 12,
                                    'name' => 'egreso_servicios_total',
                                    'label' => 'Total de Egresos por Servicios:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_servicios_total',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <h5>Socio Familiar</h5>
                            </div>
                            <?php
                            $contenido = [

                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_alimentacion',
                                    'label' => 'Alimentación:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_alimentacion',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_medicamento',
                                    'label' => 'Medicamentos:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_medicamento',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_vestimenta',
                                    'label' => 'Vestimenta:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_vestimenta',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_educacion',
                                    'label' => 'Educación:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_educacion',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_movilidad',
                                    'label' => 'Movilidad:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_movilidad',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_familiar_otros',
                                    'label' => 'Otros:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_otros',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 12,
                                    'name' => 'egreso_familiar_total',
                                    'label' => 'Total de Egresos Familiares:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_familiar_total',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ]

                                ]
                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <h5>Otros </h5>
                            </div>
                            <?php
                            $contenido = [
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_deudas',
                                    'label' => 'Otras Deudas:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01',
                                        'id' => 'egreso_otros_deudas',
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_seguro_salud',
                                    'label' => 'Seguro de Salud:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_seguro_salud',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_seguro_vida',
                                    'label' => 'Seguro de Vida:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_seguro_vida',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_seguro_sepelio',
                                    'label' => 'Seguro de Sepelio:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_seguro_sepelio',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_vehiculo',
                                    'label' => 'Vehículo:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_vehiculo',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 6,
                                    'name' => 'egreso_otros_otros',
                                    'label' => 'Otros Conceptos:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_otros',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                                (object)[
                                    'col' => 12,
                                    'name' => 'egreso_otros_total',
                                    'label' => 'Total de Otros Egresos:',
                                    'type' => 'text',
                                    'value' => '',
                                    'attributes' => [
                                        'id' => 'egreso_otros_total',
                                        'placeholder' => '0.00',
                                        'type' => 'number',
                                        'step' => '0.01'
                                    ],
                                ],
                            ];
                            ?>
                            <?php foreach ($contenido as $item) : ?>
                                <div class="col-md-<?= $item->col ?>">
                                    <div class="mb-2">
                                        <?php $formBuilder = new FormBuilder(); ?>
                                        <?php if ($item->type == 'select') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->select($item->name, $item->options, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php elseif ($item->type == 'textarea') : ?>
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->textarea($item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php else : ?>
                                            <!-- FALSE -->
                                            <?= $formBuilder
                                                ->label($item->name, $item->label)
                                                ->input($item->type, $item->name, $item->value, $item->attributes)
                                                ->getform() ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

            </section>

            <textarea name="educacionArrayData" id="educacionArrayData" hidden></textarea>
            <textarea name="familiaArrayData" id="familiaArrayData" hidden></textarea>
        </form>
    </div>
</div>
<!-- end Step wizard Vertical -->
<button class="btn btn-sm btn-primary" id="generate-pdf">Generar PDF</button>
<script src="<?= base_url('assets/libs/jquery-steps/build/jquery.steps.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    //Custom design form example
    $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            previous: "Anterior",
            next: "Siguiente",
            finish: "Guardar",
        },
        onFinishing: function(event, currentIndex) {


            // Asignar el JSON al textarea
            $('#educacionArrayData').val(JSON.stringify(educationArray));
            $('#familiaArrayData').val(JSON.stringify(familyArray));
            Swal.fire(
                "Guardando!",
                "",
                "success"
            );
            $.ajax({
                url: '<?= base_url('fichasocial/store') ?>',
                method: 'POST',
                data: $(".tab-wizard").serialize(),
                success: function(response) {
                    Swal.fire(
                        "Exito!",
                        response.msg,
                        response.type
                    );
                    console.log(response);
                    // Maneja la respuesta del servidor aquí
                },
                error: function(xhr, status, error) {
                    // Maneja los errores aquí
                }
            });
        },

    });

    $('#generate-pdf').on('click', function() {
        var formData = $("#formFichaSocial").serializeArray();
        var doc = new jspdf.jsPDF();

        doc.text("Datos del Formulario", 10, 10);

        var y = 20;
        formData.forEach(function(field) {
            doc.text(field.name + ": " + field.value, 10, y);
            y += 10;
        });

        doc.save('formulario.pdf');
    });
    var familyArray = [];

    $('#addFamilyMember').click(function(e) {
        e.preventDefault();
        // Get form values
        var familyMember = {
            fam_apellidos_nombres: $('#fam_apellidos_nombres').val(),
            fam_parentesco: $('#fam_parentesco').val(),
            fam_fecha_nacimiento: $('#fam_fecha_nacimiento').val(),
            fam_estado_civil: $('#fam_estado_civil').val(),
            fam_grado_instruccion: $('#fam_grado_instruccion').val(),
            fam_profesion: $('#fam_profesion').val(),
            fam_vive_con_usted: $('#fam_vive_con_usted').val(),
            fam_dependencia_economica: $('#fam_dependencia_economica').val()
        };

        // Add to array
        familyArray.push(familyMember);

        // Update table
        updateFamilyTable();
    });

    function updateFamilyTable() {

        $('#familiaArrayData').val(JSON.stringify(familyArray));
        var tbody = $('#familyTable tbody');
        tbody.empty();
        familyArray.forEach((item, index) => {
            var row = `
                <tr>
                    <td>${item.fam_apellidos_nombres}</td>
                    <td>${item.fam_parentesco}</td>
                    <td>${item.fam_fecha_nacimiento}</td>
                    <td>${item.fam_estado_civil}</td>
                    <td>${item.fam_grado_instruccion}</td>
                    <td>${item.fam_profesion}</td>
                    <td>${item.fam_vive_con_usted}</td>
                    <td>${item.fam_dependencia_economica}</td>
                    <td><button class="btn btn-danger btn-sm remove-btn" data-index="${index}">Eliminar</button></td>
                </tr>
            `;
            tbody.append(row);
        });

        // Attach remove event
        $('.remove-btn').click(function() {
            var index = $(this).data('index');
            familyArray.splice(index, 1);
            updateFamilyTable();
        });
    }


    var educationArray = [];

    $('#addEducation').click(function(e) {
        // Get form values
        e.preventDefault();
        var education = {
            e_centro_estudios: $('#e_centro_estudios').val(),
            e_grado_instruccion: $('#e_grado_instruccion').val(),
            e_anios_cursados: $('#e_anios_cursados').val(),
            e_titulo_obtenido: $('#e_titulo_obtenido').val(),
            e_observaciones: $('#e_observaciones').val()
        };

        // Add to array
        educationArray.push(education);

        // Update table
        updateEducationTable();
    });

    function updateEducationTable() {
        $('#educacionArrayData').val(JSON.stringify(educationArray));
        var tbody = $('#educationTable tbody');
        tbody.empty();
        educationArray.forEach((item, index) => {
            var row = `
                <tr>
                    <td>${item.e_centro_estudios}</td>
                    <td>${item.e_grado_instruccion}</td>
                    <td>${item.e_anios_cursados}</td>
                    <td>${item.e_titulo_obtenido}</td>
                    <td>${item.e_observaciones}</td>
                    <td><button class="btn btn-danger btn-sm remove-btn" data-index="${index}">Eliminar</button></td>
                </tr>
            `;
            tbody.append(row);
        });

        // Attach remove event
        $('.remove-btn').click(function() {
            var index = $(this).data('index');
            educationArray.splice(index, 1);
            updateEducationTable();
        });
    }

    $('#dniSearch').click(function(e) {
        e.preventDefault();

        Swal.fire(
            "Buscando!",
            "Buscando coincidencias",
            "success"
        );
        let dni = $('#dni').val(); // Obtener el valor del input

        // Realizar la solicitud AJAX
        $.ajax({
            url: '<?= base_url('fichasocial/buscar') ?>', // Reemplaza con la URL correcta de tu controlador
            type: 'POST',
            data: {
                dni: dni
            },
            dataType: 'json',
            success: function(response) {
                if (response != null) {
                    $('.tab-wizard')[0].reset();
                    updateFields(response.personales);
                    updateFields(response.economicos);
                    updateFields(response.salud);
                    updateFields(response.trabajo);
                    updateFields(response.vivienda_comunidad);
                    familyArray = [];
                    response.familia.forEach(element => {
                        // Clonar el objeto eliminando la propiedad 'id'
                        let {
                            id,
                            ...rest
                        } = element;
                        familyArray.push(rest);
                    });

                    console.log(familyArray);
                    updateFamilyTable();
                    familyArray = [];
                    response.educacion.forEach(element => {
                        // Clonar el objeto eliminando la propiedad 'id'
                        let {
                            id,
                            ...rest
                        } = element;
                        educationArray.push(rest);
                    });

                    console.log(educationArray);
                    updateEducationTable();
                    Swal.close();
                } else {
                    $('.tab-wizard')[0].reset();
                    Swal.fire(
                        "Error!",
                        "No se encontro el DNI buscado",
                        "error"
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    function updateFields(data) {
        $.each(data, function(key, value) {
            // Verifica si existe un elemento con el ID que coincide con la clave
            var $field = $('#' + key);

            if ($field.length) {
                // Actualiza el valor del campo dependiendo del tipo de input
                if ($field.is(':checkbox')) {
                    // Para checkbox, verificar y seleccionar si el valor coincide
                    $field.prop('checked', $field.val() == value);
                } else if ($field.is(':radio')) {
                    // Para radio buttons, seleccionar el correcto del grupo
                    $('input[name="' + $field.attr('name') + '"]').prop('checked', false); // Desmarcar todos los radios en el grupo
                    $('input[name="' + $field.attr('name') + '"][value="' + value + '"]').prop('checked', true);
                } else if ($field.is('select')) {
                    // Para select, establecer el valor
                    $field.val(value).trigger('change');
                } else {
                    // Para otros tipos de input, simplemente establecer el valor
                    $field.val(value);
                }
            }
        });
    }
</script>
<button onclick="generatePDF()">Generar PDF</button>
<script>
    function generatePDF() {
        var content = document.getElementById('formImpresion');

        html2canvas(content).then(canvas => {
            var imgData = canvas.toDataURL('image/png');
            var docDefinition = {
                content: [{
                    image: imgData,
                    width: 500 // Puedes ajustar el tamaño de la imagen según sea necesario
                }]
            };
            pdfMake.createPdf(docDefinition).download('formulario.pdf');
        });
    }


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<button id="download">Descargar PDF</button>

    <script>
        document.getElementById('download').addEventListener('click', () => {
            const element = document.getElementById('formFichaSocial-p-0');
            html2pdf().from(element).save();
        });
    </script>