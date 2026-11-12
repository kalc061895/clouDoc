(() => {
    'use strict';

    const config = JSON.parse(document.getElementById('dispositivos-config').textContent);
    const form = document.getElementById('dispositivo-form');
    const listado = document.getElementById('dispositivos-listado');
    const titulo = document.getElementById('dispositivo-titulo');
    const guardar = document.getElementById('dispositivo-guardar');
    const limpiar = document.getElementById('dispositivo-limpiar');
    const csrf = form.elements.namedItem(config.csrfName);
    const campos = ['id', 'codigo', 'nombre', 'marca', 'est_id', 'modelo', 'serie', 'ip', 'puerto', 'activo'];
    let equipos = config.equipos;
    let ocupado = false;

    function cargarFormulario(equipo = {}) {
        const valores = {id: 0, marca: 'ZKTECO', activo: 1, ...equipo};
        campos.forEach(campo => {
            form.elements.namedItem(campo).value = valores[campo] ?? '';
        });
        titulo.textContent = Number(valores.id) ? 'Editar dispositivo' : 'Nuevo dispositivo';
    }

    function mostrarEquipos() {
        listado.replaceChildren();
        equipos.forEach(equipo => {
            const fila = listado.insertRow();
            const valores = [equipo.codigo, equipo.nombre, equipo.marca, equipo.est_nombre,
                `${equipo.ip ?? ''} / ${equipo.puerto ?? ''}`, Number(equipo.activo) ? 'Activo' : 'Inactivo'];
            valores.forEach(valor => { fila.insertCell().textContent = valor; });
            const editar = document.createElement('button');
            editar.type = 'button';
            editar.className = 'btn btn-sm btn-outline-primary';
            editar.textContent = 'Editar';
            editar.addEventListener('click', () => {
                if (ocupado) return;
                cargarFormulario(equipo);
                form.scrollIntoView({behavior: 'smooth', block: 'start'});
                form.elements.namedItem('codigo').focus({preventScroll: true});
            });
            fila.insertCell().append(editar);
        });
        if (!equipos.length) {
            const celda = listado.insertRow().insertCell();
            celda.colSpan = 7;
            celda.textContent = 'Todavía no hay dispositivos registrados.';
        }
    }

    limpiar.addEventListener('click', () => {
        if (ocupado) return;
        cargarFormulario();
        form.elements.namedItem('codigo').focus();
    });

    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (ocupado || !form.reportValidity()) return;
        ocupado = true;
        const datos = new FormData(form);
        const controles = [...form.querySelectorAll('input, select, button')];
        controles.forEach(control => { control.disabled = true; });
        try {
            const confirmacion = await Swal.fire({
                title: Number(datos.get('id')) ? '¿Guardar cambios del dispositivo?' : '¿Registrar dispositivo?',
                text: `${datos.get('nombre')} — ${Number(datos.get('activo')) ? 'Activo' : 'Inactivo'}`,
                icon: Number(datos.get('activo')) ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
            });
            if (!confirmacion.isConfirmed) return;
            guardar.textContent = 'Guardando…';
            form.setAttribute('aria-busy', 'true');
            const respuesta = await fetch(form.action, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
                body: datos,
                credentials: 'same-origin',
            });
            if (respuesta.redirected || !respuesta.headers.get('content-type')?.includes('application/json')) {
                throw new Error('La sesión o el formulario ha caducado. Recargue la página para continuar.');
            }
            const resultado = await respuesta.json();
            if (resultado.csrf) {
                csrf.name = resultado.csrf.name;
                csrf.value = resultado.csrf.hash;
            }
            if (!respuesta.ok || resultado.status !== 'success') {
                throw new Error(resultado.message || 'No se pudo guardar el dispositivo.');
            }
            equipos = resultado.equipos;
            mostrarEquipos();
            cargarFormulario();
            toastr.success(resultado.message, '', {escapeHtml: true});
        } catch (error) {
            toastr.error(error.message || 'Error de conexión. Revise el listado antes de reintentar.', '', {escapeHtml: true});
        } finally {
            ocupado = false;
            controles.forEach(control => { control.disabled = false; });
            guardar.textContent = 'Guardar dispositivo';
            form.removeAttribute('aria-busy');
        }
    });

    mostrarEquipos();
})();
