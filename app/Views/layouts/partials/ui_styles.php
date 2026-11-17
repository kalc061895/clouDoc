<?php // public/main uses ../assets: the deployed shared directory is public/assets. 
?>
<link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
<?php if ($authenticated ?? false): ?>
    <link rel="stylesheet" href="<?= base_url('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/select2/dist/css/select2.min.css') ?>">
<?php endif ?>