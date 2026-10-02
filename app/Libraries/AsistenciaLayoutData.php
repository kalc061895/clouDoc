<?php

namespace App\Libraries;

/** Datos de presentación de la sesión; no modifica permisos ni el usuario. */
class AsistenciaLayoutData
{
    public static function perfil(?object $user): array
    {
        $nombre = trim(implode(' ', array_filter([
            trim((string) ($user->nombres ?? '')),
            trim((string) ($user->paterno ?? '')),
            trim((string) ($user->materno ?? '')),
        ], static fn($v) => $v !== '')));
        $nombre = $nombre ?: (trim((string) ($user->username ?? '')) ?: 'Usuario');
        $parts = preg_split('/\s+/u', $nombre);
        $iniciales = mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr($parts[count($parts) - 1], 0, 1) : ''));
        $foto = trim((string) ($user->photo ?? ''));
        // Las fotos de cuenta existentes se guardan como nombre de archivo en este directorio.
        $fotoValida = $foto !== '' && basename($foto) === $foto && !str_contains($foto, '\\')
            && preg_match('/\.(?:png|jpe?g|webp|gif)$/i', $foto)
            && is_file(FCPATH . 'assets/images/profile/' . $foto);
        return [
            'nombre' => $nombre,
            'cargo' => trim((string) ($user->cargo ?? '')) ?: 'Cargo no registrado',
            'username' => trim((string) ($user->username ?? '')),
            'email' => trim((string) ($user->email ?? '')),
            'iniciales' => $iniciales,
            'foto' => $fotoValida ? base_url('assets/images/profile/' . rawurlencode($foto)) : null,
        ];
    }

    public static function opciones(array $tree, array $asignados): array
    {
        $allowed = [];
        foreach ($asignados as $item) {
            if (($item['status'] ?? '') === 'active') $allowed[(string) $item['id']] = true;
        }
        $result = [];
        $walk = function (array $nodes, array $parents = []) use (&$walk, &$result, $allowed): void {
            foreach ($nodes as $item) {
                if (($item['status'] ?? '') !== 'active') continue;
                $nombre = trim((string) ($item['name'] ?? ''));
                $children = $item['children'] ?? [];
                if ($children) { $walk($children, [...$parents, $nombre]); continue; }
                if (!isset($allowed[(string) $item['id']])) continue;
                $path = trim((string) ($item['url'] ?? ''));
                // Sólo destinos locales del menú, nunca encabezados, acciones JS o URLs externas.
                if ($nombre === '' || $path === '' || $path[0] === '#' || preg_match('/^[a-z][a-z0-9+.-]*:/i', $path)
                    || str_starts_with($path, '//') || str_contains($path, '\\') || preg_match('/[\x00-\x20]/', $path)
                    || preg_match('~(?:^|/)\.\.(?:/|$)~', $path)) continue;
                $url = base_url(ltrim($path, '/'));
                $result[$url] ??= ['nombre' => $nombre, 'categoria' => implode(' / ', $parents), 'url' => $url];
            }
        };
        $walk($tree);
        return array_values($result);
    }
}
