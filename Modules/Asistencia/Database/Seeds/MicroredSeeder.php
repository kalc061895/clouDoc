<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MicroredSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // RED 1: EJERCITO PERUANO
            ['mic_red_ide' => 100, 'mic_codigo' => 'MR-01-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'PUNO'],

            // RED 2: AZANGARO
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-ALIA', 'mic_nombre' => 'ALIANZA', 'mic_ubicacion' => 'AZANGARO'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-ARAP', 'mic_nombre' => 'ARAPA', 'mic_ubicacion' => 'ARAPA'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-ASIL', 'mic_nombre' => 'ASILLO', 'mic_ubicacion' => 'ASILLO'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-CHUP', 'mic_nombre' => 'CHUPA', 'mic_ubicacion' => 'CHUPA'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-JDCH', 'mic_nombre' => 'JOSE DOMINGO CHOQUEHUANCA', 'mic_ubicacion' => 'JOSE DOMINGO CHOQUEHUANCA'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-MUÑA', 'mic_nombre' => 'MUÑANI', 'mic_ubicacion' => 'MUÑANI'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'AZANGARO'],
            ['mic_red_ide' => 2, 'mic_codigo' => 'MR-02-SANT', 'mic_nombre' => 'SAN ANTON', 'mic_ubicacion' => 'SAN ANTON'],

            // RED 3: CHUCUITO
            ['mic_red_ide' => 3, 'mic_codigo' => 'MR-03-DESA', 'mic_nombre' => 'DESAGUADERO', 'mic_ubicacion' => 'DESAGUADERO'],
            ['mic_red_ide' => 3, 'mic_codigo' => 'MR-03-MOLI', 'mic_nombre' => 'MOLINO', 'mic_ubicacion' => 'MOLINO'],
            ['mic_red_ide' => 3, 'mic_codigo' => 'MR-03-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'JULI'],
            ['mic_red_ide' => 3, 'mic_codigo' => 'MR-03-POMA', 'mic_nombre' => 'POMATA', 'mic_ubicacion' => 'POMATA'],
            ['mic_red_ide' => 3, 'mic_codigo' => 'MR-03-ZEPI', 'mic_nombre' => 'ZEPITA', 'mic_ubicacion' => 'ZEPITA'],

            // RED 4: EL COLLAO
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-CAMI', 'mic_nombre' => 'CAMICACHI', 'mic_ubicacion' => 'ILAVE'],
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-CHEC', 'mic_nombre' => 'CHECCA', 'mic_ubicacion' => 'ILAVE'],
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-MAZO', 'mic_nombre' => 'MAZO CRUZ', 'mic_ubicacion' => 'SANTA ROSA'],
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-MULL', 'mic_nombre' => 'MULLACONTIHUECO', 'mic_ubicacion' => 'ILAVE'],
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'ILAVE'],
            ['mic_red_ide' => 4, 'mic_codigo' => 'MR-04-PILC', 'mic_nombre' => 'PILCUYO', 'mic_ubicacion' => 'PILCUYO'],

            // RED 5: HUANCANE
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-COJA', 'mic_nombre' => 'COJATA', 'mic_ubicacion' => 'COJATA'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-CONI', 'mic_nombre' => 'CONIMA', 'mic_ubicacion' => 'CONIMA'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-HUAN', 'mic_nombre' => 'HUANCANE', 'mic_ubicacion' => 'HUANCANE'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-MOHO', 'mic_nombre' => 'MOHO', 'mic_ubicacion' => 'MOHO'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'HUANCANE'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-ROSA', 'mic_nombre' => 'ROSASPATA', 'mic_ubicacion' => 'ROSASPATA'],
            ['mic_red_ide' => 5, 'mic_codigo' => 'MR-05-VILQ', 'mic_nombre' => 'VILQUECHICO', 'mic_ubicacion' => 'VILQUECHICO'],

            // RED 6: LAMPA
            ['mic_red_ide' => 6, 'mic_codigo' => 'MR-06-CABA', 'mic_nombre' => 'CABANILLA', 'mic_ubicacion' => 'CABANILLA'],
            ['mic_red_ide' => 6, 'mic_codigo' => 'MR-06-LAMP', 'mic_nombre' => 'LAMPA', 'mic_ubicacion' => 'LAMPA'],
            ['mic_red_ide' => 6, 'mic_codigo' => 'MR-06-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'LAMPA'],
            ['mic_red_ide' => 6, 'mic_codigo' => 'MR-06-PALC', 'mic_nombre' => 'PALCA', 'mic_ubicacion' => 'PALCA'],
            ['mic_red_ide' => 6, 'mic_codigo' => 'MR-06-SLUC', 'mic_nombre' => 'SANTA LUCIA', 'mic_ubicacion' => 'SANTA LUCIA'],

            // RED 7: MACUSANI
            ['mic_red_ide' => 7, 'mic_codigo' => 'MR-07-AYAP', 'mic_nombre' => 'AYAPATA', 'mic_ubicacion' => 'AYAPATA'],
            ['mic_red_ide' => 7, 'mic_codigo' => 'MR-07-ISIV', 'mic_nombre' => 'ISIVILLA', 'mic_ubicacion' => 'ISIVILLA'],
            ['mic_red_ide' => 7, 'mic_codigo' => 'MR-07-MACU', 'mic_nombre' => 'MACUSANI', 'mic_ubicacion' => 'MACUSANI'],
            ['mic_red_ide' => 7, 'mic_codigo' => 'MR-07-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'MACUSANI'],
            ['mic_red_ide' => 7, 'mic_codigo' => 'MR-07-SGAB', 'mic_nombre' => 'SAN GABAN', 'mic_ubicacion' => 'SAN GABAN'],

            // RED 8: MELGAR
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-AYAV', 'mic_nombre' => 'AYAVIRI', 'mic_ubicacion' => 'AYAVIRI'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-COAZ', 'mic_nombre' => 'COAZA', 'mic_ubicacion' => 'COAZA'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-CRUC', 'mic_nombre' => 'CRUCERO', 'mic_ubicacion' => 'CRUCERO'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-LLAL', 'mic_nombre' => 'LLALLI', 'mic_ubicacion' => 'LLALLI'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'AYAVIRI'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-NUÑO', 'mic_nombre' => 'NUÑOA', 'mic_ubicacion' => 'NUÑOA'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-ORUR', 'mic_nombre' => 'ORURILLO', 'mic_ubicacion' => 'ORURILLO'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-PHAR', 'mic_nombre' => 'PHARA', 'mic_ubicacion' => 'PHARA'],
            ['mic_red_ide' => 8, 'mic_codigo' => 'MR-08-SROS', 'mic_nombre' => 'SANTA ROSA', 'mic_ubicacion' => 'SANTA ROSA'],

            // RED 9: PUNO
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-ACOR', 'mic_nombre' => 'ACORA', 'mic_ubicacion' => 'ACORA'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-CAPA', 'mic_nombre' => 'CAPACHICA', 'mic_ubicacion' => 'CAPACHICA'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-JAE', 'mic_nombre' => 'JOSE ANTONIO ENCINAS', 'mic_ubicacion' => 'PUNO'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-LARA', 'mic_nombre' => 'LARAQUERI', 'mic_ubicacion' => 'LARAQUERI'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-MAÑA', 'mic_nombre' => 'MAÑAZO', 'mic_ubicacion' => 'MAÑAZO'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-METR', 'mic_nombre' => 'METROPOLITANO', 'mic_ubicacion' => 'PUNO'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'PUNO'],
            ['mic_red_ide' => 9, 'mic_codigo' => 'MR-09-SBOL', 'mic_nombre' => 'SIMON BOLIVAR', 'mic_ubicacion' => 'PUNO'],

            // RED 10: SAN ANTONIO DE PUTINA
            ['mic_red_ide' => 10, 'mic_codigo' => 'MR-10-ANAN', 'mic_nombre' => 'ANANEA', 'mic_ubicacion' => 'ANANEA'],
            ['mic_red_ide' => 10, 'mic_codigo' => 'MR-10-PUTI', 'mic_nombre' => 'PUTINA', 'mic_ubicacion' => 'PUTINA'],

            // RED 11: SAN ROMAN
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-CABA', 'mic_nombre' => 'CABANILLAS', 'mic_ubicacion' => 'CABANILLAS'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-CSUR', 'mic_nombre' => 'CONO SUR', 'mic_ubicacion' => 'JULIACA'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-JULI', 'mic_nombre' => 'JULIACA', 'mic_ubicacion' => 'JULIACA'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'JULIACA'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-SAMA', 'mic_nombre' => 'SAMAN', 'mic_ubicacion' => 'SAMAN'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-SADR', 'mic_nombre' => 'SANTA ADRIANA', 'mic_ubicacion' => 'JULIACA'],
            ['mic_red_ide' => 11, 'mic_codigo' => 'MR-11-TARA', 'mic_nombre' => 'TARACO', 'mic_ubicacion' => 'TARACO'],

            // RED 12: SANDIA
            ['mic_red_ide' => 12, 'mic_codigo' => 'MR-12-CCUY', 'mic_nombre' => 'CUYO CUYO', 'mic_ubicacion' => 'CUYO CUYO'],
            ['mic_red_ide' => 12, 'mic_codigo' => 'MR-12-MASI', 'mic_nombre' => 'MASIAPO', 'mic_ubicacion' => 'MASIAPO'],
            ['mic_red_ide' => 12, 'mic_codigo' => 'MR-12-PPUN', 'mic_nombre' => 'PUTINA PUNCO', 'mic_ubicacion' => 'PUTINA PUNCO'],
            ['mic_red_ide' => 12, 'mic_codigo' => 'MR-12-SJOR', 'mic_nombre' => 'SAN JUAN DEL ORO', 'mic_ubicacion' => 'SAN JUAN DEL ORO'],
            ['mic_red_ide' => 12, 'mic_codigo' => 'MR-12-SAND', 'mic_nombre' => 'SANDIA', 'mic_ubicacion' => 'SANDIA'],

            // RED 13: YUNGUYO
            ['mic_red_ide' => 13, 'mic_codigo' => 'MR-13-ACHU', 'mic_nombre' => 'AYCHUYO', 'mic_ubicacion' => 'YUNGUYO'],
            ['mic_red_ide' => 13, 'mic_codigo' => 'MR-13-COPA', 'mic_nombre' => 'COPANI', 'mic_ubicacion' => 'COPANI'],
            ['mic_red_ide' => 13, 'mic_codigo' => 'MR-13-NONE', 'mic_nombre' => 'NO PERTENECE A NINGUNA MICRORED', 'mic_ubicacion' => 'YUNGUYO'],
            ['mic_red_ide' => 13, 'mic_codigo' => 'MR-13-OLLA', 'mic_nombre' => 'OLLARAYA', 'mic_ubicacion' => 'OLLARAYA'],
            ['mic_red_ide' => 13, 'mic_codigo' => 'MR-13-YUNG', 'mic_nombre' => 'YUNGUYO', 'mic_ubicacion' => 'YUNGUYO'],
        ];

        $this->db->table('casis_microred')->insertBatch($data);
    }
}
