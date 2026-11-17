<?php // Load dependencies once, in dependency order. Never import demo initializers. 
?>
<script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/theme/theme.js') ?>"></script>
<?php if ($authenticated ?? false): ?>
    <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/select2/dist/js/select2.full.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/toastr-init.js') ?>"></script>
    <script src="<?= base_url('assets/js/iconify-icon@1.0.8/dist/iconify-icon.min.js') ?>"></script>
<?php endif ?>
<?= view('layouts/partials/ui_init', [], ['saveData' => false]) ?>