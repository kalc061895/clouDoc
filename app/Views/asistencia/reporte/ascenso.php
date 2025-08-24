<div class="container-fluid content-main mw-100">
    <div class="row">
        <div class="col-12">

            <div class="card h-100">
                <div class="card-body">
                    <div class="col-12">
                        <h2>Reporte de Ascenso</h2>
                        <p>Genera un reporte detallado de ascensos.</p>
                        <i>(Periodo Julio 2024 - Diciembre 2024)</i>
                    </div>

                    <form action="<?= base_url('asistencia/generar_reporte_ascenso') ?>" method="post" class="form" id="form-reporte-ascenso">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="dni" name="dni" required minlength="8" maxlength="8" placeholder="Ingrese el DNI del trabajador" maxlength="8" pattern="\d{8}">
                                </div>
                            </div>
                            <div class="col-6">

                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="row" id="respuesta">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <?php if (isset($data) && !empty($data)) : ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row) : ?>
                                        <tr>
                                            <td><?= esc($row['rep_ide']) ?></td>
                                            <td><?= esc($row['rep_titulo']) ?></td>
                                            <td><?= esc($row['rep_descripcion']) ?></td>
                                            <td><?= esc($row['rep_inicio']) ?></td>
                                            <td><?= esc($row['rep_fin']) ?></td>
                                            <td><?= esc($row['rep_estado']) ?></td>
                                            <td><?= esc($row['rep_fecha']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>No se encontraron registros.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>