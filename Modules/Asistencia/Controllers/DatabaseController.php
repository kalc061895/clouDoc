<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Asistencia\Models\EstablecimientoModel;
use Modules\Asistencia\Services\DiresaService;
use Modules\Asistencia\Services\RedService;
use Modules\Asistencia\Services\MicroredService;
use Modules\Asistencia\Services\EstablecimientoService;
use Modules\Asistencia\Services\TipoOficinaService;
use Modules\Asistencia\Services\OficinaService;
use Modules\Asistencia\Services\CargoService;
use Modules\Asistencia\Services\ProfesionService;
use Modules\Asistencia\Services\ColegiaturaService;
use Modules\Asistencia\Services\DiaFeriadoService;
use Modules\Asistencia\Services\ModalidadContratoService;
use Modules\Asistencia\Services\LicenciaService;
use Modules\Asistencia\Services\PermisoService;


class DatabaseController extends BaseController
{
    use ResponseTrait;

    protected $oficinaService;
    protected $diresaService;

    protected $redService;

    protected $microredService;
    protected $establecimientoService;

    public function __construct()
    {
        $this->oficinaService = new OficinaService();
        $this->diresaService = new DiresaService();
        $this->redService = new RedService();
        $this->microredService = new MicroredService();
    }

    /**
     * Renderiza la vista principal del Gestor de Base de Datos
     * GET /gestordb/listar_tablas
     */

    /**
     * 1. Vista Principal de Diresa
     * GET /gestordb/diresa
     */
    public function diresas()
    {
        return view('Modules\Asistencia\Views\database\diresas_view');
    }

    /**
     * 2. API: Listar Direcciones de Salud
     * GET /gestordb/api/diresas
     */
    public function apiListarDiresas()
    {
        $search = $this->request->getVar('search');
        $data = $this->diresaService->listarDiresas($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * 3. API: Crear Dirección de Salud
     * POST /gestordb/api/diresas
     */
    public function apiCrearDiresa()
    {
        $datos = [
            'dir_nombre'    => $this->request->getVar('dir_nombre'),
            'dir_director'  => $this->request->getVar('dir_director'),
            'dir_ubicacion' => $this->request->getVar('dir_ubicacion'),
            'dir_telefono'  => $this->request->getVar('dir_telefono'),
            'dir_email'     => $this->request->getVar('dir_email'),
        ];

        $resultado = $this->diresaService->crearDiresa($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Dirección de Salud registrada exitosamente.'
        ]);
    }

    /**
     * 4. API: Actualizar Dirección de Salud
     * PUT /gestordb/api/diresas/(:num)
     */
    public function apiActualizarDiresa($id = null)
    {
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'dir_nombre'    => $rawDatos['dir_nombre'] ?? null,
            'dir_director'  => $rawDatos['dir_director'] ?? null,
            'dir_ubicacion' => $rawDatos['dir_ubicacion'] ?? null,
            'dir_telefono'  => $rawDatos['dir_telefono'] ?? null,
            'dir_email'     => $rawDatos['dir_email'] ?? null,
        ];

        $resultado = $this->diresaService->actualizarDiresa((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => 'Dirección de Salud actualizada correctamente.'
        ]);
    }

    /**
     * 5. API: Eliminar Dirección de Salud
     * DELETE /gestordb/api/diresas/(:num)
     */
    public function apiEliminarDiresa($id = null)
    {
        if ($this->diresaService->eliminarDiresa((int)$id)) {
            return $this->respondDeleted([
                'status'  => 'success',
                'message' => 'Dirección de Salud dada de baja correctamente.'
            ]);
        }

        return $this->fail('No se pudo dar de baja la Dirección de Salud.', 400);
    }


    /**
     * 1. Vista Principal de Redes
     * GET /gestordb/red
     */
    public function redes()
    {
        return view('Modules\Asistencia\Views\database\redes_view');
    }

    /**
     * 2. API: Listar Redes
     * GET /gestordb/api/redes
     */
    public function apiListarRedes()
    {
        $search = $this->request->getVar('search');
        $data = $this->redService->listarRedes($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * 3. API: Crear Red de Salud
     * POST /gestordb/api/redes
     */
    public function apiCrearRed()
    {
        $datos = [
            'red_dir_ide'   => $this->request->getVar('red_dir_ide'),
            'red_codigo'    => $this->request->getVar('red_codigo'),
            'red_nombre'    => $this->request->getVar('red_nombre'),
            'red_director'  => $this->request->getVar('red_director'),
            'red_ubicacion' => $this->request->getVar('red_ubicacion'),
            'red_telefono'  => $this->request->getVar('red_telefono'),
            'red_email'     => $this->request->getVar('red_email'),
        ];

        $resultado = $this->redService->crearRed($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Red de Salud registrada exitosamente.'
        ]);
    }

    /**
     * 4. API: Actualizar Red de Salud
     * PUT /gestordb/api/redes/(:num)
     */
    public function apiActualizarRed($id = null)
    {
        $rawDatos = $this->request->getRawInput();
        $datos = [
            'red_dir_ide'   => $rawDatos['red_dir_ide'] ?? null,
            'red_codigo'    => $rawDatos['red_codigo'] ?? null,
            'red_nombre'    => $rawDatos['red_nombre'] ?? null,
            'red_director'  => $rawDatos['red_director'] ?? null,
            'red_ubicacion' => $rawDatos['red_ubicacion'] ?? null,
            'red_telefono'  => $rawDatos['red_telefono'] ?? null,
            'red_email'     => $rawDatos['red_email'] ?? null,
        ];

        $resultado = $this->redService->actualizarRed((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => 'Red de Salud actualizada correctamente.'
        ]);
    }

    /**
     * 5. API: Eliminar Red de Salud
     * DELETE /gestordb/api/redes/(:num)
     */
    public function apiEliminarRed($id = null)
    {
        if ($this->redService->eliminarRed((int)$id)) {
            return $this->respondDeleted([
                'status'  => 'success',
                'message' => 'Red de Salud dada de baja correctamente.'
            ]);
        }

        return $this->fail('No se pudo dar de baja la Red de Salud.', 400);
    }

    /**
     * API de apoyo: Listar Redes simplificado (para futuros selects de Microredes/Establecimientos)
     * GET /gestordb/api/redes-lookup
     */
    public function apiRedesLookup()
    {
        $redes = $this->redService->listarRedes();
        $data = array_map(function ($red) {
            return [
                'id' => $red['red_ide'],
                'nombre' => $red['red_nombre']
            ];
        }, $redes);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * Vista Principal de Microredes
     * GET /gestordb/microred
     */
    public function microredes()
    {
        return view('Modules\Asistencia\Views\database\microredes_view');
    }

    /**
     * API: Listar Microredes
     * GET /gestordb/api/microredes
     */
    public function apiListarMicroredes()
    {
        $search = $this->request->getVar('search');
        $data = $this->microredService->listarMicroredes($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Microred
     * POST /gestordb/api/microredes
     */
    public function apiCrearMicrored()
    {
        $datos = [
            'mic_red_ide'   => $this->request->getVar('mic_red_ide'),
            'mic_codigo'    => $this->request->getVar('mic_codigo'),
            'mic_nombre'    => $this->request->getVar('mic_nombre'),
            'mic_director'  => $this->request->getVar('mic_director'),
            'mic_ubicacion' => $this->request->getVar('mic_ubicacion'),
            'mic_telefono'  => $this->request->getVar('mic_telefono'),
            'mic_email'     => $this->request->getVar('mic_email'),
        ];

        $resultado = $this->microredService->crearMicrored($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Microred registrada con éxito.']);
    }

    /**
     * API: Actualizar Microred
     * PUT /gestordb/api/microredes/[:num]
     */
    public function apiActualizarMicrored($id = null)
    {
        $rawDatos = $this->request->getRawInput();
        $datos = [
            'mic_red_ide'   => $rawDatos['mic_red_ide'] ?? null,
            'mic_codigo'    => $rawDatos['mic_codigo'] ?? null,
            'mic_nombre'    => $rawDatos['mic_nombre'] ?? null,
            'mic_director'  => $rawDatos['mic_director'] ?? null,
            'mic_ubicacion' => $rawDatos['mic_ubicacion'] ?? null,
            'mic_telefono'  => $rawDatos['mic_telefono'] ?? null,
            'mic_email'     => $rawDatos['mic_email'] ?? null,
        ];

        $resultado = $this->microredService->actualizarMicrored((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Microred actualizada correctamente.']);
    }

    /**
     * API: Eliminar Microred
     * DELETE /gestordb/api/microredes/[:num]
     */
    public function apiEliminarMicrored($id = null)
    {
        if ($this->microredService->eliminarMicrored((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Microred dada de baja.']);
        }
        return $this->fail('No se pudo eliminar la microred.', 400);
    }

    /**
     * API de apoyo (Lookup): Listado simplificado para que la vista de Establecimientos consuma las microredes
     * GET /gestordb/api/microredes-lookup
     */
    public function apiMicroredesLookup()
    {
        $microredes = $this->microredService->listarMicroredes();
        $data = array_map(function ($mic) {
            return [
                'id' => $mic['mic_ide'],
                'nombre' => $mic['mic_nombre']
            ];
        }, $microredes);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * Renderiza la vista principal de Establecimientos
     * GET /asistencia/gestordb/establecimiento
     */
    public function establecimientos()
    {
        return view('Modules\Asistencia\Views\database\establecimientos_view');
    }

    /**
     * API: Listar todos los establecimientos (con join jerárquico)
     * GET /asistencia/gestordb/api/establecimientos
     */
    public function apiListarEstablecimientos()
    {
        $establecimientoService = new EstablecimientoService();
        $busqueda = $this->request->getVar('search');

        $data = $establecimientoService->listarEstablecimientos($busqueda);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear nuevo Establecimiento
     * POST /asistencia/gestordb/api/establecimientos
     */
    public function apiCrearEstablecimiento()
    {
        $establecimientoService = new EstablecimientoService();

        $datos = [
            'est_mic_ide'      => $this->request->getVar('est_mic_ide'),
            'est_codigo'       => $this->request->getVar('est_codigo'),
            'est_ipress'       => $this->request->getVar('est_ipress'),
            'est_tipo'         => $this->request->getVar('est_tipo'),
            'est_denominacion' => $this->request->getVar('est_denominacion'),
            'est_categoria'    => $this->request->getVar('est_categoria'),
            'est_ubigeo'       => $this->request->getVar('est_ubigeo'),
            'est_latitud'      => $this->request->getVar('est_latitud') ?: null,
            'est_longitud'     => $this->request->getVar('est_longitud') ?: null,
            'est_radio'        => $this->request->getVar('est_radio') ?: 50,
        ];

        $resultado = $establecimientoService->crearEstablecimiento($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400); // Errores de validación del Modelo
        }

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Establecimiento registrado correctamente.'
        ]);
    }

    /**
     * API: Actualizar Establecimiento existente
     * PUT /asistencia/gestordb/api/establecimientos/(:num)
     */
    public function apiActualizarEstablecimiento($id = null)
    {
        $establecimientoService = new EstablecimientoService();
        $rawDatos = $this->request->getRawInput();
        $datos = [
            'est_mic_ide'      => $rawDatos['est_mic_ide'] ?? null,
            'est_codigo'       => $rawDatos['est_codigo'] ?? null,
            'est_ipress'       => $rawDatos['est_ipress'] ?? null,
            'est_tipo'         => $rawDatos['est_tipo'] ?? null,
            'est_denominacion' => $rawDatos['est_denominacion'] ?? null,
            'est_categoria'    => $rawDatos['est_categoria'] ?? null,
            'est_ubigeo'       => $rawDatos['est_ubigeo'] ?? null,
            'est_latitud'      => $rawDatos['est_latitud'] ?? null,
            'est_longitud'     => $rawDatos['est_longitud'] ?? null,
            'est_radio'        => $rawDatos['est_radio'] ?? 50,
        ];

        $resultado = $establecimientoService->actualizarEstablecimiento((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => 'Establecimiento actualizado con éxito.'
        ]);
    }

    /**
     * API: Eliminar (Soft Delete) Establecimiento
     * DELETE /asistencia/gestordb/api/establecimientos/(:num)
     */
    public function apiEliminarEstablecimiento($id = null)
    {
        $establecimientoService = new EstablecimientoService();

        if ($establecimientoService->eliminarEstablecimiento((int)$id)) {
            return $this->respondDeleted([
                'status'  => 'success',
                'message' => 'Establecimiento enviado a la papelera.'
            ]);
        }

        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    /**
     * Vista Principal de Tipos de Oficina
     * GET /asistencia/gestordb/tipo-oficina
     */
    public function tiposOficina()
    {
        return view('Modules\Asistencia\Views\database\tipos_oficina_view');
    }

    /**
     * API: Listar
     * GET /asistencia/gestordb/api/tipos-oficina
     */
    public function apiListarTiposOficina()
    {
        $service = new TipoOficinaService();
        $search = $this->request->getVar('search');
        $data = $service->listarTiposOficina($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear
     * POST /asistencia/gestordb/api/tipos-oficina
     */
    public function apiCrearTipoOficina()
    {
        $service = new TipoOficinaService();
        $datos = [
            'tofi_codigo'      => $this->request->getVar('tofi_codigo'),
            'tofi_nombre'      => $this->request->getVar('tofi_nombre'),
            'tofi_descripcion' => $this->request->getVar('tofi_descripcion'),
            'tofi_estado'      => $this->request->getVar('tofi_estado') ?? 1,
        ];

        $resultado = $service->crearTipoOficina($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Tipo de oficina guardado.']);
    }

    /**
     * API: Actualizar
     * PUT /asistencia/gestordb/api/tipos-oficina/(:num)
     */
    public function apiActualizarTipoOficina($id = null)
    {
        $service = new TipoOficinaService();
        // CAPTURA CORRECTA PARA METODOS PUT/PATCH
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'tofi_codigo'      => $rawDatos['tofi_codigo'] ?? null,
            'tofi_nombre'      => $rawDatos['tofi_nombre'] ?? null,
            'tofi_descripcion' => $rawDatos['tofi_descripcion'] ?? null,
            'tofi_estado'      => isset($rawDatos['tofi_estado']) ? (int)$rawDatos['tofi_estado'] : 1,
        ];
        $resultado = $service->actualizarTipoOficina((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Tipo de oficina actualizado.']);
    }

    /**
     * API: Eliminar
     * DELETE /asistencia/gestordb/api/tipos-oficina/(:num)
     */
    public function apiEliminarTipoOficina($id = null)
    {
        $service = new TipoOficinaService();

        if ($service->eliminarTipoOficina((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Tipo de oficina eliminado.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    /**
     * API Lookup: Listar solo activos para combos
     * GET /asistencia/gestordb/api/tipos-oficina-lookup
     */
    public function apiTiposOficinaLookup()
    {
        $service = new TipoOficinaService();
        $activos = $service->obtenerActivos();

        $data = array_map(function ($item) {
            return [
                'id'     => $item['tofi_ide'],
                'nombre' => $item['tofi_nombre']
            ];
        }, $activos);

        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    /**
     * Vista Principal de Oficinas
     * GET /asistencia/gestordb/oficina
     */
    public function oficinas()
    {
        return view('Modules\Asistencia\Views\database\oficinas_view');
    }

    /**
     * API: Listar oficinas
     * GET /asistencia/gestordb/api/oficinas
     */
    public function apiListarOficinas()
    {
        $service = new OficinaService();
        $search = $this->request->getVar('search');
        $data = $service->listarOficinas($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Obtener oficinas de un establecimiento (para llenar el selector de Oficina Padre)
     * GET /asistencia/gestordb/api/oficinas-establecimiento/(:num)
     */
    public function apiOficinasEstablecimiento($estIde = null)
    {
        $service = new OficinaService();
        $data = $service->listarPorEstablecimiento((int)$estIde);
        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear oficina
     * POST /asistencia/gestordb/api/oficinas
     */
    public function apiCrearOficina()
    {
        $service = new OficinaService();
        $datos = [
            'ofi_est_ide'           => $this->request->getVar('ofi_est_ide') ?: null,
            'ofi_tofi_ide'          => $this->request->getVar('ofi_tofi_ide'),
            'ofi_nombre'            => $this->request->getVar('ofi_nombre'),
            'ofi_abreviatura'       => $this->request->getVar('ofi_abreviatura'),
            'ofi_codigo_referencia' => $this->request->getVar('ofi_codigo_referencia'),
            'ofi_titulo_encargado'  => $this->request->getVar('ofi_titulo_encargado'),
            'ofi_nombres_encargado' => $this->request->getVar('ofi_nombres_encargado'),
            'ofi_cargo_encargado'   => $this->request->getVar('ofi_cargo_encargado'),
            'ofi_descripcion'       => $this->request->getVar('ofi_descripcion'),
            'ofi_rango'             => $this->request->getVar('ofi_rango'),
            'ofi_padre_ide'         => $this->request->getVar('ofi_padre_ide') ?: null,
            'ofi_estado'            => $this->request->getVar('ofi_estado') ?? 1,
        ];

        $resultado = $service->crearOficina($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Oficina registrada con éxito.']);
    }

    /**
     * API: Actualizar oficina (Implementación con RawInput para evitar nulos)
     * PUT /asistencia/gestordb/api/oficinas/(:num)
     */
    public function apiActualizarOficina($id = null)
    {
        $service = new OficinaService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'ofi_est_ide'           => $rawDatos['ofi_est_ide'] ?: null,
            'ofi_tofi_ide'          => $rawDatos['ofi_tofi_ide'] ?? null,
            'ofi_nombre'            => $rawDatos['ofi_nombre'] ?? null,
            'ofi_abreviatura'       => $rawDatos['ofi_abreviatura'] ?? null,
            'ofi_codigo_referencia' => $rawDatos['ofi_codigo_referencia'] ?? null,
            'ofi_titulo_encargado'  => $rawDatos['ofi_titulo_encargado'] ?? null,
            'ofi_nombres_encargado' => $rawDatos['ofi_nombres_encargado'] ?? null,
            'ofi_cargo_encargado'   => $rawDatos['ofi_cargo_encargado'] ?? null,
            'ofi_descripcion'       => $rawDatos['ofi_descripcion'] ?? null,
            'ofi_rango'             => $rawDatos['ofi_rango'] ?? null,
            'ofi_padre_ide'         => $rawDatos['ofi_padre_ide'] ?: null,
            'ofi_estado'            => isset($rawDatos['ofi_estado']) ? (int)$rawDatos['ofi_estado'] : 1,
        ];

        $resultado = $service->actualizarOficina((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Oficina actualizada correctamente.']);
    }

    /**
     * API: Eliminar oficina
     * DELETE /asistencia/gestordb/api/oficinas/(:num)
     */
    public function apiEliminarOficina($id = null)
    {
        $service = new OficinaService();

        if ($service->eliminarOficina((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Oficina dada de baja.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }


    /**
     * Vista Principal de Cargos
     * GET /asistencia/gestordb/cargo
     */
    public function cargos()
    {
        return view('Modules\Asistencia\Views\database\cargos_view');
    }

    /**
     * API: Listar cargos
     * GET /asistencia/gestordb/api/cargos
     */
    public function apiListarCargos()
    {
        $service = new CargoService();
        $search = $this->request->getVar('search');
        $data = $service->listarCargos($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Cargo
     * POST /asistencia/gestordb/api/cargos
     */
    public function apiCrearCargo()
    {
        $service = new CargoService();
        $datos = [
            'car_codigo'      => $this->request->getVar('car_codigo'),
            'car_nombre'      => $this->request->getVar('car_nombre'),
            'car_descripcion' => $this->request->getVar('car_descripcion'),
            'car_estado'      => $this->request->getVar('car_estado') ?? 1,
        ];

        $resultado = $service->crearCargo($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Cargo registrado correctamente.']);
    }

    /**
     * API: Actualizar Cargo (Evitando nulos con RawInput)
     * PUT /asistencia/gestordb/api/cargos/1
     */
    public function apiActualizarCargo($id = null)
    {
        $service = new CargoService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'car_codigo'      => $rawDatos['car_codigo'] ?? null,
            'car_nombre'      => $rawDatos['car_nombre'] ?? null,
            'car_descripcion' => $rawDatos['car_descripcion'] ?? null,
            'car_estado'      => isset($rawDatos['car_estado']) ? (int)$rawDatos['car_estado'] : 1,
        ];

        $resultado = $service->actualizarCargo((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Cargo actualizado con éxito.']);
    }

    /**
     * API: Eliminar Cargo (Soft Delete)
     * DELETE /asistencia/gestordb/api/cargos/1
     */
    public function apiEliminarCargo($id = null)
    {
        $service = new CargoService();

        if ($service->eliminarCargo((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Cargo enviado a la papelera.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    /**
     * API Lookup: Listar solo activos para combos
     * GET /asistencia/gestordb/api/cargos-lookup
     */
    public function apiCargosLookup()
    {
        $service = new CargoService();
        $activos = $service->obtenerActivos();

        $data = array_map(function ($item) {
            return [
                'id'     => $item['car_ide'],
                'nombre' => $item['car_nombre']
            ];
        }, $activos);

        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    /**
     * Vista Principal de Profesiones
     * GET /asistencia/gestordb/profesion
     */
    public function profesiones()
    {
        return view('Modules\Asistencia\Views\database\profesiones_view');
    }

    /**
     * API: Listar profesiones
     * GET /asistencia/gestordb/api/profesiones
     */
    public function apiListarProfesiones()
    {
        $service = new ProfesionService();
        $search = $this->request->getVar('search');
        $data = $service->listarProfesiones($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Profesión
     * POST /asistencia/gestordb/api/profesiones
     */
    public function apiCrearProfesion()
    {
        $service = new ProfesionService();
        $datos = [
            'pro_codigo'      => $this->request->getVar('pro_codigo'),
            'pro_nombre'      => $this->request->getVar('pro_nombre'),
            'pro_abreviatura' => $this->request->getVar('pro_abreviatura'),
            'pro_estado'      => $this->request->getVar('pro_estado') ?? 1,
        ];

        $resultado = $service->crearProfesion($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Profesión registrada correctamente.']);
    }

    /**
     * API: Actualizar Profesión (getRawInput)
     * PUT /asistencia/gestordb/api/profesiones/1
     */
    public function apiActualizarProfesion($id = null)
    {
        $service = new ProfesionService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'pro_codigo'      => $rawDatos['pro_codigo'] ?? null,
            'pro_nombre'      => $rawDatos['pro_nombre'] ?? null,
            'pro_abreviatura' => $rawDatos['pro_abreviatura'] ?? null,
            'pro_estado'      => isset($rawDatos['pro_estado']) ? (int)$rawDatos['pro_estado'] : 1,
        ];

        $resultado = $service->actualizarProfesion((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Profesión actualizada con éxito.']);
    }

    /**
     * API: Eliminar Profesión (Soft Delete)
     * DELETE /asistencia/gestordb/api/profesiones/1
     */
    public function apiEliminarProfesion($id = null)
    {
        $service = new ProfesionService();

        if ($service->eliminarProfesion((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Profesión enviada a la papelera.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    /**
     * API Lookup: Listar solo activas para selectores
     * GET /asistencia/gestordb/api/profesiones-lookup
     */
    public function apiProfesionesLookup()
    {
        $service = new ProfesionService();
        $activas = $service->obtenerActivas();

        $data = array_map(function ($item) {
            return [
                'id'     => $item['pro_ide'],
                'nombre' => $item['pro_nombre']
            ];
        }, $activas);

        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    /**
     * Vista Principal de Colegiaturas
     * GET /asistencia/gestordb/colegiatura
     */
    public function colegiaturas()
    {
        return view('Modules\Asistencia\Views\database\colegiaturas_view');
    }

    /**
     * API: Listar colegiaturas con JOIN
     * GET /asistencia/gestordb/api/colegiaturas
     */
    public function apiListarColegiaturas()
    {
        $service = new ColegiaturaService();
        $search = $this->request->getVar('search');
        $data = $service->listarColegiaturas($search);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Colegiatura
     * POST /asistencia/gestordb/api/colegiaturas
     */
    public function apiCrearColegiatura()
    {
        $service = new ColegiaturaService();
        $datos = [
            'col_pro_ide'     => $this->request->getVar('col_pro_ide'),
            'col_nombre'      => $this->request->getVar('col_nombre'),
            'col_abreviatura' => $this->request->getVar('col_abreviatura'),
            'col_estado'      => $this->request->getVar('col_estado') ?? 1,
        ];

        $resultado = $service->crearColegiatura($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Colegiatura registrada correctamente.']);
    }

    /**
     * API: Actualizar Colegiatura (getRawInput)
     * PUT /asistencia/gestordb/api/colegiaturas/1
     */
    public function apiActualizarColegiatura($id = null)
    {
        $service = new ColegiaturaService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'col_pro_ide'     => isset($rawDatos['col_pro_ide']) ? (int)$rawDatos['col_pro_ide'] : null,
            'col_nombre'      => $rawDatos['col_nombre'] ?? null,
            'col_abreviatura' => $rawDatos['col_abreviatura'] ?? null,
            'col_estado'      => isset($rawDatos['col_estado']) ? (int)$rawDatos['col_estado'] : 1,
        ];

        $resultado = $service->actualizarColegiatura((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Colegiatura actualizada con éxito.']);
    }

    /**
     * API: Eliminar Colegiatura (Soft Delete)
     * DELETE /asistencia/gestordb/api/colegiaturas/1
     */
    public function apiEliminarColegiatura($id = null)
    {
        $service = new ColegiaturaService();

        if ($service->eliminarColegiatura((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Colegiatura enviada a la papelera.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    /**
     * API Lookup: Filtrar colegiaturas por profesión seleccionada (útil para formularios dinámicos de empleados)
     * GET /asistencia/gestordb/api/colegiaturas-por-profesion/(:num)
     */
    public function apiColegiaturasPorProfesion($proIde = null)
    {
        $service = new ColegiaturaService();
        $data = $service->obtenerPorProfesion((int)$proIde);
        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    /**
     * Vista de Calendario de Feriados
     * GET /asistencia/gestordb/feriado
     */
    public function feriados()
    {
        return view('Modules\Asistencia\Views\database\feriados_view');
    }

    /**
     * API: Listar Feriados
     * GET /asistencia/gestordb/api/feriados
     */
    public function apiListarFeriados()
    {
        $service = new DiaFeriadoService();
        $search = $this->request->getVar('search');
        $anio = $this->request->getVar('anio') ? (int)$this->request->getVar('anio') : null;

        $data = $service->listarFeriados($search, $anio);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Feriado
     * POST /asistencia/gestordb/api/feriados
     */
    public function apiCrearFeriado()
    {
        $service = new DiaFeriadoService();
        $datos = [
            'df_nombre'     => $this->request->getVar('df_nombre'),
            'df_fecha'      => $this->request->getVar('df_fecha'),
            'df_tipo'       => $this->request->getVar('df_tipo'),
            'df_repetitivo' => $this->request->getVar('df_repetitivo') ?? 0,
        ];

        $resultado = $service->crearFeriado($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Feriado registrado correctamente.']);
    }

    /**
     * API: Actualizar Feriado
     * PUT /asistencia/gestordb/api/feriados/1
     */
    public function apiActualizarFeriado($id = null)
    {
        $service = new DiaFeriadoService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'df_nombre'     => $rawDatos['df_nombre'] ?? null,
            'df_fecha'      => $rawDatos['df_fecha'] ?? null,
            'df_tipo'       => $rawDatos['df_tipo'] ?? null,
            'df_repetitivo' => isset($rawDatos['df_repetitivo']) ? (int)$rawDatos['df_repetitivo'] : 0,
        ];

        $resultado = $service->actualizarFeriado((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Feriado actualizado con éxito.']);
    }

    /**
     * API: Eliminar Feriado (Soft Delete)
     * DELETE /asistencia/gestordb/api/feriados/1
     */
    public function apiEliminarFeriado($id = null)
    {
        $service = new DiaFeriadoService();

        if ($service->eliminarFeriado((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Feriado enviado a la papelera.']);
        }
        return $this->fail('No se pudo eliminar el feriado.', 400);
    }

    /**
     * Vista de Modalidades de Contrato
     * GET /asistencia/gestordb/modalidad
     */
    public function modalidades()
    {
        return view('Modules\Asistencia\Views\database\modalidades_view');
    }

    /**
     * API: Listar modalidades de contratación
     * GET /asistencia/gestordb/api/modalidades
     */
    public function apiListarModalidades()
    {
        $service = new ModalidadContratoService();
        $search = $this->request->getVar('search');
        $estado = ($this->request->getVar('estado') === null || $this->request->getVar('estado') === '') ? null : (int)$this->request->getVar('estado');

        $data = $service->listarModalidades($search, $estado);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Registrar modalidad de contrato
     * POST /asistencia/gestordb/api/modalidades
     */
    public function apiCrearModalidad()
    {
        $service = new ModalidadContratoService();
        $datos = [
            'mco_codigo'      => $this->request->getVar('mco_codigo'),
            'mco_nombre'      => $this->request->getVar('mco_nombre'),
            'mco_descripcion' => $this->request->getVar('mco_descripcion'),
            'mco_base_legal'  => $this->request->getVar('mco_base_legal'),
            'mco_estado'      => $this->request->getVar('mco_estado') ?? 1,
        ];

        $resultado = $service->crearModalidad($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Modalidad de contrato registrada con éxito.']);
    }

    /**
     * API: Actualizar modalidad de contrato
     * PUT /asistencia/gestordb/api/modalidades/1
     */
    public function apiActualizarModalidad($id = null)
    {
        $service = new ModalidadContratoService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'mco_codigo'      => $rawDatos['mco_codigo'] ?? null,
            'mco_nombre'      => $rawDatos['mco_nombre'] ?? null,
            'mco_descripcion' => $rawDatos['mco_descripcion'] ?? null,
            'mco_base_legal'  => $rawDatos['mco_base_legal'] ?? null,
            'mco_estado'      => isset($rawDatos['mco_estado']) ? (int)$rawDatos['mco_estado'] : 1,
        ];

        $resultado = $service->actualizarModalidad((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Modalidad de contrato actualizada correctamente.']);
    }

    /**
     * API: Eliminar modalidad (Soft Delete)
     * DELETE /asistencia/gestordb/api/modalidades/1
     */
    public function apiEliminarModalidad($id = null)
    {
        $service = new ModalidadContratoService();

        if ($service->eliminarModalidad((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Régimen de contrato enviado a la papelera de reciclaje.']);
        }
        return $this->fail('No se pudo desactivar el registro.', 400);
    }

    /**
     * Vista de Gestión de Tipos de Licencias
     * GET /asistencia/gestordb/licencia
     */
    public function licencias()
    {
        return view('Modules\Asistencia\Views\database\licencias_view');
    }

    /**
     * API: Listar Licencias
     * GET /asistencia/gestordb/api/licencias
     */
    public function apiListarLicencias()
    {
        $service = new LicenciaService();
        $search = $this->request->getVar('search');
        $estado = $this->request->getVar('estado') !== null ? (int)$this->request->getVar('estado') : null;
        // CORRECCIÓN: Si viene vacío "" o no está definido, se guarda como null
        $rawRemunerado = $this->request->getVar('remunerado');
        $remunerado = ($rawRemunerado === '' || $rawRemunerado === null) ? null : (int)$rawRemunerado;
        $data = $service->listarLicencias($search, $estado, $remunerado);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Registrar Licencia
     * POST /asistencia/gestordb/api/licencias
     */
    public function apiCrearLicencia()
    {
        $service = new LicenciaService();
        $datos = [
            'lic_nombre'       => $this->request->getVar('lic_nombre'),
            'lic_abreviatura'  => $this->request->getVar('lic_abreviatura'),
            'lic_remunerado'   => $this->request->getVar('lic_remunerado') ?? 0,
            'lic_dias_maximos' => $this->request->getVar('lic_dias_maximos') ? (int)$this->request->getVar('lic_dias_maximos') : null,
            'lic_descripcion'  => $this->request->getVar('lic_descripcion'),
            'lic_estado'       => $this->request->getVar('lic_estado') ?? 1,
        ];

        $resultado = $service->crearLicencia($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Tipo de licencia registrado con éxito.']);
    }

    /**
     * API: Actualizar Licencia
     * PUT /asistencia/gestordb/api/licencias/1
     */
    public function apiActualizarLicencia($id = null)
    {
        $service = new LicenciaService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'lic_nombre'       => $rawDatos['lic_nombre'] ?? null,
            'lic_abreviatura'  => $rawDatos['lic_abreviatura'] ?? null,
            'lic_remunerado'   => isset($rawDatos['lic_remunerado']) ? (int)$rawDatos['lic_remunerado'] : 0,
            'lic_dias_maximos' => !empty($rawDatos['lic_dias_maximos']) ? (int)$rawDatos['lic_dias_maximos'] : null,
            'lic_descripcion'  => $rawDatos['lic_descripcion'] ?? null,
            'lic_estado'       => isset($rawDatos['lic_estado']) ? (int)$rawDatos['lic_estado'] : 1,
        ];

        $resultado = $service->actualizarLicencia((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Tipo de licencia actualizado correctamente.']);
    }

    /**
     * API: Eliminar Licencia (Soft Delete)
     * DELETE /asistencia/gestordb/api/licencias/1
     */
    public function apiEliminarLicencia($id = null)
    {
        $service = new LicenciaService();

        if ($service->eliminarLicencia((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Tipo de licencia enviado a la papelera.']);
        }
        return $this->fail('No se pudo desactivar el tipo de licencia.', 400);
    }

    /**
     * Vista de Tipos de Permisos por Horas
     * GET /asistencia/gestordb/permiso
     */
    public function permisos()
    {
        return view('Modules\Asistencia\Views\database\permisos_view');
    }

    /**
     * API: Listar Permisos
     * GET /asistencia/gestordb/api/permisos
     */
    public function apiListarPermisos()
    {
        $service = new PermisoService();
        $search = $this->request->getVar('search');
        $estado = $this->request->getVar('estado') !== null ? (int)$this->request->getVar('estado') : null;

        // Tratamiento seguro de valor null para cadenas vacías de los selects
        $rawRemunerado = $this->request->getVar('remunerado');
        $remunerado = ($rawRemunerado === '' || $rawRemunerado === null) ? null : (int)$rawRemunerado;

        $data = $service->listarPermisos($search, $estado, $remunerado);

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * API: Crear Permiso
     * POST /asistencia/gestordb/api/permisos
     */
    public function apiCrearPermiso()
    {
        $service = new PermisoService();
        $datos = [
            'pero_nombre'        => $this->request->getVar('pero_nombre'),
            'pero_abreviatura'   => $this->request->getVar('pero_abreviatura'),
            'pero_descripcion'   => $this->request->getVar('pero_descripcion'),
            'pero_remunerado'    => $this->request->getVar('pero_remunerado') ?? 0,
            'pero_horas_maximas' => $this->request->getVar('pero_horas_maximas') ? (int)$this->request->getVar('pero_horas_maximas') : null,
            'pero_estado'        => $this->request->getVar('pero_estado') ?? 1,
        ];

        $resultado = $service->crearPermiso($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Permiso registrado correctamente.']);
    }

    /**
     * API: Actualizar Permiso
     * PUT /asistencia/gestordb/api/permisos/1
     */
    public function apiActualizarPermiso($id = null)
    {
        $service = new PermisoService();
        $rawDatos = $this->request->getRawInput();

        $datos = [
            'pero_nombre'        => $rawDatos['pero_nombre'] ?? null,
            'pero_abreviatura'   => $rawDatos['pero_abreviatura'] ?? null,
            'pero_descripcion'   => $rawDatos['pero_descripcion'] ?? null,
            'pero_remunerado'    => isset($rawDatos['pero_remunerado']) ? (int)$rawDatos['pero_remunerado'] : 0,
            'pero_horas_maximas' => !empty($rawDatos['pero_horas_maximas']) ? (int)$rawDatos['pero_horas_maximas'] : null,
            'pero_estado'        => isset($rawDatos['pero_estado']) ? (int)$rawDatos['pero_estado'] : 1,
        ];

        $resultado = $service->actualizarPermiso((int)$id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Permiso actualizado con éxito.']);
    }

    /**
     * API: Eliminar Permiso (Soft Delete)
     * DELETE /asistencia/gestordb/api/permisos/1
     */
    public function apiEliminarPermiso($id = null)
    {
        $service = new PermisoService();

        if ($service->eliminarPermiso((int)$id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Permiso enviado a la papelera.']);
        }
        return $this->fail('No se pudo desactivar el permiso.', 400);
    }
}
