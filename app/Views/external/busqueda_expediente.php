<?= $this->extend('layouts/external'); ?>

<?= $this->section('title'); ?>
<?= lang('Exrternal.buscartramite') ?>
<?= $this->endSection(); ?>

<?= $this->section('pageStyles'); ?>
<link rel="stylesheet" href="../assets/libs/sweetalert2/dist/sweetalert2.min.css">
<?= $this->endSection(); ?>


<?= $this->section('content'); ?>
<!-- CODE HERE -->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-md-8 col-sm-12">

            <!-- ----------------------------------------- -->
            <!-- 1. Basic Form -->
            <!-- ----------------------------------------- -->
            <!-- start Basic Form -->
            <div class="card card-border-primary">
                <div class="card-header text-bg-primary">
                    <h4 class="card-title text-white mb-0">
                        <i class="ti ti-search "></i> <?= lang('External.titleSearch') ?>
                    </h4>
                </div>
                <div class="card-body">

                    <form class="floating-labels mt-4 pt-2" action="<?= base_url('/buscarexpediente') ?>" id="searchForm">
                        <div class="form-group mb-4">
                            <input type="text" class="form-control" id="inputCode" autofocus required name="codeEspediente">
                            <span class="bar"></span>
                            <label for="inputCode"><?= lang('External.codeSearch') ?></label>
                            <span class="help-block">
                                <small><?= lang('External.helpCodeSearch') ?></small>
                            </span>
                        </div>
                        <div class="form-group mb-4">
                            <select class="form-control form-select" id="inputanio" required name="yearExpediente">
                                <option></option>
                                <option>2024</option>
                                <option>2023</option>
                                <option>2023</option>
                                <option>2022</option>
                            </select>
                            <span class="bar"></span>
                            <label for="inputanio"><?= lang('External.yearSearch') ?></label>
                        </div>
                        <div class="col-md-12 d-grid gap-2">
                            <button type="submit" class="btn waves-effect waves-light bg-primary-subtle text-primary rounded-pill">
                                <i class="ti ti-search"></i>
                                <?= lang('External.buttonSearch') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end Basic Form -->
            <!-- start Form with view only -->
            <div class="card" id="responseSearch">

            </div>
            <!--start Form with view only -->
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>
<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();

            var inputCode = $('#inputCode').val();
            var inputanio = $('#inputanio').val();
            //Auto Close Timer

            Swal.fire({
                title: "<p class='text-primary fs-7'><?= lang('External.searching')?></p> ",
                type: "info",
                html: "<div class='spinner-border text-primary' role='status'>"+
                    "<span class='visually-hidden'>Loading...</span>"+
                  "</div>",
                    
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });


            // Realizar la solicitud AJAX
            $.ajax({
                url: '<?= base_url('/buscarexpediente') ?>',
                type: 'POST',
                data: {
                    inputCode: inputCode,
                    inputanio: inputanio
                },
                success: function(response) {
                    Swal.close();
                    $('#responseSearch').html(response);
                },
                error: function(xhr) {
                    Swal.close();
                    if (xhr.status === 400) {
                        var errors = xhr.responseJSON;
                        var errorMessage = '';
                        for (var key in errors) {
                            errorMessage += errors[key] + '<br>';
                        }
                        $('#responseSearch').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    } else if (xhr.status === 404) {
                        $('#responseSearch').html('<div class="alert alert-warning">No se encontraron resultados</div>');
                    } else {
                        $('#responseSearch').html('<div class="alert alert-danger">Ocurrió un error al buscar el expediente</div>');
                    }
                }
            });
        });
    });
</script>

<script>
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');
</script>

<script src="../assets/libs/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="../assets/js/forms/sweet-alert.init.js"></script>
<?= $this->endSection(); ?>