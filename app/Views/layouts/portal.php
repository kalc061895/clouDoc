<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> · <?= esc($portal->nombre) ?></title>
    <meta name="description" content="Portal de gestión de recursos humanos y administración institucional. Trámite documentario, legajos, selección, asistencia y planillas.">
    <link rel="icon" href="<?= base_url('assets/images/logos/favicon.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/portal.css') ?>">
    <script src="<?= base_url('assets/js/portal.js') ?>" defer></script>
</head>
<body>
<a class="skip-link" href="#contenido">Saltar al contenido</a>
<header class="portal-header">
    <div class="shell header-inner">
        <a class="brand" href="<?= site_url('/') ?>" aria-label="ClouDoc, inicio"><span class="brand-symbol" aria-hidden="true">C<span></span></span><span><?= esc($portal->nombre) ?><small>Gestión institucional</small></span></a>
        <nav class="main-nav" aria-label="Navegación principal"><a href="<?= site_url('/') ?>#modulos">Módulos</a><a href="#recursos">Manuales y videos</a><a href="#ayuda">Ayuda</a></nav>
        <a class="button button-dark header-login" href="<?= site_url('login') ?>"><?= view('portal/icono', ['icono'=>'acceso']) ?><span>Iniciar sesión</span></a>
    </div>
</header>
<main id="contenido"><?= $this->renderSection('content') ?></main>
<footer class="portal-footer"><div class="shell footer-inner"><div><a class="footer-brand" href="<?= site_url('/') ?>"><?= esc($portal->nombre) ?></a><p>Sistema de gestión de recursos humanos<br>y administración institucional.</p></div><div><strong><?= esc($portal->entidad) ?></strong><p>Una cuenta. Accesos según su perfil.</p><a href="<?= site_url('login') ?>">Iniciar sesión →</a></div><div><strong>Accesos públicos</strong><a href="<?= site_url('portal/modulos/tramite-documentario') ?>">Mesa de partes y trámites</a><a href="<?= site_url('/') ?>#recursos">Centro de aprendizaje</a><a href="<?= site_url('/') ?>#ayuda">Orientación de acceso</a></div></div><div class="shell footer-bottom">Oficina de Tecnología e Informática · <?= esc($portal->entidad) ?></div></footer>
</body>
</html>
