<?php // Compatibility for existing AJAX menu entries; only the main layout loads this. 
?>
<script>
    (() => {
        // Preserve the application's existing AJAX menu convention.
        if (document.querySelector('.content-main')) {
            const $ = window.jQuery;
            let pending;
            $(document).on('click.cloudoc', 'a.open-here', function(event) {
                if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || this.target === '_blank') return;
                const url = new URL(this.href, location.href);
                if (url.origin !== location.origin) return;
                event.preventDefault();
                if (pending) pending.abort();
                if (typeof window.chartInterval !== 'undefined') clearInterval(window.chartInterval);
                const content = $('.content-main').attr('aria-busy', 'true');
                const link = this;
                pending = $.ajax({
                        url: url.href,
                        dataType: 'html'
                    })
                    .done(html => {
                        // A session timeout can redirect an AJAX request to a complete login page.
                        if (/<html[\s>]/i.test(html)) {
                            location.assign(url.href);
                            return;
                        }
                        if ($.fn.dataTable) {
                            content.find('table[data-ui-table]').each(function() {
                                if ($.fn.dataTable.isDataTable(this)) $(this).DataTable().destroy();
                            });
                        }
                        content.html(html);
                        document.querySelectorAll('a.open-here.active').forEach(item => item.classList.remove('active'));
                        link.classList.add('active');
                        window.ClouDocUI.init(content[0]);
                    })
                    .fail((xhr, status) => {
                        if (status !== 'abort' && window.Swal) Swal.fire({
                            icon: 'error',
                            text: 'No se pudo cargar la página. Intente nuevamente.'
                        });
                    })
                    .always(() => content.removeAttr('aria-busy'));
            });
        }
    })();
</script>