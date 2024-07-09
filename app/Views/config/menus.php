<!-- HTML básico para el CRUD -->
<div class="container-fluid">
    <h2>CRUD de Menús</h2>
    <button id="btnAdd" class="btn btn-primary mb-2">Agregar Menú</button>
    <table id="menuTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se listarán los menús -->
        </tbody>
    </table>
</div>

<!-- Modal para agregar/editar menú -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Agregar Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="menuForm">
                    <input type="hidden" id="menuId" name="menuId">
                    <div class="mb-3">
                        <label for="menuName" class="form-label">Nombre del Menú</label>
                        <input type="text" class="form-control" id="menuName" name="menuName">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnSaveMenu" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Función para cargar los menús al cargar la página
        loadMenus();

        // Función para cargar los menús desde el servidor
        function loadMenus() {
            $.ajax({
                url: '/menus', // URL del backend para obtener los menús (adaptar según tu estructura)
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var rows = '';
                    $.each(response, function(index, menu) {
                        rows += '<tr>' +
                            '<td>' + menu.id + '</td>' +
                            '<td>' + menu.name + '</td>' +
                            '<td>' +
                            '<button class="btn btn-sm btn-primary btn-edit" data-id="' + menu.id + '">Editar</button> ' +
                            '<button class="btn btn-sm btn-danger btn-delete" data-id="' + menu.id + '">Eliminar</button>' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#menuTable tbody').html(rows);
                }
            });
        }

        // Abrir modal para agregar menú
        $('#btnAdd').click(function() {
            $('#menuModalLabel').text('Agregar Menú');
            $('#menuId').val('');
            $('#menuName').val('');
            $('#menuModal').modal('show');
        });

        // Guardar o actualizar menú
        $('#btnSaveMenu').click(function() {
            var menuId = $('#menuId').val();
            var menuName = $('#menuName').val();

            var url = '/menus';
            var method = 'POST';
            if (menuId !== '') {
                url += '/' + menuId;
                method = 'PUT';
            }

            $.ajax({
                url: url,
                type: method,
                dataType: 'json',
                data: $('#menuForm').serialize(),
                success: function(response) {
                    $('#menuModal').modal('hide');
                    loadMenus();
                }
            });
        });

        // Editar menú
        $('#menuTable').on('click', '.btn-edit', function() {
            var menuId = $(this).data('id');
            $.ajax({
                url: '/menus/' + menuId, // URL para obtener detalles del menú por ID (adaptar según tu estructura)
                type: 'GET',
                dataType: 'json',
                success: function(menu) {
                    $('#menuModalLabel').text('Editar Menú');
                    $('#menuId').val(menu.id);
                    $('#menuName').val(menu.name);
                    $('#menuModal').modal('show');
                }
            });
        });

        // Eliminar menú
        $('#menuTable').on('click', '.btn-delete', function() {
            var menuId = $(this).data('id');
            if (confirm('¿Estás seguro de eliminar este menú?')) {
                $.ajax({
                    url: '/menus/' + menuId, // URL para eliminar menú por ID (adaptar según tu estructura)
                    type: 'DELETE',
                    success: function(response) {
                        loadMenus();
                    }
                });
            }
        });
    });
</script>