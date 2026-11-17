<?php

declare(strict_types=1);

use App\Cells\DataTableCell;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

final class UiComponentsTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        helper(['ui', 'url']);
    }

    public function testButtonEscapesLabelsAndSupportsBootstrapModalTriggers(): void
    {
        $html = ui_button('<Guardar>', ['attrs' => ['data-bs-toggle' => 'modal', 'data-bs-target' => '#editar']]);
        self::assertStringContainsString('&lt;Guardar&gt;', $html);
        self::assertStringContainsString('type="button"', $html);
        self::assertStringContainsString('data-bs-target="#editar"', $html);
        self::assertStringContainsString('btn btn-primary', $html);
    }

    public function testExternalLinkProtectsNewTabAndDisabledLinkCannotNavigate(): void
    {
        $html = ui_button('Ver', ['href' => 'https://example.com/?a=1&b=2', 'attrs' => ['target' => '_blank']]);
        self::assertStringContainsString('a=1&amp;b=2', $html);
        self::assertStringContainsString('noopener noreferrer', $html);
        $disabled = ui_button('No disponible', ['href' => '/delete', 'disabled' => true]);
        self::assertStringContainsString('href="#"', $disabled);
        self::assertStringContainsString('aria-disabled="true"', $disabled);
    }

    public function testButtonRejectsExecutableUrls(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ui_button('Abrir', ['href' => 'javascript:alert(1)']);
    }

    public function testAttributesRejectInlineEventHandlers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ui_button('Abrir', ['attrs' => ['onclick' => 'alert(1)']]);
    }

    public function testBadgeRejectsAnUnknownVariant(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ui_badge('Activo', 'invented');
    }

    public function testDisabledSwitchDisablesHiddenFallbackAndCheckbox(): void
    {
        $html = ui_switch('activo', true, ['id' => 'estado', 'label' => '<Activo>', 'disabled' => true]);
        self::assertSame(2, substr_count($html, ' disabled'));
        self::assertStringContainsString('role="switch"', $html);
        self::assertStringContainsString('for="estado"', $html);
        self::assertStringContainsString('&lt;Activo&gt;', $html);
        self::assertStringContainsString('value="0"', $html);
    }

    public function testStaticTableEscapesRowsButAcceptsTrustedHtmlFormatters(): void
    {
        $html = view('components/table', [
            'columns' => [
                ['key' => 'name', 'label' => 'Nombre'],
                ['key' => 'status', 'label' => 'Estado', 'html' => true,
                    'format' => static fn ($value) => ui_badge($value, 'success')],
            ],
            'rows' => [['name' => '<script>alert(1)</script>', 'status' => 'Activo']],
        ], ['saveData' => false]);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringContainsString('&lt;script&gt;', $html);
        self::assertStringContainsString('badge bg-success-subtle text-success', $html);
    }

    public function testRawHtmlRequiresDeveloperOwnedFormatter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ui_columns([['key' => 'name', 'label' => 'Nombre', 'html' => true]]);
    }

    public function testEmptyDynamicTablesHaveNoColspanAndSupportColumnFilters(): void
    {
        $html = view_cell(DataTableCell::class, [
            'id' => 'usuarios', 'columns' => [['key' => 'name', 'label' => 'Nombre']],
            'rows' => [], 'columnFilters' => true, 'pageLength' => 25,
        ]);
        self::assertStringNotContainsString('colspan=', $html);
        self::assertStringContainsString('data-ui-column="0"', $html);
        self::assertStringContainsString('&quot;pageLength&quot;:25', $html);
        self::assertStringNotContainsString('<script', $html);
    }

    public function testCellCanRenderClosuresWithoutFrameworkCacheSerialization(): void
    {
        $cell = (new DataTableCell())->fill([
            'id' => 'estados',
            'columns' => [['key' => 'estado', 'label' => 'Estado', 'html' => true,
                'format' => static fn ($value) => ui_badge($value, 'success')]],
            'rows' => [['estado' => 'Activo']],
        ]);
        self::assertStringContainsString('badge bg-success-subtle text-success', $cell->render());
    }

    public function testCellRejectsInvalidSortColumn(): void
    {
        $cell = (new DataTableCell())->fill([
            'id' => 'invalid', 'columns' => [['key' => 'id', 'label' => 'ID']], 'order' => [[7, 'asc']],
        ]);
        $this->expectException(InvalidArgumentException::class);
        $cell->render();
    }

    public function testModalEscapesTitleAndPreservesTrustedBody(): void
    {
        $html = view('components/modal', ['id' => 'editar', 'size' => 'xl', 'title' => '<Editar>',
            'bodyHtml' => '<form id="edit-form"></form>'], ['saveData' => false]);
        self::assertStringContainsString('modal-xl', $html);
        self::assertStringContainsString('&lt;Editar&gt;', $html);
        self::assertStringContainsString('<form id="edit-form">', $html);
        self::assertStringContainsString('aria-labelledby="editar-title"', $html);
    }

    public function testCardOmitsOptionalHeaderAndEscapesTitle(): void
    {
        $html = view('components/card', ['bodyHtml' => '<p>Cuerpo</p>'], ['saveData' => false]);
        self::assertStringNotContainsString('card-header', $html);
        self::assertStringContainsString('<p>Cuerpo</p>', $html);
        $html = view('components/card', ['title' => '<Datos>', 'bodyHtml' => 'Cuerpo'], ['saveData' => false]);
        self::assertStringContainsString('&lt;Datos&gt;', $html);
        self::assertStringNotContainsString('card-footer', $html);
    }

    public function testFlashToastsEscapeSessionContent(): void
    {
        $session = $this->createMock(\CodeIgniter\Session\SessionInterface::class);
        $session->method('getFlashdata')->willReturn([
            'toasts' => [['variant' => 'success', 'message' => '<img src=x onerror=alert(1)>']],
            'error' => 'Error de validación',
        ]);
        Services::injectMock('session', $session);
        $html = view('components/toast', [], ['saveData' => false]);
        self::assertSame(2, substr_count($html, 'data-ui-toast'));
        self::assertStringNotContainsString('<img', $html);
        self::assertStringContainsString('&lt;img', $html);
        self::assertStringContainsString('data-bs-autohide="false"', $html);
    }

    public function testLayoutsRenderSectionsAndImportExistingAssetsOnlyOnce(): void
    {
        $session = $this->createMock(\CodeIgniter\Session\SessionInterface::class);
        $session->method('getFlashdata')->willReturn([]);
        Services::injectMock('session', $session);
        foreach (['main', 'auth', '../Shield/layout'] as $layout) {
            $html = view('App\\Verification\\Views\\ui_layout', [
                'layout' => $layout, 'uiUser' => (object) ['username' => 'Operador'],
                'bodySection' => $layout === '../Shield/layout' ? 'main' : 'content',
                'legacy' => $layout === '../Shield/layout',
            ], ['saveData' => false]);
            foreach (['ui-content', 'ui-modal', 'ui-styles', 'ui-scripts'] as $marker) {
                self::assertStringContainsString($marker, $html);
            }
            self::assertSame(1, substr_count($html, 'assets/css/styles.css'));
            self::assertSame(1, substr_count($html, 'bootstrap.bundle.min.js'));
            self::assertSame(1, substr_count($html, 'vendor.min.js'));
            preg_match_all('~(?:src|href)="[^"]*/(assets/[^"?]+)"~', $html, $assets);
            foreach ($assets[1] as $asset) {
                self::assertFileExists(FCPATH . rawurldecode($asset), $asset);
                self::assertGreaterThan(0, filesize(FCPATH . rawurldecode($asset)), $asset);
            }
        }
    }

    public function testFormFieldEscapesValueAndConnectsLabelHelpAndValidation(): void
    {
        $html = view('components/form_field', [
            'name' => 'nombre', 'id' => 'persona-nombre', 'label' => 'Nombre',
            'value' => '"><script>alert(1)</script>', 'required' => true,
            'helpText' => 'Como figura en su documento.', 'errors' => ['<Dato inválido>'],
        ], ['saveData' => false]);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringContainsString('is-invalid', $html);
        self::assertStringContainsString('aria-invalid="true"', $html);
        self::assertStringContainsString('aria-describedby="persona-nombre-help persona-nombre-error"', $html);
        self::assertStringContainsString('for="persona-nombre"', $html);
        self::assertStringContainsString('&lt;Dato inválido&gt;', $html);
    }

    public function testPasswordAndFileFieldsNeverRepopulateValues(): void
    {
        foreach (['password', 'file'] as $type) {
            $html = view('components/form_field', ['name' => $type, 'type' => $type,
                'value' => 'secret-value', 'attributes' => ['value' => 'injected-value']], ['saveData' => false]);
            self::assertStringNotContainsString('secret-value', $html);
            self::assertStringNotContainsString('injected-value', $html);
        }
    }

    public function testSelectPreservesZeroValueAndEscapesOptions(): void
    {
        $html = view('components/form_field', ['name' => 'estado', 'type' => 'select', 'value' => 0,
            'options' => [0 => '<Inactivo>', 1 => 'Activo'], 'placeholder' => 'Seleccione'], ['saveData' => false]);
        self::assertStringContainsString('value="0" selected>&lt;Inactivo&gt;', $html);
        self::assertStringNotContainsString('value="" selected', $html);
    }

    public function testTextareaEscapesClosingTagAndEmptyValidationDoesNotMarkInvalid(): void
    {
        $html = view('components/form_field', ['name' => 'nota', 'type' => 'textarea',
            'value' => '</textarea><script>alert(1)</script>', 'errors' => ''], ['saveData' => false]);
        self::assertStringContainsString('&lt;/textarea&gt;', $html);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringNotContainsString('is-invalid', $html);
    }

    public function testValidationSummaryOmitsEmptyErrorsAndEscapesMessages(): void
    {
        self::assertSame('', trim(view('components/form_errors', ['errors' => []], ['saveData' => false, 'debug' => false])));
        $html = view('components/form_errors', ['errors' => ['nombre' => '<Requerido>']], ['saveData' => false]);
        self::assertStringContainsString('&lt;Requerido&gt;', $html);
        self::assertStringContainsString('role="alert"', $html);
    }

    public function testAlertAndEmptyStateEscapeTextAndKeepExplicitTrustedActions(): void
    {
        $html = view('components/alert', ['message' => '<Aviso>', 'dismissible' => true], ['saveData' => false]);
        self::assertStringContainsString('&lt;Aviso&gt;', $html);
        self::assertStringContainsString('data-bs-dismiss="alert"', $html);
        $html = view('components/empty_state', ['title' => '<Vacío>',
            'actionsHtml' => ui_button('Crear')], ['saveData' => false]);
        self::assertStringContainsString('&lt;Vacío&gt;', $html);
        self::assertStringContainsString('<button', $html);
    }

    public function testBreadcrumbsHaveOneCurrentItemAndEscapeLabels(): void
    {
        $html = view('components/breadcrumbs', ['items' => [
            ['label' => 'Inicio', 'url' => '/inicio'], ['label' => '<Personal>', 'url' => '/personal'],
        ]], ['saveData' => false]);
        self::assertSame(1, substr_count($html, 'aria-current="page"'));
        self::assertStringContainsString('&lt;Personal&gt;', $html);
        self::assertStringNotContainsString('href="/personal"', $html);
    }

    public function testDropdownSupportsModalActionsAndDisabledLinks(): void
    {
        $html = view('components/dropdown', ['id' => 'fila-7', 'items' => [
            ['label' => '<Editar>', 'attrs' => ['data-bs-toggle' => 'modal', 'data-bs-target' => '#editar']],
            ['divider' => true], ['label' => 'Bloqueado', 'href' => '/delete', 'disabled' => true],
        ]], ['saveData' => false]);
        self::assertStringContainsString('aria-labelledby="fila-7"', $html);
        self::assertStringContainsString('data-bs-target="#editar"', $html);
        self::assertStringContainsString('&lt;Editar&gt;', $html);
        self::assertStringContainsString('aria-disabled="true"', $html);
        self::assertStringNotContainsString('href="/delete"', $html);
    }
}
