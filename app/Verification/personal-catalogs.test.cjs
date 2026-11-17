const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

// Execute the real transport/state function without a browser or a second implementation.
const source = fs.readFileSync(path.resolve(__dirname, '../../Modules/Asistencia/Views/personal/partials/registrar_script.php'), 'utf8');
const context = {};
vm.runInNewContext(source.slice(source.indexOf('function crearCargador'), source.indexOf('\n$(function')), context);
function setup() {
    const requests = [];
    const renders = [];
    const loader = context.crearCargadorCatalogosPersonal(url => {
        const xhr = {url, status: 500, done(fn) {this.success = fn; return this;}, fail(fn) {this.failure = fn; return this;},
            abort() {this.aborted = true; this.failure?.(this, 'abort');}};
        requests.push(xhr);
        return xhr;
    }, (...args) => renders.push(args));
    return {loader, requests, renders};
}
test('loading persists until the requested catalog is ready', () => {
    const {loader, requests, renders} = setup();
    loader.load('oficina', '/oficinas?est=1');
    assert.equal(loader.state('oficina'), 'loading');
    requests[0].success({status: 'success', data: [{id: 1, nombre: 'Administración'}]});
    assert.equal(loader.state('oficina'), 'ready');
    assert.equal(renders.at(-1)[2][0].nombre, 'Administración');
});
test('a newer parent selection cancels the prior request and ignores its late response', () => {
    const {loader, requests, renders} = setup();
    const applied = [];
    loader.load('colegio', '/profesion/1', rows => applied.push(rows));
    loader.load('colegio', '/profesion/2', rows => applied.push(rows));
    assert.equal(requests[0].aborted, true);
    requests[1].success({status: 'success', data: [{id: 2, nombre: 'Nuevo'}]});
    requests[0].success({status: 'success', data: [{id: 1, nombre: 'Anterior'}]});
    assert.equal(applied.length, 1);
    assert.equal(renders.at(-1)[2][0].id, 2);
});
test('clearing a parent cancels pending child updates', () => {
    const {loader, requests} = setup();
    let applied = false;
    loader.load('especialidad', '/especialidades', () => {applied = true;});
    loader.reset('especialidad');
    requests[0].success({status: 'success', data: [{id: 1, nombre: 'Anterior'}]});
    assert.equal(loader.state('especialidad'), 'idle');
    assert.equal(applied, false);
});
test('failed catalog offers a retry and can recover', () => {
    const {loader, requests, renders} = setup();
    loader.load('cargo', '/cargos');
    requests[0].failure(requests[0], 'timeout');
    assert.equal(loader.state('cargo'), 'error');
    renders.at(-1)[3]();
    assert.equal(loader.state('cargo'), 'loading');
    requests[1].success({status: 'success', data: [{id: 4, nombre: 'Cargo'}]});
    assert.equal(loader.state('cargo'), 'ready');
});
test('empty catalog differs from transport failure and invokes dependent-field callback', () => {
    const {loader, requests} = setup();
    let count = -1;
    loader.load('especialidad', '/especialidades', rows => {count = rows.length;});
    requests[0].success({status: 'success', data: []});
    assert.equal(loader.state('especialidad'), 'empty');
    assert.equal(count, 0);
});
test('invalid or unsuccessful JSON cannot leave a catalog stuck loading', () => {
    for (const response of [null, {}, {status: 'error', data: []}, {status: 'success', data: 'invalid'}, {status: 'success', data: [null]}]) {
        const {loader, requests} = setup();
        loader.load('tipo', '/tipos');
        requests[0].success(response);
        assert.equal(loader.state('tipo'), 'error');
    }
});
