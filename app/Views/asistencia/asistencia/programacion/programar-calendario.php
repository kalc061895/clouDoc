
<div class="container-fluid mt-4">

    <!-- HEADER -->
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">Calendario de Programación</h4>
            <small class="text-muted">Gestión de turnos del personal</small>
        </div>
        <button class="btn btn-success" id="btnNuevo">+ Nuevo</button>
    </div>

    <div class="row">

        <!-- PANEL IZQUIERDO -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">

                    <h6>Trabajador</h6>
                    <select id="trabajador" class="form-control mb-3"></select>

                    <h6>Turnos</h6>

                    <div id="external-events">

                        <div class="fc-event bg-primary text-white p-2 mb-2" data-turno="M">
                            Turno Mañana (07:00 - 13:00)
                        </div>

                        <div class="fc-event bg-success text-white p-2 mb-2" data-turno="T">
                            Turno Tarde (13:00 - 19:00)
                        </div>

                        <div class="fc-event bg-dark text-white p-2 mb-2" data-turno="N">
                            Turno Noche (19:00 - 07:00)
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- CALENDARIO -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body calendar-sidebar app-calendar">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="eventModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Programar Turno</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <!-- TRABAJADOR -->
                    <div class="col-md-12">
                        <label>Trabajador</label>
                        <select id="trabajador_modal" class="form-control"></select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>DNI</label>
                        <input id="dni" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Apellidos</label>
                        <input id="apellidos" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Nombres</label>
                        <input id="nombres" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Cargo</label>
                        <input id="cargo" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Especialidad</label>
                        <input id="especialidad" class="form-control" readonly>
                    </div>

                    <!-- CONFIG -->
                    <div class="col-md-4 mt-3">
                        <label>Tipo</label>
                        <select id="tipo" class="form-control">
                            <option value="ADMIN">Administrativo</option>
                            <option value="ASIS">Asistencial</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Turno</label>
                        <select id="turno" class="form-control">
                            <option value="M">Mañana</option>
                            <option value="T">Tarde</option>
                            <option value="N">Noche</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Color</label>
                        <input type="color" id="color" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>UPSS</label>
                        <input id="upss" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Ambiente</label>
                        <input id="ambiente" class="form-control">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label>Actividad</label>
                        <input id="actividad" class="form-control">
                    </div>

                    <!-- FECHAS -->
                    <div class="col-md-6 mt-3">
                        <label>Inicio</label>
                        <input id="event-start-date" type="datetime-local" class="form-control">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Fin</label>
                        <input id="event-end-date" type="datetime-local" class="form-control">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardar">Guardar</button>
                <button class="btn btn-warning" id="btnActualizar">Actualizar</button>
                <button class="btn btn-danger" id="btnEliminar">Eliminar</button>
            </div>

        </div>
    </div>
</div>

<script>
    let calendar;
    let eventoActual = null;
    let modal = new bootstrap.Modal(document.getElementById('eventModal'));

    initCalendar();

    function initCalendar() {

        cargarTrabajadores();

        let calendarEl = document.getElementById("calendar");

        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            selectable: true,
            editable: true,
            droppable: true,

            initialView: "dayGridMonth",

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            events: '<?= base_url("asistencia/programacion/eventos") ?>',

            select: function(info) {
                limpiarModal();
                eventoActual = null;
                document.getElementById("event-start-date").value = info.startStr + "T07:00";
                document.getElementById("event-end-date").value = info.startStr + "T13:00";
                modal.show();
            },

            eventClick: function(info) {
                let e = info.event;
                eventoActual = e;

                document.getElementById("trabajador_modal").value = e.extendedProps.trabajador_id;
                autocompletarTrabajador(e.extendedProps);

                document.getElementById("turno").value = e.extendedProps.turno;
                document.getElementById("tipo").value = e.extendedProps.tipo;
                document.getElementById("upss").value = e.extendedProps.upss;
                document.getElementById("ambiente").value = e.extendedProps.ambiente;
                document.getElementById("actividad").value = e.extendedProps.actividad;
                document.getElementById("color").value = e.extendedProps.color_actividad;

                document.getElementById("event-start-date").value = e.startStr.substring(0, 16);
                document.getElementById("event-end-date").value = e.endStr ? e.endStr.substring(0, 16) : e.startStr.substring(0, 16);

                modal.show();
            },

            eventDidMount: function(info) {
                let color = info.event.extendedProps.color_actividad;
                if (color) {
                    info.el.style.backgroundColor = color;
                    info.el.style.borderColor = color;
                }
            },

            drop: function(info) {
                let turno = info.draggedEl.getAttribute('data-turno');
                let trabajador = document.getElementById("trabajador").value;

                let data = {
                    trabajador_id: trabajador,
                    turno: turno,
                    tipo: 'ASIS',
                    start: info.dateStr,
                    end: info.dateStr
                };

                fetch('<?= base_url("asistencia/programacion/eventos") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(() => calendar.refetchEvents());
            }
        });

        calendar.render();

        document.getElementById("btnNuevo").onclick = () => {
            limpiarModal();
            eventoActual = null;
            modal.show();
        };

        document.getElementById("btnGuardar").onclick = () => guardarEvento();
        document.getElementById("btnActualizar").onclick = () => actualizarEvento();
        document.getElementById("btnEliminar").onclick = () => eliminarEvento();
    }

    function cargarTrabajadores() {
        fetch('<?= base_url("asistencia/programacion/trabajadores") ?>')
            .then(res => res.json())
            .then(data => {
                let select1 = document.getElementById("trabajador");
                let select2 = document.getElementById("trabajador_modal");

                select1.innerHTML = '<option value="">Seleccione...</option>';
                select2.innerHTML = '<option value="">Seleccione...</option>';

                data.forEach(t => {
                    let opt = new Option(t.apellidos + ' ' + t.nombres, t.id);
                    opt.dataset = t;

                    select1.add(opt.cloneNode(true));
                    select2.add(opt);
                });
            });
    }

    document.getElementById("trabajador_modal").addEventListener("change", function() {
        let opt = this.selectedOptions[0];
        autocompletarTrabajador(opt.dataset);
    });

    function autocompletarTrabajador(data) {
        document.getElementById("dni").value = data.dni || '';
        document.getElementById("apellidos").value = data.apellidos || '';
        document.getElementById("nombres").value = data.nombres || '';
        document.getElementById("cargo").value = data.cargo || '';
        document.getElementById("especialidad").value = data.especialidad || '';
        document.getElementById("tipo").value = data.tipo || 'ASIS';
    }

    function obtenerDatos() {
        let t = document.getElementById("trabajador_modal").selectedOptions[0].dataset;

        return {
            trabajador_id: document.getElementById("trabajador_modal").value,
            trabajador_dni: t.dni,
            trabajador_apellidos: t.apellidos,
            trabajador_nombres: t.nombres,
            trabajador_cargo: t.cargo,
            trabajador_especialidad: t.especialidad,

            tipo: document.getElementById("tipo").value,
            turno: document.getElementById("turno").value,

            upss: document.getElementById("upss").value,
            ambiente: document.getElementById("ambiente").value,
            actividad: document.getElementById("actividad").value,
            color_actividad: document.getElementById("color").value,

            start: document.getElementById("event-start-date").value,
            end: document.getElementById("event-end-date").value
        };
    }

    function guardarEvento() {
        fetch('<?= base_url("asistencia/programacion/eventos") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(obtenerDatos())
        }).then(() => {
            calendar.refetchEvents();
            modal.hide();
        });
    }

    function actualizarEvento() {
        if (!eventoActual) return;
        fetch('<?= base_url("asistencia/programacion/eventos") ?>/' + eventoActual.id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(obtenerDatos())
        }).then(() => {
            calendar.refetchEvents();
            modal.hide();
        });
    }

    function eliminarEvento() {
        if (!eventoActual) return;
        if (!confirm("¿Eliminar turno?")) return;

        fetch('<?= base_url("asistencia/programacion/eventos") ?>/' + eventoActual.id, {
            method: 'DELETE'
        }).then(() => {
            calendar.refetchEvents();
            modal.hide();
        });
    }

    function limpiarModal() {
        document.querySelectorAll('#eventModal input, #eventModal select').forEach(e => e.value = '');
    }
</script>