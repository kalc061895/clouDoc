<?php
use Modules\Asistencia\Services\RolDocumentoData;
$historial = $historial ?? false;
?>
<div class="row g-3">
    <div class="col-md-2"><label for="rol-anio" class="form-label">Año</label><input id="rol-anio" class="form-control" name="anio" type="number" min="2000" max="2100" value="<?= date('Y') ?>" <?= $historial ? '' : 'required' ?>></div>
    <div class="col-md-2"><label for="rol-mes" class="form-label">Mes</label><select id="rol-mes" class="form-select" name="mes" <?= $historial ? '' : 'required' ?>>
        <?php if ($historial): ?><option value="">Todos</option><?php endif ?>
        <?php foreach (RolDocumentoData::MESES as $n => $nombre): ?><option value="<?= $n ?>" <?= ! $historial && $n === (int) date('n') ? 'selected' : '' ?>><?= esc($nombre) ?></option><?php endforeach ?>
    </select></div>
    <div class="col-md-<?= $historial ? '5' : '8' ?>"><label for="rol-est" class="form-label">Establecimiento</label><select id="rol-est" class="form-select" name="est_ide" <?= $historial ? '' : 'required' ?>>
        <option value=""><?= $historial ? 'Todos' : 'Seleccione un establecimiento' ?></option>
        <?php foreach ($catalogos['establecimientos'] as $est): ?><option value="<?= (int) $est['est_ide'] ?>"><?= esc($est['est_nombre']) ?></option><?php endforeach ?>
    </select></div>
    <?php if ($historial): ?>
    <div class="col-md-3"><label for="rol-estado" class="form-label">Estado</label><select id="rol-estado" class="form-select" name="estado"><option value="">Todos</option><option value="GENERADO">Generado</option><option value="ANULADO">Anulado</option></select></div>
    <?php else: ?>
    <div class="col-md-4"><label for="rol-tipo" class="form-label">Tipo de oficina</label><select id="rol-tipo" class="form-select" name="tofi_ide"><option value="">Todos los tipos</option><?php foreach ($catalogos['tipos'] as $tipo): ?><option value="<?= (int) $tipo['tofi_ide'] ?>"><?= esc($tipo['tofi_nombre']) ?></option><?php endforeach ?></select></div>
    <div class="col-md-8"><label for="rol-oficina" class="form-label">Departamento / oficina / servicio / área</label><select id="rol-oficina" class="form-select" name="ofi_ide"><option value="">Todas las oficinas</option></select></div>
    <div class="col-md-6"><label for="rol-upss" class="form-label">UPSS</label><select id="rol-upss" class="form-select" name="ups_ide"><option value="">Todas las UPSS</option></select></div>
    <div class="col-md-6"><label for="rol-servicio" class="form-label">Servicio UPSS</label><select id="rol-servicio" class="form-select" name="uss_ide"><option value="">Todos los servicios</option></select></div>
    <div class="col-12"><div class="form-check"><input id="rol-hijos" class="form-check-input" type="checkbox" name="incluir_hijos" value="1" checked><label for="rol-hijos" class="form-check-label">Incluir oficinas y áreas dependientes del departamento o tipo seleccionado</label></div></div>
    <?php endif ?>
</div>
