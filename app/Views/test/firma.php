<!-- incluye jQuery 3.6 y el script del servicio -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var jqFirmaPeru = jQuery.noConflict(true);
</script>
<script src="https://apps.firmaperu.gob.pe/web/clienteweb/firmaperu.min.js"></script>

<div id="addComponent" style="display:none;"></div>

<input type="file" id="doc" accept="application/pdf">
<button id="btnFirmar">Firmar</button>

<script>
    function signatureInit() {
        /* puedes mostrar loader */ }

    function signatureOk() {
        alert('Documento firmado.');
    }

    function signatureCancel() {
        alert('Operación cancelada.');
    }

    document.getElementById('btnFirmar').onclick = async () => {
        const file = document.getElementById('doc').files[0];
        if (!file) {
            alert('Selecciona un PDF');
            return;
        }

        // 1) crea el job
        const fd = new FormData();
        fd.append('document', file);
        // si manejas múltiples perfiles por user: fd.append('profile_id', '1');

        const res = await fetch('<?= site_url('api/signature/job') ?>', {
            method: 'POST',
            body: fd
        });
        const data = await res.json();
        if (!data.param_base64) {
            alert('No se pudo crear el job');
            return;
        }
        console.log(data);
        console.log(  data.param );
        
        
        param_2 =  btoa(data.param);
        console.log('param_2: '+param_2);

        // 2) dispara el firmador
        //startSignature(data.port || 48596, param_2);
        startSignature(data.port || 48596, data.param_base64);
    };
</script>