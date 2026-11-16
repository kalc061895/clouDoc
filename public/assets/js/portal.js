(() => {
    const controls = document.querySelector('[data-resource-controls]');
    if (!controls) return;
    controls.hidden = false;
    const cards = [...document.querySelectorAll('[data-resource-card]')];
    const search = document.getElementById('buscar-recurso');
    const buttons = [...controls.querySelectorAll('[data-resource-type]')];
    const normalize = text => text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    let type = 'todos';
    function filter() {
        const term = normalize(search.value.trim());
        for (const card of cards) card.hidden = (type !== 'todos' && card.dataset.type !== type) || !normalize(card.dataset.search).includes(term);
        document.getElementById('sin-recursos').hidden = cards.some(card => !card.hidden);
    }
    buttons.forEach(button => button.addEventListener('click', () => {
        type = button.dataset.resourceType;
        buttons.forEach(item => { const selected = item === button; item.classList.toggle('selected', selected); item.setAttribute('aria-pressed', String(selected)); });
        filter();
    }));
    search.addEventListener('input', filter);
})();
