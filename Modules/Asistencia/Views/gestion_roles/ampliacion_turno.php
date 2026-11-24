<?php helper('ui'); ?>
<div class="row align-items-end" data-turno>
    <div class="col-sm-4 col-lg-3">
        <?= view('components/form_field', [
            'name' => 'fecha[]', 'id' => 'ampliacion-fecha-' . $indice, 'label' => 'Fecha', 'type' => 'date',
            'attributes' => ['min' => $desde, 'max' => $hasta], 'value' => $fecha, 'required' => true,
        ], ['saveData' => false]) ?>
    </div>
    <div class="col-sm-6 col-lg-5">
        <?= view('components/form_field', [
            'name' => 'horario[]', 'id' => 'ampliacion-horario-' . $indice, 'label' => 'Turno y horario', 'type' => 'select',
            'options' => $horarios, 'placeholder' => 'Seleccione un horario', 'value' => $horario, 'required' => true,
        ], ['saveData' => false]) ?>
    </div>
    <div class="col-auto mb-3">
        <?= ui_button('Quitar', ['size' => 'sm', 'variant' => 'secondary', 'outline' => true, 'attrs' => ['data-quitar-turno' => '1']]) ?>
    </div>
</div>
