<?php
$trazos = [
    'documento'=>'M7 3h8l4 4v14H7z M14 3v5h5 M10 12h6 M10 16h6',
    'personal'=>'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M15 4a4 4 0 0 1 0 8 M22 21v-2a4 4 0 0 0-3-3.87 M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8',
    'seleccion'=>'M8 5H5v16h14V5h-3 M9 3h6v4H9z M8 13l2 2 5-5 M8 18h8',
    'calendario'=>'M4 5h16v16H4z M8 3v4 M16 3v4 M4 10h16 M8 14h2 M14 14h2 M8 18h2',
    'planilla'=>'M5 3h14v18l-3-2-4 2-4-2-3 2z M9 7h6 M9 11h6 M9 15h2',
    'video'=>'M4 4h16v16H4z M10 8l6 4-6 4z',
    'flecha'=>'M5 12h14 M13 6l6 6-6 6',
    'acceso'=>'M14 4h6v16h-6 M3 12h12 M10 7l5 5-5 5',
    'libro'=>'M12 5v16 M3 3l9 2 9-2v16l-9 2-9-2z',
];
?>
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="<?= $trazos[$icono ?? 'documento'] ?? $trazos['documento'] ?>"/></svg>
