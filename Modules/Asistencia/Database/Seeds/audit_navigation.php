<?php

// Revisión estática, sin conexión ni escrituras en la base de datos.
namespace CodeIgniter\Database {
    class Seeder {}
}

namespace {
    require __DIR__ . '/MenuSeeder.php';
    $root = dirname(__DIR__, 4);
    $routes = new class {
        public array $prefix = [];
        public array $entries = [];
        public function group($prefix, ...$args): void
        {
            $this->prefix[] = $prefix;
            $args[count($args) - 1]($this);
            array_pop($this->prefix);
        }
        public function __call($verb, $args): void
        {
            $this->entries[] = [strtoupper($verb), trim(implode('/', $this->prefix) . '/' . $args[0], '/'), $args[1]];
        }
    };
    require $root . '/Modules/Asistencia/Config/Routes.php';
    $menus = \Modules\Asistencia\Database\Seeds\MenuSeeder::menus();
    $urls = array_column(array_filter($menus, fn ($m) => $m['url'] !== null), null, 'url');
    $implemented = [];
    $pending = [];
    $aliases = [];
    $issues = [];
    $targets = [];
    $seen = [];
    foreach ($routes->entries as [$verb, $url, $target]) {
        [$controller, $method] = explode('::', explode('/', $target)[0]);
        $file = $root . '/Modules/Asistencia/Controllers/' . $controller . '.php';
        $source = is_file($file) ? file_get_contents($file) : '';
        $exists = preg_match('/public\s+function\s+' . preg_quote($method, '/') . '\s*\(/i', $source);
        if (! $exists) {
            $issues[] = [$verb, $url, $target, $source === '' ? 'Falta controlador' : 'Falta método'];
        }
        $key = $verb . ' ' . $url;
        if (isset($seen[$key])) {
            echo 'Ruta duplicada: ' . $key . PHP_EOL;
        }
        $seen[$key] = true;
        if ($verb !== 'GET' || str_contains($url, '/api/') || str_contains($url, '/select/') || str_contains($url, '(:')) {
            continue;
        }
        if (isset($urls[$url])) {
            if (! $exists) {
                throw new \RuntimeException('Menú sin método: ' . $url);
            }
            // Comprobar la vista literal retornada por el método de entrada.
            preg_match('/public\s+function\s+' . preg_quote($method, '/') . '\s*\([^)]*\)[^{]*\{(.*?)(?=\n    (?:public|private|protected) function|\z)/s', $source, $body);
            if (! preg_match('/return view\(\x27([^\x27]+)\x27/', $body[1] ?? '', $view)) {
                throw new \RuntimeException('Revisar vista de ' . $url);
            }
            $viewPath = str_replace('\\', '/', $view[1]);
            $viewPath .= str_ends_with($viewPath, '.php') ? '' : '.php';
            if (! is_file($root . '/' . $viewPath)) {
                throw new \RuntimeException('Falta vista: ' . $viewPath);
            }
            $implemented[$url] = [$urls[$url]['id'], $urls[$url]['name'], $url, $target];
            $targets[$target] = $url;
        } elseif (! $exists) {
            $pending[$url] = [$url, $target];
        } else {
            $aliases[$url] = [$url, $target];
        }
    }
    if (count($implemented) !== count($urls)) {
        throw new \RuntimeException('Hay menús sin ruta GET.');
    }
    $report = "# Navegación de Asistencia\n\nRevisión estática de Routes.php, métodos públicos y vistas de entrada. No certifica el funcionamiento de los flujos ni la base de datos.\n\n";
    $report .= "## Ejecución\n\n```powershell\nphp spark db:seed 'Modules\\Asistencia\\Database\\Seeds\\NavigationSeeder'\n```\n\nCrea el grupo `asistencia` (1000), 3 agrupadores y 31 enlaces, y vincula las 34 entradas al grupo 1000. MasterSeeder también llama a NavigationSeeder. Las relaciones usan su ID autoincremental; los IDs fijos corresponden al grupo y los menús. No asigna usuarios al grupo. No configura permisos por DIRESA/red/microred ni autorización de endpoints.\n\nSe puede repetir sin duplicar los IDs ni las relaciones. Ante IDs ocupados por otras opciones, falla. Si el grupo asistencia ya tiene otro ID, requiere migrar previamente sus usuarios. No elimina los menús antiguos ni sus relaciones: si ya ejecutó el ejemplo, requieren una migración separada.\n\n";
    $table = static function ($headers, $rows): string {
        $out = '| ' . implode(' | ', $headers) . " |\n| " . implode(' | ', array_fill(0, count($headers), '---')) . " |\n";
        foreach ($rows as $row) {
            $out .= '| ' . implode(' | ', $row) . " |\n";
        }
        return $out . "\n";
    };
    $report .= "## Pantallas incluidas\n\nFirmar roles muestra un aviso de función pendiente; todavía no firma documentos.\n\n" . $table(['ID', 'Menú', 'Ruta', 'Destino'], $implemented);
    $report .= "## Pantallas pendientes (no se insertan menús rotos)\n\n" . $table(['Ruta', 'Destino faltante'], $pending);
    $report .= "## Alias y descargas sin menú adicional\n\n" . $table(['Ruta', 'Destino'], $aliases);
    $report .= "## Todos los endpoints con controlador o método ausente\n\n" . $table(['Verbo', 'Ruta', 'Destino', 'Problema'], $issues);
    $report .= "## Hallazgos del ejemplo anterior\n\n- Namespace incorrecto en GroupUserSeeder y grupos 100–107; esta versión crea únicamente el grupo solicitado 1000.\n- MenuGroupUserSeeder vinculaba IDs 600–663 al grupo 1. Ahora utiliza el catálogo explícito del módulo.\n- Enlaces personal/listado_personal*, marcar/prueba2, administrador/periferie y gestordb/servicio no tienen ruta.\n- Horarios usa gestordb/turnohorario; gestordb/turno_horario apunta al método inexistente horarios.\n- Había catálogos duplicados, agrupadores vacíos y un padre MAPA DE ESTABLECIMIENTOS inexistente.\n- Se omiten alias de programación y marcaciones para no repetir la misma pantalla. Los paneles por trabajador se acceden desde el gestor de personal.\n- POST administrador/asistencia/rectificar referencia \$1 sin capturarlo en la URL. Revisar antes de usarlo.\n- Routes.php repite las rutas GET gestordb/licencia, gestordb/permiso y gestordb/turno.\n\nRegenerar y verificar: `php Modules/Asistencia/Database/Seeds/audit_navigation.php`.\n";
    file_put_contents($root . '/Modules/Asistencia/Database/Seeds/NAVEGACION.md', $report);
    echo count($implemented) . ' enlaces verificados; ' . count($pending) . ' pantallas pendientes; ' . count($issues) . ' endpoints incompletos.' . PHP_EOL;
}
