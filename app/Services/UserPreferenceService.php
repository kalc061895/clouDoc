<?php

namespace App\Services;

class UserPreferenceService
{
    public const DEFAULTS = [
        'Layout' => 'vertical', 'SidebarType' => 'full', 'BoxedLayout' => true,
        'Direction' => 'ltr', 'Theme' => 'light', 'ColorTheme' => 'Blue_Theme', 'cardBorder' => false,
    ];
    private const VALUES = [
        'Layout' => ['vertical', 'horizontal'], 'SidebarType' => ['full', 'mini-sidebar'],
        'BoxedLayout' => [true, false], 'Direction' => ['ltr', 'rtl'], 'Theme' => ['light', 'dark'],
        'ColorTheme' => ['Blue_Theme', 'Aqua_Theme', 'Purple_Theme', 'Green_Theme', 'Cyan_Theme', 'Orange_Theme'],
        'cardBorder' => [true, false],
    ];
    private $db;

    public function __construct($db = null) { $this->db = $db ?? db_connect(); }

    public static function validar(array $input): array
    {
        if (array_diff(array_keys($input), array_keys(self::DEFAULTS)) || count($input) !== count(self::DEFAULTS)) {
            throw new \InvalidArgumentException('La configuración debe contener únicamente las opciones de apariencia.');
        }
        foreach (self::VALUES as $key => $values) {
            if (!array_key_exists($key, $input) || !in_array($input[$key], $values, true)) {
                throw new \InvalidArgumentException('Valor de configuración inválido: ' . $key);
            }
        }
        return array_replace(self::DEFAULTS, $input);
    }

    public function obtener(int $userId): array
    {
        if ($userId < 1) throw new \InvalidArgumentException('Se requiere una sesión activa.');
        $row = $this->db->table('user_preferences')->select('settings_json')->where('user_id', $userId)->get()->getRowArray();
        $stored = $row ? json_decode($row['settings_json'], true) : [];
        $settings = self::DEFAULTS;
        if (is_array($stored)) foreach (self::VALUES as $key => $values) {
            if (isset($stored[$key]) && in_array($stored[$key], $values, true)) $settings[$key] = $stored[$key];
        }
        return $settings;
    }

    public function guardar(int $userId, array $input): array
    {
        if ($userId < 1) throw new \InvalidArgumentException('Se requiere una sesión activa.');
        $settings = self::validar($input);
        // La PK evita duplicados incluso cuando dos pestañas guardan por primera vez.
        $ok = $this->db->table('user_preferences')->upsert([
            'user_id' => $userId, 'settings_json' => json_encode($settings, JSON_THROW_ON_ERROR),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$ok) throw new \RuntimeException('No se pudo guardar la configuración.');
        return $settings;
    }
}
