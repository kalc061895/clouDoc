<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeys extends Migration
{
    public function up()
    {
        // 1. Convocatorias
        $this->forge->addForeignKey('con_tco_ide', 'selec_tipos_convocatoria', 'tco_ide', 'CASCADE', 'RESTRICT', 'selec_convocatorias');
        $this->forge->addForeignKey('con_eco_ide', 'selec_estados_convocatoria', 'eco_ide', 'CASCADE', 'RESTRICT', 'selec_convocatorias');

        // Documentos Convocatoria
        $this->forge->addForeignKey('cod_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE', 'selec_convocatoria_documentos');

        // 2. Cargos
        $this->forge->addForeignKey('car_tca_ide', 'selec_tipos_cargo', 'tca_ide', 'CASCADE', 'RESTRICT', 'selec_cargos');
        $this->forge->addForeignKey('car_gru_ide', 'selec_grupos_ocupacionales', 'gru_ide', 'CASCADE', 'RESTRICT', 'selec_cargos');
        $this->forge->addForeignKey('car_niv_ide', 'selec_niveles', 'niv_ide', 'CASCADE', 'RESTRICT', 'selec_cargos');
        $this->forge->addForeignKey('car_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'SET NULL', 'selec_cargos');

        // Convocatoria Cargos
        $this->forge->addForeignKey('cco_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE', 'selec_convocatoria_cargos');
        $this->forge->addForeignKey('cco_car_ide', 'selec_cargos', 'car_ide', 'CASCADE', 'RESTRICT', 'selec_convocatoria_cargos');

        // 3. Requisitos
        $this->forge->addForeignKey('req_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'CASCADE', 'selec_requisitos');

        $this->forge->addForeignKey('rfo_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'CASCADE', 'selec_requisito_formacion');
        $this->forge->addForeignKey('rfo_nfo_ide', 'selec_niveles_formacion', 'nfo_ide', 'CASCADE', 'SET NULL', 'selec_requisito_formacion');
        $this->forge->addForeignKey('rfo_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'SET NULL', 'selec_requisito_formacion');

        $this->forge->addForeignKey('rex_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'CASCADE', 'selec_requisito_experiencia');

        // 4. Cronograma
        $this->forge->addForeignKey('cet_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE', 'selec_convocatoria_etapas');
        $this->forge->addForeignKey('cet_eta_ide', 'selec_etapas', 'eta_ide', 'CASCADE', 'RESTRICT', 'selec_convocatoria_etapas');

        // 5. Postulantes
        $this->forge->addForeignKey('pos_tdo_ide', 'selec_tipos_documento', 'tdo_ide', 'CASCADE', 'RESTRICT', 'selec_postulantes');

        $this->forge->addForeignKey('pfo_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE', 'selec_postulante_formacion');
        $this->forge->addForeignKey('pfo_nfo_ide', 'selec_niveles_formacion', 'nfo_ide', 'CASCADE', 'SET NULL', 'selec_postulante_formacion');

        $this->forge->addForeignKey('ppr_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE', 'selec_postulante_profesiones');
        $this->forge->addForeignKey('ppr_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'SET NULL', 'selec_postulante_profesiones');

        $this->forge->addForeignKey('pex_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE', 'selec_postulante_experiencias');
        $this->forge->addForeignKey('pex_mvi_ide', 'selec_modalidades_vinculo', 'mvi_ide', 'CASCADE', 'SET NULL', 'selec_postulante_experiencias');

        $this->forge->addForeignKey('pca_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE', 'selec_postulante_capacitaciones');

        // 6. Postulación y Expediente
        $this->forge->addForeignKey('pto_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT', 'selec_postulaciones');
        $this->forge->addForeignKey('pto_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'RESTRICT', 'selec_postulaciones');
        $this->forge->addForeignKey('pto_epo_ide', 'selec_estados_postulacion', 'epo_ide', 'CASCADE', 'RESTRICT', 'selec_postulaciones');
        $this->forge->addForeignKey('pto_eex_ide', 'selec_estados_expediente', 'eex_ide', 'CASCADE', 'SET NULL', 'selec_postulaciones');

        $this->forge->addForeignKey('exd_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'CASCADE', 'selec_expediente_documentos');

        $this->forge->addForeignKey('pan_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'CASCADE', 'selec_postulacion_anexos');
        $this->forge->addForeignKey('pan_ane_ide', 'selec_anexos', 'ane_ide', 'CASCADE', 'RESTRICT', 'selec_postulacion_anexos');
        $this->forge->addForeignKey('pan_exd_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL', 'selec_postulacion_anexos');

        $this->forge->addForeignKey('vpo_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'CASCADE', 'selec_validaciones_postulacion');

        // Documentos de sustento del postulante
        $this->forge->addForeignKey('pfo_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL', 'selec_postulante_formacion');
        $this->forge->addForeignKey('ppr_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL', 'selec_postulante_profesiones');
        $this->forge->addForeignKey('pex_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL', 'selec_postulante_experiencias');
        $this->forge->addForeignKey('pca_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL', 'selec_postulante_capacitaciones');

        // 7. Anexos y plantillas
        $this->forge->addForeignKey('ane_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE', 'selec_anexos');
        $this->forge->addForeignKey('ane_archivo_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'SET NULL', 'selec_anexos');

        // 8. Comisión Evaluadora
        $this->forge->addForeignKey('com_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE', 'selec_comisiones');
        $this->forge->addForeignKey('com_documento_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'SET NULL', 'selec_comisiones');

        $this->forge->addForeignKey('cmi_com_ide', 'selec_comisiones', 'com_ide', 'CASCADE', 'CASCADE', 'selec_comision_miembros');
        $this->forge->addForeignKey('cmi_documento_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'SET NULL', 'selec_comision_miembros');
    }

    public function down()
    {
        // Eliminación de claves foráneas en orden inverso
        $this->forge->dropForeignKey('selec_comision_miembros', 'selec_comision_miembros_cmi_documento_ide_foreign');
        $this->forge->dropForeignKey('selec_comision_miembros', 'selec_comision_miembros_cmi_com_ide_foreign');
        $this->forge->dropForeignKey('selec_comisiones', 'selec_comisiones_com_documento_ide_foreign');
        $this->forge->dropForeignKey('selec_comisiones', 'selec_comisiones_com_con_ide_foreign');

        $this->forge->dropForeignKey('selec_anexos', 'selec_anexos_ane_archivo_ide_foreign');
        $this->forge->dropForeignKey('selec_anexos', 'selec_anexos_ane_con_ide_foreign');

        $this->forge->dropForeignKey('selec_postulante_capacitaciones', 'selec_postulante_capacitaciones_pca_documento_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_experiencias', 'selec_postulante_experiencias_pex_documento_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_profesiones', 'selec_postulante_profesiones_ppr_documento_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_formacion', 'selec_postulante_formacion_pfo_documento_ide_foreign');

        $this->forge->dropForeignKey('selec_validaciones_postulacion', 'selec_validaciones_postulacion_vpo_pto_ide_foreign');
        $this->forge->dropForeignKey('selec_postulacion_anexos', 'selec_postulacion_anexos_pan_exd_ide_foreign');
        $this->forge->dropForeignKey('selec_postulacion_anexos', 'selec_postulacion_anexos_pan_ane_ide_foreign');
        $this->forge->dropForeignKey('selec_postulacion_anexos', 'selec_postulacion_anexos_pan_pto_ide_foreign');

        $this->forge->dropForeignKey('selec_expediente_documentos', 'selec_expediente_documentos_exd_pto_ide_foreign');

        $this->forge->dropForeignKey('selec_postulaciones', 'selec_postulaciones_pto_eex_ide_foreign');
        $this->forge->dropForeignKey('selec_postulaciones', 'selec_postulaciones_pto_epo_ide_foreign');
        $this->forge->dropForeignKey('selec_postulaciones', 'selec_postulaciones_pto_cco_ide_foreign');
        $this->forge->dropForeignKey('selec_postulaciones', 'selec_postulaciones_pto_pos_ide_foreign');

        $this->forge->dropForeignKey('selec_postulante_capacitaciones', 'selec_postulante_capacitaciones_pca_pos_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_experiencias', 'selec_postulante_experiencias_pex_mvi_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_experiencias', 'selec_postulante_experiencias_pex_pos_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_profesiones', 'selec_postulante_profesiones_ppr_pro_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_profesiones', 'selec_postulante_profesiones_ppr_pos_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_formacion', 'selec_postulante_formacion_pfo_nfo_ide_foreign');
        $this->forge->dropForeignKey('selec_postulante_formacion', 'selec_postulante_formacion_pfo_pos_ide_foreign');
        $this->forge->dropForeignKey('selec_postulantes', 'selec_postulantes_pos_tdo_ide_foreign');

        $this->forge->dropForeignKey('selec_convocatoria_etapas', 'selec_convocatoria_etapas_cet_eta_ide_foreign');
        $this->forge->dropForeignKey('selec_convocatoria_etapas', 'selec_convocatoria_etapas_cet_con_ide_foreign');

        $this->forge->dropForeignKey('selec_requisito_experiencia', 'selec_requisito_experiencia_rex_req_ide_foreign');
        $this->forge->dropForeignKey('selec_requisito_formacion', 'selec_requisito_formacion_rfo_pro_ide_foreign');
        $this->forge->dropForeignKey('selec_requisito_formacion', 'selec_requisito_formacion_rfo_nfo_ide_foreign');
        $this->forge->dropForeignKey('selec_requisito_formacion', 'selec_requisito_formacion_rfo_req_ide_foreign');
        $this->forge->dropForeignKey('selec_requisitos', 'selec_requisitos_req_cco_ide_foreign');

        $this->forge->dropForeignKey('selec_convocatoria_cargos', 'selec_convocatoria_cargos_cco_car_ide_foreign');
        $this->forge->dropForeignKey('selec_convocatoria_cargos', 'selec_convocatoria_cargos_cco_con_ide_foreign');
        $this->forge->dropForeignKey('selec_cargos', 'selec_cargos_car_pro_ide_foreign');
        $this->forge->dropForeignKey('selec_cargos', 'selec_cargos_car_niv_ide_foreign');
        $this->forge->dropForeignKey('selec_cargos', 'selec_cargos_car_gru_ide_foreign');
        $this->forge->dropForeignKey('selec_cargos', 'selec_cargos_car_tca_ide_foreign');

        $this->forge->dropForeignKey('selec_convocatoria_documentos', 'selec_convocatoria_documentos_cod_con_ide_foreign');
        $this->forge->dropForeignKey('selec_convocatorias', 'selec_convocatorias_con_eco_ide_foreign');
        $this->forge->dropForeignKey('selec_convocatorias', 'selec_convocatorias_con_tco_ide_foreign');
    }
}
