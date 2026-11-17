<?php // Only the glue needed to connect PHP components to the installed plugins. 
?>
<script>
    (() => {
        'use strict';
        if (window.ClouDocUI) return;

        function init(root = document) {
            root.querySelectorAll('[data-ui-toast]').forEach(element => {
                if (element.dataset.uiReady) return;
                bootstrap.Toast.getOrCreateInstance(element).show();
                element.dataset.uiReady = 'true';
            });
            root.querySelectorAll('table[data-ui-table]').forEach(element => {
                const $ = window.jQuery;
                if (!$?.fn.dataTable || $.fn.dataTable.isDataTable(element)) return;
                const options = JSON.parse(element.dataset.uiTable);
                const table = $(element).DataTable({
                    ...options,
                    lengthMenu: [10, 25, 50, 100],
                    // Bootstrap adapter markup without another downloaded dependency.
                    dom: '<"row align-items-center mb-3"<"col-sm-6"l><"col-sm-6"f>>rt<"row align-items-center mt-3"<"col-sm-6"i><"col-sm-6 d-flex justify-content-sm-end"p>>',
                    oClasses: {
                        sWrapper: 'dataTables_wrapper dt-bootstrap5',
                        sFilterInput: 'form-control form-control-sm',
                        sLengthSelect: 'form-select form-select-sm',
                        sPageButton: 'paginate_button btn btn-outline-primary btn-sm ms-1'
                    },
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        infoFiltered: '(de _MAX_ registros)',
                        emptyTable: 'No hay registros disponibles.',
                        zeroRecords: 'No hay resultados para los filtros seleccionados.',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    },
                    drawCallback: function() {
                        const container = this.api().table().container();
                        container.querySelectorAll('.paginate_button').forEach(button => {
                            const active = button.classList.contains('current');
                            button.classList.toggle('active', active);
                            if (active) button.setAttribute('aria-current', 'page');
                            else button.removeAttribute('aria-current');
                        });
                    }
                });
                element.querySelectorAll('[data-ui-column]').forEach(input => {
                    input.addEventListener('input', () => table.column(Number(input.dataset.uiColumn)).search(input.value).draw());
                });
            });
            // The template's static demo script rewrites #get-url to index.html.
            const home = document.querySelector('[data-ui-home]');
            if (home) home.href = home.dataset.uiHome;
        }

        window.ClouDocUI = {
            init
        };
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => init());
        else init();
        document.addEventListener('ui:refresh', event => init(event.detail?.root ?? document));
        document.addEventListener('shown.bs.modal', () => {
            if (window.jQuery?.fn.dataTable) jQuery.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        });
        document.addEventListener('click', event => {
            if (!event.target.closest('[data-ui-sidebar-toggle]')) return;
            document.getElementById('main-wrapper')?.classList.toggle('show-sidebar');
            document.body.dataset.sidebartype = document.body.dataset.sidebartype === 'full' ? 'mini-sidebar' : 'full';
        });
    })();
</script>