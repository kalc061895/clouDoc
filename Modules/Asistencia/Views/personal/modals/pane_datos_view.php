<?php
$mostrar = static function ($valor): string {
    return $valor === null || trim((string) $valor) === '' ? 'No registrado' : esc((string) $valor);
};
$fecha = static function ($valor) use ($mostrar): string {
    if (!$valor || str_starts_with((string) $valor, '0000-00-00')) {
        return 'No registrado';
    }
    $dia = \DateTimeImmutable::createFromFormat('!Y-m-d', substr((string) $valor, 0, 10));
    return $dia && $dia->format('Y-m-d') === substr((string) $valor, 0, 10) ? $dia->format('d/m/Y') : $mostrar($valor);
};
$nombre = trim(implode(' ', array_filter([$personal['per_paterno'] ?? '', $personal['per_materno'] ?? '', $personal['per_nombre'] ?? ''])));
$estado = ['1' => 'Activo', '0' => 'Inactivo'];
$estadoValor = (string) ($personal['perl_estado'] ?? '');
$sexo = ['M' => 'Masculino', 'F' => 'Femenino'];
$secciones = [
    ['titulo' => 'Identificación y contacto', 'icono' => 'solar:user-id-bold-duotone', 'campos' => [
        'Tipo de documento' => $mostrar($personal['tdi_nombre'] ?? null),
        'Número de documento' => $mostrar($personal['per_numero_documento'] ?? null),
        'Apellido paterno' => $mostrar($personal['per_paterno'] ?? null),
        'Apellido materno' => $mostrar($personal['per_materno'] ?? null),
        'Nombres' => $mostrar($personal['per_nombre'] ?? null),
        'Sexo' => $mostrar($sexo[$personal['per_sexo'] ?? ''] ?? ($personal['per_sexo'] ?? null)),
        'Fecha de nacimiento' => $fecha($personal['per_fecha_nacimiento'] ?? null),
        'Lugar de nacimiento' => $mostrar($personal['per_lugar_nacimiento'] ?? null),
        'Estado civil' => $mostrar($personal['per_estadocivil'] ?? null),
        'RUC' => $mostrar($personal['per_ruc'] ?? null),
        'Teléfono' => $mostrar($personal['per_telefono'] ?? null),
        'Correo electrónico' => $mostrar($personal['per_email'] ?? null),
        'Dirección de residencia' => $mostrar($personal['per_residencia'] ?? null),
    ]],
    ['titulo' => 'Información laboral', 'icono' => 'solar:case-round-bold-duotone', 'campos' => [
        'Código del trabajador' => $mostrar($personal['perl_codigo'] ?? null),
        'Establecimiento' => $mostrar($personal['est_nombre'] ?? null),
        'Código del establecimiento' => $mostrar($personal['est_codigo'] ?? null),
        'Código IPRESS' => $mostrar($personal['est_ipress'] ?? null),
        'Oficina' => $mostrar($personal['ofi_nombre'] ?? null),
        'Cargo' => $mostrar($personal['car_nombre'] ?? null),
        'Modalidad de contrato' => $mostrar($personal['mco_nombre'] ?? null),
        'Plaza' => $mostrar($personal['perl_plaza'] ?? null),
        'Nivel' => $mostrar($personal['perl_nivel'] ?? null),
        'Fecha de inicio' => $fecha($personal['perl_fecha_inicio'] ?? null),
        'Fecha de término' => $fecha($personal['perl_fecha_termino'] ?? null),
        'Fecha de cese' => $fecha($personal['perl_fecha_cese'] ?? null),
    ]],
];
?>
<section aria-label="Datos generales del trabajador">
    <div class="d-flex flex-wrap align-items-center gap-3 bg-light border rounded-3 p-3 mb-3">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;" aria-hidden="true">
            <iconify-icon icon="solar:user-bold-duotone" class="fs-2"></iconify-icon>
        </div>
        <div class="flex-grow-1 text-break">
            <h5 class="fw-bold mb-1"><?= $mostrar($nombre) ?></h5>
            <div class="text-muted small"><?= $mostrar($personal['tdi_abreviatura'] ?? $personal['tdi_nombre'] ?? null) ?>: <span class="font-monospace"><?= $mostrar($personal['per_numero_documento'] ?? null) ?></span></div>
            <div class="small mt-1"><?= $mostrar($personal['car_nombre'] ?? null) ?> · <?= $mostrar($personal['est_nombre'] ?? null) ?></div>
        </div>
        <span class="badge <?= $estadoValor === '1' ? 'bg-success' : 'bg-secondary' ?>"><?= $mostrar($estado[$estadoValor] ?? ($estadoValor ?: null)) ?></span>
    </div>
    <div class="row g-3">
        <?php foreach ($secciones as $seccion): ?>
            <div class="col-12 col-lg-6">
                <div class="border rounded-3 h-100 p-3">
                    <h6 class="fw-bold border-bottom pb-2 mb-3 d-flex align-items-center gap-2"><iconify-icon icon="<?= esc($seccion['icono']) ?>" class="text-primary fs-5"></iconify-icon><?= esc($seccion['titulo']) ?></h6>
                    <dl class="row g-3 mb-0">
                        <?php foreach ($seccion['campos'] as $etiqueta => $valor): ?>
                            <div class="col-12 col-sm-6"><dt class="small text-muted fw-normal mb-1"><?= esc($etiqueta) ?></dt><dd class="small fw-semibold text-break mb-0"><?= $valor ?></dd></div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-12">
            <div class="border rounded-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Formación profesional y colegiatura</h6>
                <?php if (empty($personal['profesiones'])): ?>
                    <p class="text-muted small mb-0">No se ha registrado información profesional.</p>
                <?php endif; ?>
                <?php foreach ($personal['profesiones'] ?? [] as $profesion): ?>
                    <div class="bg-light rounded-3 p-3 mb-2">
                        <h6 class="mb-3"><?= $mostrar($profesion['pro_nombre'] ?? null) ?> <?php if (!empty($profesion['pp_principal'])): ?><span class="badge bg-primary ms-1">Principal</span><?php endif; ?></h6>
                        <dl class="row g-3 mb-0">
                            <?php $camposProfesionales = [
                                'Colegio profesional' => $mostrar($profesion['col_nombre'] ?? null),
                                'Número de colegiatura' => $mostrar($profesion['pp_numero_colegiatura'] ?? null),
                                'Segunda especialidad' => $mostrar($profesion['se_nombre'] ?? null),
                                'RNE' => $mostrar($profesion['pp_rne'] ?? null),
                                'Habilitación registrada' => $mostrar(['1' => 'Habilitado', '0' => 'No habilitado'][(string) ($profesion['pp_habilitado'] ?? '')] ?? null),
                                'Fecha de habilitación' => $fecha($profesion['pp_fecha_habilitacion'] ?? null),
                                'Vencimiento de habilitación' => $fecha($profesion['pp_fecha_vencimiento'] ?? null),
                                'Observaciones' => $mostrar($profesion['pp_observacion'] ?? null),
                            ]; ?>
                            <?php foreach ($camposProfesionales as $etiqueta => $valor): ?>
                                <div class="col-12 col-sm-6 col-lg-3"><dt class="small text-muted fw-normal mb-1"><?= esc($etiqueta) ?></dt><dd class="small fw-semibold text-break mb-0"><?= $valor ?></dd></div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-12"><div class="border rounded-3 p-3"><h6 class="fw-bold mb-2">Observaciones laborales</h6><p class="small text-break mb-0" style="white-space: pre-wrap;"><?= $mostrar($personal['perl_observacion'] ?? null) ?></p></div></div>
    </div>
</section>
