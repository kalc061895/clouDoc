<div class="container-fluid">
    <div class="row">

        <!-- PANEL -->
        <div class="col-md-3">

            <div class="card shadow-sm mb-3">
                <div class="card-body">

                    <h5>🧾 Programación</h5>

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

            <!-- ACTIVIDADES -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <h6>⚡ Actividades</h6>

                    <button class="btn btn-primary w-100 mb-1 actividad-btn" data-tipo="Consulta externa">Consulta externa</button>
                    <button class="btn btn-danger w-100 mb-1 actividad-btn" data-tipo="Hospitalización">Hospitalización</button>
                    <button class="btn btn-success w-100 actividad-btn" data-tipo="Administrativo">Administrativo</button>

                </div>
            </div>

        </div>

        <!-- CALENDARIO -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div id="scheduler" style="height: 750px;"></div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const URL = "<?= base_url('asistencia/programacion/eventos') ?>";

    const colores = {
        'Consulta externa': '#0d6efd',
        'Hospitalización': '#dc3545',
        'Administrativo': '#198754'
    };

    let actividadSeleccionada = null;

    // botones
    document.querySelectorAll('.actividad-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            actividadSeleccionada = btn.dataset.tipo;

            Swal.fire({
                icon: 'info',
                title: actividadSeleccionada,
                timer: 1000,
                showConfirmButton: false
            });
        });
    });


    // 📅 DAYPILOT
    const dp = new DayPilot.Calendar("scheduler");

    dp.viewType = "Week";
    dp.startDate = DayPilot.Date.today();

    dp.businessBeginsHour = 6;
    dp.businessEndsHour = 20;

    dp.timeRangeSelectedHandling = "Enabled";
    dp.eventMoveHandling = "Update";


    // ➕ CREAR EVENTO
    dp.onTimeRangeSelected = function(args) {

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
                    inicio: args.start.toString(),
                    fin: args.end.toString()
                })
            })
            .then(() => {
                Swal.fire('Guardado', 'Programado', 'success');
                cargarEventos();
            });

    };


    // 🔄 MOVER EVENTO
    dp.onEventMoved = function(args) {

        fetch(URL + '/' + args.e.id(), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    inicio: args.newStart.toString(),
                    fin: args.newEnd.toString()
                })
            })
            .then(() => {
                Swal.fire('Actualizado', 'Horario modificado', 'success');
            });

    };


    // 📥 CARGAR EVENTOS + COMBOS
    function cargarEventos() {

        fetch(URL)
            .then(res => res.json())
            .then(data => {

                // combos
                cargarCombos(data);

                // eventos
                dp.events.list = data.eventos.map(e => ({
                    id: e.id,
                    text: e.titulo,
                    start: e.inicio,
                    end: e.fin,
                    backColor: e.color
                }));

                dp.update();

            });
    }

    cargarEventos();
    dp.init();


    // combos
    function cargarCombos(data) {

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
            actividad.innerHTML += `<option value="${a.nombre}">
      ${a.nombre}
    </option>`;
        });

    }
</script>