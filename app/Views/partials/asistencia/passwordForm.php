<form id="asis-password-form" action="<?= site_url('asistencia/mi-cuenta/password') ?>" method="post">
    <?= csrf_field() ?>
    <div class="alert d-none" id="asis-password-status" role="status" aria-live="polite" tabindex="-1"></div>
    <?php foreach ([
        'current_password' => ['Contraseña actual', 'current-password'],
        'password' => ['Nueva contraseña', 'new-password'],
        'password_confirm' => ['Confirmar nueva contraseña', 'new-password'],
    ] as $name => [$label, $autocomplete]): ?>
        <?= view('components/form_field', ['name' => $name, 'id' => 'asis-' . $name, 'type' => 'password',
            'label' => $label, 'required' => true, 'attributes' => ['autocomplete' => $autocomplete, 'maxlength' => 255]], ['saveData' => false]) ?>
    <?php endforeach ?>
    <p class="form-text">Use al menos <?= (int) config('Auth')->minimumPasswordLength ?> caracteres. La nueva contraseña debe ser diferente de la actual y cumplir la política de seguridad de su cuenta.</p>
</form>
