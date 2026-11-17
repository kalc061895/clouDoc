<?= $this->extend('layouts/' . $layout) ?>
<?= $this->section('title') ?>Prueba de layout<?= $this->endSection() ?>
<?= $this->section('sidebar') ?><li class="sidebar-item">Menú autorizado</li><?= $this->endSection() ?>
<?= $this->section($bodySection ?? 'content') ?><p>ui-content</p><?= $this->endSection() ?>
<?= $this->section('modals') ?><div id="ui-modal"></div><?= $this->endSection() ?>
<?= $this->section(! empty($legacy) ? 'pageStyles' : 'styles') ?><!-- ui-styles --><?= $this->endSection() ?>
<?= $this->section(! empty($legacy) ? 'pageScripts' : 'scripts') ?><!-- ui-scripts --><?= $this->endSection() ?>
