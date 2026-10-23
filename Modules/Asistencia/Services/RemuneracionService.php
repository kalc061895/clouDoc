<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\Database\BaseConnection;
use Modules\Asistencia\Models\RemuneracionModel;

class RemuneracionService
{
    public const CAMPOS = [
        'remuneracion' => 'Remuneración mensual',
        'guardias.diurna' => 'Guardia diurna',
        'guardias.nocturna' => 'Guardia nocturna',
        'guardias.diurna_domingo' => 'Guardia diurna en domingo',
        'guardias.nocturna_domingo' => 'Guardia nocturna en domingo',
        'guardias.diurna_feriado' => 'Guardia diurna en feriado',
        'guardias.nocturna_feriado' => 'Guardia nocturna en feriado',
        'reten.diurno' => 'Retén diurno',
        'reten.nocturno' => 'Retén nocturno',
        'reten.diurno_feriado' => 'Retén diurno en feriado',
        'reten.nocturno_feriado' => 'Retén nocturno en feriado',
        'descuentos.base_calculo' => 'Base de cálculo de descuentos',
        'descuentos.dias_divisor' => 'Días divisor',
        'descuentos.horas_jornada' => 'Horas por jornada',
    ];

    private BaseConnection $db;
    private RemuneracionModel $model;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
        $this->model = new RemuneracionModel($this->db);
    }

    public function listar(): array
    {
        return $this->model->select('casis_remuneraciones.*, c.car_nombre')
            ->join('casis_cargo c', 'c.car_ide = rem_car_ide')
            ->orderBy('c.car_nombre')->orderBy('rem_nivel')->findAll();
    }

    public function obtener(int $id): array
    {
        $row = $this->model->find($id);
        if (!$row) throw new \DomainException('La remuneración ya no existe.', 404);
        return $row;
    }

    public function guardar(array $datos, int $usuario, ?int $id = null): void
    {
        $actual = $id === null ? null : $this->obtener($id);
        $cargo = filter_var($datos['rem_car_ide'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $nivel = is_string($datos['rem_nivel'] ?? null) ? trim($datos['rem_nivel']) : '';
        if (!$cargo || !$this->db->table('casis_cargo')->where('car_ide', $cargo)->where('deleted_at', null)->countAllResults()) {
            throw new \DomainException('Seleccione un cargo válido.');
        }
        if ($nivel === '' || mb_strlen($nivel) > 50) throw new \DomainException('El nivel es obligatorio y admite hasta 50 caracteres.');
        $estado = $datos['rem_estado'] ?? 1;
        if (!in_array($estado, [0, 1, '0', '1'], true)) throw new \DomainException('Estado no válido.');

        $duplicado = $this->db->table('casis_remuneraciones')->where('rem_car_ide', $cargo)->where('rem_nivel', $nivel);
        if ($id !== null) $duplicado->where('rem_ide !=', $id);
        if ($duplicado->countAllResults()) throw new \DomainException('Ya existe una remuneración para ese cargo y nivel, incluso si está inactiva.', 409);

        $config = self::configuracion($datos, $actual['rem_configuracion'] ?? []);
        $row = ['rem_car_ide' => $cargo, 'rem_nivel' => $nivel, 'rem_estado' => (int)$estado, 'rem_configuracion' => $config];
        $row[$id === null ? 'created_by' : 'updated_by'] = $usuario;
        try {
            $ok = $id === null ? $this->model->insert($row) : $this->model->update($id, $row);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // La clave única también protege frente a altas simultáneas.
            if ((int)$e->getCode() === 1062 || str_contains($e->getMessage(), 'Duplicate entry')) {
                throw new \DomainException('Ya existe una remuneración para ese cargo y nivel.', 409);
            }
            throw $e;
        }
        if ($ok === false) throw new \DomainException(implode(' ', $this->model->errors()) ?: 'No se pudo guardar la remuneración.');
    }

    public static function configuracion(array $datos, array $config = []): array
    {
        $config['moneda'] = $config['moneda'] ?? 'PEN';
        foreach (self::CAMPOS as $ruta => $label) {
            $key = str_replace('.', '_', $ruta);
            if (!array_key_exists($key, $datos)) throw new \DomainException('Falta el campo: ' . $label . '.');
            $value = $datos[$key];
            if ($value === '' || $value === null) {
                if ($ruta === 'remuneracion') throw new \DomainException('La remuneración mensual es obligatoria.');
                $value = null;
            } else {
                if (!is_string($value) || !preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D', $value)) {
                    throw new \DomainException($label . ': ingrese un valor no negativo con hasta dos decimales.');
                }
                [$entero, $decimal] = array_pad(explode('.', $value), 2, '');
                $value = (ltrim($entero, '0') ?: '0') . '.' . str_pad($decimal, 2, '0');
                if (in_array($ruta, ['descuentos.dias_divisor', 'descuentos.horas_jornada'], true) && $value === '0.00') {
                    throw new \DomainException($label . ' debe ser mayor que cero.');
                }
            }
            $partes = explode('.', $ruta);
            if (count($partes) === 1) $config[$ruta] = $value;
            else $config[$partes[0]][$partes[1]] = $value;
        }
        return $config;
    }

    public function eliminar(int $id): void
    {
        $this->obtener($id);
        if (!$this->model->delete($id)) throw new \DomainException('No se pudo eliminar la remuneración.');
    }
}
