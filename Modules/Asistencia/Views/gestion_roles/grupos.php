<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Grupos de programación de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3"><div class="card border-0 shadow-sm"><div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><div><h1 class="h3 mb-1">Grupos de programación</h1><p class="text-muted mb-0">Define el alcance institucional que compartirán uno o más periodos.</p></div><button class="btn btn-primary" type="button" onclick="nuevoGrupo()"><iconify-icon icon="lucide:plus" class="me-1"></iconify-icon>Nuevo grupo</button></div>
    <?= view('Modules\\Asistencia\\Views\\gestion_roles\\navegacion',['admin'=>true]) ?>
    <?php foreach (['error'=>'error','success'=>'success'] as $key=>$toast): if ($flash=session()->getFlashdata($key)): ?><script>document.addEventListener('DOMContentLoaded',()=>toastr.<?= $toast ?>(<?= json_encode($flash,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>));</script><?php endif; endforeach ?>
    <div class="table-responsive"><table class="table table-striped table-hover align-middle" id="tablaGruposRol"><thead><tr><th>Nombre</th><th>Nivel</th><th>Entidad</th><th>Periodos</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
    <?php foreach ($grupos as $g): ?><tr><td><?= esc($g['nombre']) ?></td><td><?= esc($g['nivel']) ?></td><td><?= esc($g['alcance_nombre'] ?? '') ?></td><td><?= (int)$g['periodos'] ?></td><td><span class="badge <?= $g['estado']==='ACTIVO'?'text-bg-success':'text-bg-secondary' ?>"><?= esc($g['estado']) ?></span></td><td class="text-nowrap">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick='editarGrupo(<?= json_encode(['id'=>(int)$g['id'],'nivel'=>$g['nivel'],'entidad_id'=>(int)$g['entidad_id'],'nombre'=>$g['nombre']],JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'><iconify-icon icon="lucide:pencil"></iconify-icon> Editar</button>
        <?php if (!(int)$g['periodos']): ?><form method="post" action="<?= site_url('asistencia/gestion-roles/grupos/eliminar/'.(int)$g['id']) ?>" class="d-inline js-eliminar-grupo"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger"><iconify-icon icon="lucide:trash-2"></iconify-icon></button></form><?php endif ?>
    </td></tr><?php endforeach ?>
    </tbody></table></div>
</div></div></div>
<div class="modal fade" id="modalGrupoRol" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><form method="post" action="<?= site_url('asistencia/gestion-roles/grupos/guardar') ?>"><?= csrf_field() ?><input type="hidden" name="id" id="grupo-id">
    <div class="modal-header"><h2 class="modal-title fs-5" id="grupo-titulo">Nuevo grupo</h2><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
        <label class="form-label w-100">Nombre<input class="form-control" name="nombre" id="grupo-nombre" maxlength="150" required></label>
        <label class="form-label w-100">Nivel de aplicación<select class="form-select" id="grupo-nivel" name="nivel" required><option value="">Seleccione</option><option value="ESTABLECIMIENTO">Establecimiento</option><option value="MICRORED">Microred</option><option value="RED">Red</option><option value="DIRESA">DIRESA</option></select></label>
        <label class="form-label w-100">Entidad<select class="form-select" id="grupo-entidad" name="entidad_id" required><option value="">Seleccione primero el nivel</option></select></label>
        <p class="small text-muted mb-0">El periodo de un grupo se aplicará a sus entidades descendientes cuando no exista un periodo más específico.</p>
    </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary">Guardar grupo</button></div>
</form></div></div></div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
const catalogosGrupo={
 DIRESA:<?= json_encode(array_map(fn($r)=>['id'=>$r['dir_ide'],'nombre'=>$r['dir_nombre']],$diresas),JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>,
 RED:<?= json_encode(array_map(fn($r)=>['id'=>$r['red_ide'],'nombre'=>$r['red_nombre']],$redes),JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>,
 MICRORED:<?= json_encode(array_map(fn($r)=>['id'=>$r['mic_ide'],'nombre'=>$r['mic_nombre']],$microredes),JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>,
 ESTABLECIMIENTO:<?= json_encode(array_map(fn($r)=>['id'=>$r['est_ide'],'nombre'=>$r['est_nombre']],$establecimientos),JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>
};
const modalGrupo=bootstrap.Modal.getOrCreateInstance(document.getElementById('modalGrupoRol'));
function opcionesEntidad(nivel,seleccion=''){const s=document.getElementById('grupo-entidad');s.innerHTML='<option value="">Seleccione una entidad...</option>'+(catalogosGrupo[nivel]||[]).map(x=>`<option value="${x.id}">${String(x.nombre).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}</option>`).join('');s.value=seleccion;}
document.getElementById('grupo-nivel').addEventListener('change',e=>opcionesEntidad(e.target.value));
function nuevoGrupo(){document.getElementById('grupo-titulo').textContent='Nuevo grupo';document.getElementById('grupo-id').value='0';document.getElementById('grupo-nombre').value='';document.getElementById('grupo-nivel').value='';opcionesEntidad('');modalGrupo.show();}
function editarGrupo(g){document.getElementById('grupo-titulo').textContent='Editar grupo';document.getElementById('grupo-id').value=g.id;document.getElementById('grupo-nombre').value=g.nombre;document.getElementById('grupo-nivel').value=g.nivel;opcionesEntidad(g.nivel,g.entidad_id);modalGrupo.show();}
$(function(){if($.fn.DataTable)$('#tablaGruposRol').DataTable({language:{emptyTable:'No hay grupos',search:'Buscar:',lengthMenu:'Mostrar _MENU_ registros',zeroRecords:'No se encontraron grupos',paginate:{first:'Primero',last:'Último',next:'Siguiente',previous:'Anterior'}},pageLength:25,columnDefs:[{targets:5,orderable:false,searchable:false}]});document.querySelectorAll('.js-eliminar-grupo').forEach(f=>f.addEventListener('submit',async e=>{e.preventDefault();const r=await Swal.fire({title:'¿Eliminar grupo?',text:'Esta operación no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Eliminar',cancelButtonText:'Cancelar',confirmButtonColor:'#d33'});if(r.isConfirmed)f.submit();}));});
</script>
<?= $this->endSection() ?>
