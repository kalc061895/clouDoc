<div class="container-fluid">
    <div class="row">

        <!-- PANEL IZQUIERDO -->
        <div class="col-md-3">

            <div class="card shadow-sm mb-3">
                <div class="card-body">

                    <h5 class="mb-3">🧾 Programación</h5>

                    <div class="mb-2">
                        <label>Personal</label>
                        <select id="personal" class="form-select"></select>
                    </div>

                    <div class="mb-2">
                        <label>Servicio</label>
                        <select id="servicio" class="form-select"></select>
                    </div>

                    <div class="mb-2">
                        <label>Actividad</label>
                        <select id="actividad" class="form-select"></select>
                    </div>

                </div>
            </div>

            <!-- ACTIVIDADES RÁPIDAS -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>⚡ Selección rápida</h6>

                    <div class="d-grid gap-2">

                        <button class="btn btn-primary btn-sm actividad-btn" data-tipo="Consulta externa">Consulta externa</button>
                        <button class="btn btn-danger btn-sm actividad-btn" data-tipo="Hospitalización">Hospitalización</button>
                        <button class="btn btn-success btn-sm actividad-btn" data-tipo="Administrativo">Administrativo</button>

                    </div>

                </div>
            </div>

        </div>

        <!-- CALENDARIO -->
        <div class="col-md-9">

            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- BOTONES -->
                    <div class="mb-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="cambiarVista('day')">Día</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="cambiarVista('week')">Semana</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="cambiarVista('month')">Mes</button>
                    </div>

                    <div id="calendar" style="height: 750px;"></div>

                </div>
            </div>

        </div>

    </div>
</div>

<script>
    // 🔗 URL BASE
    const URL = "<?= base_url('asistencia/programacion/eventos') ?>";

    // 🎨 COLORES
    const colores = {
        'Consulta externa': '#0d6efd',
        'Hospitalización': '#dc3545',
        'Administrativo': '#198754'
    };

    let actividadSeleccionada = null;


    // 🧲 BOTONES RÁPIDOS
    document.querySelectorAll('.actividad-btn').forEach(btn => {
        btn.addEventListener('click', () => {

            actividadSeleccionada = btn.dataset.tipo;

            Swal.fire({
                icon: 'info',
                title: 'Actividad seleccionada',
                text: actividadSeleccionada,
                timer: 1000,
                showConfirmButton: false
            });
        });
    });


    // 📅 CALENDARIO
    const calendar = new tui.Calendar('#calendar', {
        defaultView: 'week',

        week: {
            startDayOfWeek: 1,
            hourStart: 6,
            hourEnd: 20
        },

        useCreationPopup: false,
        useDetailPopup: false
    });


    // 🔄 CAMBIAR VISTA
    function cambiarVista(vista) {
        calendar.changeView(vista);
    }


    // 📥 CARGAR COMBOS + EVENTOS
    function cargarTodo() {

        fetch(URL)
            .then(res => res.json())
            .then(data => {

                // combos
                let personal = document.getElementById('personal');
                let servicio = document.getElementById('servicio');
                let actividad = document.getElementById('actividad');

                personal.innerHTML = '';
                servicio.innerHTML = '';
                actividad.innerHTML = '';

                data.personal.forEach(p => {
                    personal.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
                });

                data.servicios.forEach(s => {
                    servicio.innerHTML += `<option value="${s.id}">${s.nombre}</option>`;
                });

                data.actividades.forEach(a => {
                    actividad.innerHTML += `<option value="${a.nombre}" data-color="${a.color}">
        ${a.nombre}
      </option>`;
                });

                // eventos
                calendar.clear();

                data.eventos.forEach(e => {

                    let color = e.color || '#000';

                    calendar.createEvents([{
                        id: e.id,
                        calendarId: '1',
                        title: e.titulo,
                        category: 'time',
                        start: e.inicio,
                        end: e.fin,
                        backgroundColor: color,
                        borderColor: color
                    }]);

                });

            });
    }

    cargarTodo();


    // ➕ CREAR EVENTO
    calendar.on('beforeCreateEvent', (event) => {

        let personal = document.getElementById('personal').value;
        let servicio = document.getElementById('servicio').value;

        let actividad = actividadSeleccionada ?
            actividadSeleccionada :
            document.getElementById('actividad').value;

        if (!personal || !servicio || !actividad) {
            Swal.fire('Error', 'Complete los campos', 'error');
            return;
        }

        let color = colores[actividad] || '#000';

        fetch(URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    personal_id: personal,
                    servicio_id: servicio,
                    actividad: actividad,
                    color: color,
                    inicio: event.start,
                    fin: event.end
                })
            })
            .then(() => {
                Swal.fire('Guardado', 'Programado correctamente', 'success');
                cargarTodo();
            });

    });


    // 🔄 DRAG & DROP
    calendar.on('beforeUpdateEvent', (event) => {

        fetch(URL + '/' + event.event.id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    inicio: event.changes.start,
                    fin: event.changes.end
                })
            })
            .then(() => {
                Swal.fire('Actualizado', 'Horario modificado', 'success');
                cargarTodo();
            });

    });
</script>