<?= $this->extend('layouts/external') ;?>

<?= $this->section('title') ;?>
    <?= lang('Exrternal.nuevotramite')?>
<?= $this->endSection() ;?>

<?= $this->section('content') ;?>
<!-- CODE HERE -->

<div class="row">
    <div class="col-xl-12">
        <?= $content?>
    </div>
</div>
<?= $this->endSection() ;?>

