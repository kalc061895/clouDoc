<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatosGeneralesSeeder extends Seeder
{
    public function run()
    {
        // 1. Tipos de Convocatoria
        $this->db->table('selec_tipos_convocatoria')->insertBatch([
            ['tco_codigo' => 'CAS', 'tco_nombre' => 'Contratación Administrativa de Servicios', 'tco_descripcion' => 'Proceso de selección bajo el régimen D.L. 1057', 'tco_estado' => 'ACTIVO'],
            ['tco_codigo' => 'DL276', 'tco_nombre' => 'Carrera Administrativa D.L. 276', 'tco_descripcion' => 'Nombramiento y contratación bajo Decreto Legislativo 276', 'tco_estado' => 'ACTIVO'],
            ['tco_codigo' => 'DL728', 'tco_nombre' => 'Régimen Laboral Privado D.L. 728', 'tco_descripcion' => 'Proceso de selección bajo Ley de Fomento del Empleo', 'tco_estado' => 'ACTIVO'],
            ['tco_codigo' => 'PRACT', 'tco_nombre' => 'Practicantes Pre / Profesionales', 'tco_descripcion' => 'Convenios de modalidades formativas de servicios', 'tco_estado' => 'ACTIVO'],
        ]);

        // 2. Estados de Convocatoria
        $this->db->table('selec_estados_convocatoria')->insertBatch([
            ['eco_codigo' => 'BORRADOR', 'eco_nombre' => 'En Enlace / Borrador', 'eco_descripcion' => 'Convocatoria en proceso de registro', 'eco_orden' => 1, 'eco_estado' => 'ACTIVO'],
            ['eco_codigo' => 'PUBLICADA', 'eco_nombre' => 'Publicada', 'eco_descripcion' => 'Convocatoria difundida en el portal institucional y SERVIR', 'eco_orden' => 2, 'eco_estado' => 'ACTIVO'],
            ['eco_codigo' => 'EN_PROCESO', 'eco_nombre' => 'En Evaluación', 'eco_descripcion' => 'Postulaciones cerradas y en evaluación por comisión', 'eco_orden' => 3, 'eco_estado' => 'ACTIVO'],
            ['eco_codigo' => 'CONCLUIDA', 'eco_nombre' => 'Concluida con Ganador', 'eco_descripcion' => 'Proceso finalizado con adjudicación de plaza', 'eco_orden' => 4, 'eco_estado' => 'ACTIVO'],
            ['eco_codigo' => 'DESIERTA', 'eco_nombre' => 'Declarada Desierta', 'eco_descripcion' => 'Sin postulantes aptos o ganadores', 'eco_orden' => 5, 'eco_estado' => 'ACTIVO'],
            ['eco_codigo' => 'CANCELADA', 'eco_nombre' => 'Cancelada / Cancelado', 'eco_descripcion' => 'Proceso dejado sin efecto por necesidad institucional', 'eco_orden' => 6, 'eco_estado' => 'ACTIVO'],
        ]);

        // 3. Tipos de Cargo
        $this->db->table('selec_tipos_cargo')->insertBatch([
            ['tca_codigo' => 'ASISTENCIAL', 'tca_nombre' => 'Personal Asistencial de Salud', 'tca_estado' => 'ACTIVO'],
            ['tca_codigo' => 'ADMINISTRATIVO', 'tca_nombre' => 'Personal Administrativo', 'tca_estado' => 'ACTIVO'],
            ['tca_codigo' => 'DIRECTIVO', 'tca_nombre' => 'Empleado de Confianza / Directivo', 'tca_estado' => 'ACTIVO'],
        ]);

        // 4. Grupos Ocupacionales
        $this->db->table('selec_grupos_ocupacionales')->insertBatch([
            ['gru_codigo' => 'PROF', 'gru_nombre' => 'Profesional', 'gru_estado' => 'ACTIVO'],
            ['gru_codigo' => 'TECN', 'gru_nombre' => 'Técnico', 'gru_estado' => 'ACTIVO'],
            ['gru_codigo' => 'AUX', 'gru_nombre' => 'Auxiliar', 'gru_estado' => 'ACTIVO'],
            ['gru_codigo' => 'FUNC', 'gru_nombre' => 'Funcionario', 'gru_estado' => 'ACTIVO'],
        ]);

        // 5. Niveles
        $this->db->table('selec_niveles')->insertBatch([
            ['niv_codigo' => 'PF-1', 'niv_nombre' => 'Profesional Nivel 1', 'niv_estado' => 'ACTIVO'],
            ['niv_codigo' => 'TC-1', 'niv_nombre' => 'Técnico Nivel 1', 'niv_estado' => 'ACTIVO'],
            ['niv_codigo' => 'AUX-1', 'niv_nombre' => 'Auxiliar Nivel 1', 'niv_estado' => 'ACTIVO'],
            ['niv_codigo' => 'F-1', 'niv_nombre' => 'Nivel F-1 / Jefatura', 'niv_estado' => 'ACTIVO'],
        ]);

        // 6. Modalidades de Vínculo (Experiencia previa)
        $this->db->table('selec_modalidades_vinculo')->insertBatch([
            ['mvi_codigo' => 'PUB_CAS', 'mvi_nombre' => 'Sector Público - Contrato CAS', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PUB_276', 'mvi_nombre' => 'Sector Público - Contrato 276', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PUB_NOM', 'mvi_nombre' => 'Sector Público - Nombrado / Planta', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PUB_LOC', 'mvi_nombre' => 'Sector Público - Locación de Servicios (Terceros)', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PRIV_DEP', 'mvi_nombre' => 'Sector Privado - Dependiente (Planilla)', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PRIV_IND', 'mvi_nombre' => 'Sector Privado - Independiente (Honorarios)', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'PRACT_PRO', 'mvi_nombre' => 'Prácticas Profesionales', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'SEC_SERUMS', 'mvi_nombre' => 'SERUMS (Sector Salud)', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'SEC_RESID', 'mvi_nombre' => 'Residentado Médico / Especialidad', 'mvi_estado' => 'ACTIVO'],
            ['mvi_codigo' => 'OTROS', 'mvi_nombre' => 'Otras Modalidades', 'mvi_estado' => 'ACTIVO'],
        ]);

        // 7. Profesiones
        $this->db->table('selec_profesiones')->insertBatch([
           
            ['pro_codigo' => 'TEC_MED', 'pro_nombre' => 'TECNOLGO MEDICO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ING_SYS', 'pro_nombre' => 'INGENIERO DE SISTEMAS', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'MED_CIR', 'pro_nombre' => 'MEDICO CIRUJANO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ASIS_SOC', 'pro_nombre' => 'ASISTENTE SOCIAL', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ENF', 'pro_nombre' => 'ENFERMERA/O', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'NUT', 'pro_nombre' => 'NUTRICIONISTA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'OBS', 'pro_nombre' => 'OBSTETRA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_LAB', 'pro_nombre' => 'TECNICO EN LABORATORIO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'CONT', 'pro_nombre' => 'CONTADOR', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ING_EST', 'pro_nombre' => 'INGENIERO ESTADISTICO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'SEC_COMP', 'pro_nombre' => 'SECUNDARIA COMPLETA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_REH', 'pro_nombre' => 'TECNICO EN REHABILITACION', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ING_ELEC', 'pro_nombre' => 'ING ELECTRONICO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_MANT', 'pro_nombre' => 'TECNICO EN MANTENIMIENTO', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_RAD', 'pro_nombre' => 'TECNICO EN RADIOLOGIA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_CONT', 'pro_nombre' => 'TECNICO EN CONTABILIDAD', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_ADM', 'pro_nombre' => 'TECNICO EN ADMINISTRACION', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_COMP', 'pro_nombre' => 'TECNICO EN COMPUTACION E INFORMATICA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_FAR', 'pro_nombre' => 'TECNICO EN FARMACIA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'TEC_EST', 'pro_nombre' => 'TECNICO EN ESTADISTICA', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'ING_CIV', 'pro_nombre' => 'INGENIERO CIVIL', 'pro_estado' => 'ACTIVO'],
            ['pro_codigo' => 'CONT_PUB', 'pro_nombre' => 'CONTADOR PUBLICO', 'pro_estado' => 'ACTIVO'],
        ]);

        // 8. Niveles de Formación
        $this->db->table('selec_niveles_formacion')->insertBatch([
            ['nfo_codigo' => 'SEC_INC', 'nfo_nombre' => 'Secundaria Incompleta', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'SEC_COM', 'nfo_nombre' => 'Secundaria Completa', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'TEC_INC', 'nfo_nombre' => 'Técnica Superior Incompleta', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'TEC_COM', 'nfo_nombre' => 'Técnica Superior Completa (Titulado)', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'UNIV_EGR', 'nfo_nombre' => 'Universitaria - Egresado', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'UNIV_BAC', 'nfo_nombre' => 'Universitaria - Bachiller', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'UNIV_TIT', 'nfo_nombre' => 'Universitaria - Titulado con Colegiatura Habilitada', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'MAESTRIA', 'nfo_nombre' => 'Grado académico de Maestría', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'DOCTORADO', 'nfo_nombre' => 'Grado académico de Doctorado', 'nfo_estado' => 'ACTIVO'],
            ['nfo_codigo' => 'ESPECIALIZACION', 'nfo_nombre' => 'Grado académico de Especialización', 'nfo_estado' => 'ACTIVO'],
        ]);

        // 9. Tipos de Documento de Identidad
        $this->db->table('selec_tipos_documento')->insertBatch([
            ['tdo_codigo' => 'DNI', 'tdo_nombre' => 'Documento Nacional de Identidad', 'tdo_estado' => 'ACTIVO'],
            ['tdo_codigo' => 'CE', 'tdo_nombre' => 'Carnet de Extranjería', 'tdo_estado' => 'ACTIVO'],
            ['tdo_codigo' => 'PAS', 'tdo_nombre' => 'Pasaporte', 'tdo_estado' => 'ACTIVO'],
            ['tdo_codigo' => 'PTP', 'tdo_nombre' => 'Permiso Temporal de Permanencia', 'tdo_estado' => 'ACTIVO'],
        ]);

        // 10. Estados de Postulación
        $this->db->table('selec_estados_postulacion')->insertBatch([
            ['epo_codigo' => 'REGISTRADA', 'epo_nombre' => 'Postulación Registrada', 'epo_descripcion' => 'Postulación enviada por el usuario', 'epo_orden' => 1],
            ['epo_codigo' => 'REGISTRANDO', 'epo_nombre' => 'Postulación en Proceso', 'epo_descripcion' => 'Postulación iniciada por el usuario', 'epo_orden' => 1],
            ['epo_codigo' => 'APTO_CV', 'epo_nombre' => 'Apto Curricular', 'epo_descripcion' => 'Cumple con los requisitos mínimos de CV', 'epo_orden' => 2],
            ['epo_codigo' => 'NO_APTO_CV', 'epo_nombre' => 'No Apto Curricular', 'epo_descripcion' => 'No cumple con requisitos o la documentación obligatoria', 'epo_orden' => 3],
            ['epo_codigo' => 'APTO_ENT', 'epo_nombre' => 'Apto para Entrevista', 'epo_descripcion' => 'Aprobó las fases preliminares / evaluaciones técnicas', 'epo_orden' => 4],
            ['epo_codigo' => 'GANADOR', 'epo_nombre' => 'Ganador', 'epo_descripcion' => 'Obtuvo el máximo puntaje y la plaza', 'epo_orden' => 5],
            ['epo_codigo' => 'ACCESITARIO', 'epo_nombre' => 'Accesitario', 'epo_descripcion' => 'Queda en lista de reserva en orden de mérito', 'epo_orden' => 6],
            ['epo_codigo' => 'PRESENTADO', 'epo_nombre' => 'Postulación completada', 'epo_descripcion' => 'Postulación completada por el usuario', 'epo_orden' => 1],
            ['epo_codigo' => 'DESCALIFICADO', 'epo_nombre' => 'Descalificado', 'epo_descripcion' => 'Incurrió en alguna causal de eliminación o inasistencia', 'epo_orden' => 7],
        ]);

        // 11. Estados de Expediente Digital
        $this->db->table('selec_estados_expediente')->insertBatch([
            ['eex_codigo' => 'EN_REVISION', 'eex_nombre' => 'En Revisión por Comisión'],
            ['eex_codigo' => 'VALIDADO', 'eex_nombre' => 'Expediente Conformado y Validado'],
            ['eex_codigo' => 'OBSERVADO', 'eex_nombre' => 'Documentación Incompleta / Observado'],
            ['eex_codigo' => 'RECHAZADO', 'eex_nombre' => 'Expediente Rechazado'],
        ]);

        // 12. Tipos de Archivo Permitidos
        $this->db->table('selec_tipos_archivo')->insertBatch([
            ['tar_codigo' => 'PDF', 'tar_nombre' => 'Documento PDF', 'tar_extension' => 'pdf', 'tar_mime' => 'application/pdf', 'tar_estado' => 'ACTIVO'],
            ['tar_codigo' => 'JPG', 'tar_nombre' => 'Imagen JPEG', 'tar_extension' => 'jpg', 'tar_mime' => 'image/jpeg', 'tar_estado' => 'ACTIVO'],
            ['tar_codigo' => 'PNG', 'tar_nombre' => 'Imagen PNG', 'tar_extension' => 'png', 'tar_mime' => 'image/png', 'tar_estado' => 'ACTIVO'],
            ['tar_codigo' => 'ZIP', 'tar_nombre' => 'Archivo Comprimido ZIP', 'tar_extension' => 'zip', 'tar_mime' => 'application/zip', 'tar_estado' => 'ACTIVO'],
        ]);
    }
}
