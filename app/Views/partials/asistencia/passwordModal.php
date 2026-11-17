<?php
helper('ui');
echo view('components/modal', [
    'id' => 'asis-password-modal', 'title' => 'Cambiar contraseña',
    'staticBackdrop' => true,
    'bodyHtml' => view('partials/asistencia/passwordForm', [], ['saveData' => false]),
    'footerHtml' => ui_button('Cerrar', ['variant' => 'secondary', 'attrs' => ['data-bs-dismiss' => 'modal']])
        . ui_button('Guardar contraseña', ['type' => 'submit', 'attrs' => ['form' => 'asis-password-form', 'id' => 'asis-password-submit']]),
], ['saveData' => false]);
?>
<script type="application/json" id="asis-password-config"><?= json_encode([
    'tokenUrl' => site_url('asistencia/mi-cuenta/password/token'), 'csrfName' => csrf_token(),
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
