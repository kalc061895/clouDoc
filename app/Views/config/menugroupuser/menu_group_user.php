<!-- start Add Row -->
<div class="card">
    <div class="card-body">
        <div class="mb-2">
            <h4 class="card-title"><?= lang('Main.menuGroupUserTitle') ?></h4>
        </div>
        <button id="addNew" class="btn btn-primary mb-2">
            <i class="ti ti-plus fs-4"></i> <?= lang('Main.nuevo') ?>
        </button>
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped w-100 table-bordered display text-nowrap">
                <thead>
                    <!-- start row -->
                    <tr>
                        <th><?= lang('Main.menuId') ?></th>
                        <th><?= lang('Main.groupId') ?></th>
                        <th><?= lang('Main.opciones') ?></th>

                    </tr>
                    <!-- end row -->
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- Primary Header Modal -->
<div id="menuGroupFormModal" class="modal fade" tabindex="-1" aria-labelledby="menuGroupFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary text-white">
                <h4 class="modal-title text-white" id="menuGroupFormModalLabel">
                <?= lang('Main.menuGroupUserTitle')?>
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dataForm">
                    <div class="col-12">
                        <input type="hidden" name="id" id="id">

                        <div class="mb-4">
                            <label class="form-label"><?= lang('Main.menuId') ?></label>
                            <select name="menu_id" id="menu_id" class="form-select" aria-label="Default select example">
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><?= lang('Main.groupId') ?></label>
                            <select name="group_user_id" id="group_user_id" class="form-select" aria-label="Default select example">
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-primary"><?= lang('Main.guardar') ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <?= lang('Main.cancelar') ?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({

            order: [
                [0, "desc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/2.1.0/i18n/es-MX.json"
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('menugroupuser') ?>",
                "type": "POST"
            },
            "columns": [{
                    "data": "menu_id"
                },
                {
                    "data": "group_user_id"
                },
                {
                    "data": null,
                    "defaultContent": '<button class="btn btn-primary btn-sm edit"><?= lang('Main.editar') ?></button> <button class="btn btn-danger btn-sm delete"><?= lang('Main.eliminar') ?></button>'
                }
            ]
        });

        $('#addNew').on('click', function() {
            $('#menuGroupFormModal').modal('show');
            $('#dataForm')[0].reset();
        });

        $('#dataForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('menugroupuser/save') ?>",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#menuGroupFormModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Saved successfully.');
                }
            });
        });

        $('#dataTable').on('click', '.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            $('#id').val(data.id);
            $('#menu_id').val(data.menu_id);
            $('#group_user_id').val(data.group_user_id);
            $('#menuGroupFormModal').modal('show');
        });

        $('#dataTable').on('click', '.delete', function() {
            var data = table.row($(this).parents('tr')).data();
            if (confirm('Are you sure to delete this record?')) {
                $.ajax({
                    url: "<?= base_url('menugroupuser/delete') ?>/" + data.id,
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success('Deleted successfully.');
                    }
                });
            }
        });

        // Populate select options
        $.getJSON("<?= base_url('menugroupuser/getMenus') ?>", function(data) {
            $.each(data, function(key, val) {
                $('#menu_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });

        $.getJSON("<?= base_url('menugroupuser/getGroups') ?>", function(data) {
            $.each(data, function(key, val) {
                $('#group_user_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    });
</script>