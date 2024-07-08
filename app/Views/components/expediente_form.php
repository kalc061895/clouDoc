<form class="floating-labels mt-4 pt-2" action="<?= base_url('expediente/save') ?>" method="post" enctype="multipart/form-data">
    <div class="form-group mb-4">
        <input type="text" class="form-control" id="numero_expediente" name="numero_expediente" required>
        <span class="bar"></span>
        <label for="numero_expediente">Número de Expediente</label>
    </div>
    <div class="form-group">
        <label for="procedencia">Procedencia</label>
        <select class="form-control" id="procedencia" name="procedencia" required>
            <option value="interno">Interno</option>
            <option value="externo">Externo</option>
        </select>
    </div>
    <div class="form-group">
        <label for="fecha_recepcion">Fecha de Recepción</label>
        <input type="datetime-local" class="form-control" id="fecha_recepcion" name="fecha_recepcion" required>
    </div>
    <div class="form-group">
        <label for="folios">Folios</label>
        <input type="number" class="form-control" id="folios" name="folios" required>
    </div>
    <div class="form-group">
        <label for="tipo_documento">Tipo de Documento</label>
        <select class="form-control" id="tipo_documento" name="tipo_documento" required>
            <!-- Aquí se deben cargar los tipos de documentos disponibles -->
        </select>
    </div>
    <div class="form-group">
        <label for="numero_documento">Número de Documento</label>
        <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
    </div>
    <div class="form-group">
        <label for="entidad_id">Entidad</label>
        <select class="form-control" id="entidad_id" name="entidad_id" required>
            <!-- Aquí se deben cargar las entidades disponibles -->
        </select>
    </div>
    <div class="form-group">
        <label for="asunto">Asunto</label>
        <input type="text" class="form-control" id="asunto" name="asunto" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label for="atencion_oficina_id">Atención Oficina</label>
        <select class="form-control" id="atencion_oficina_id" name="atencion_oficina_id" required>
            <!-- Aquí se deben cargar las oficinas disponibles -->
        </select>
    </div>
    <div class="form-group">
        <label for="observacion">Observación</label>
        <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
