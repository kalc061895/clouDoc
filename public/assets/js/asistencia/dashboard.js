/* ApexCharts y tipografía de la plantilla local. */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    const source = document.getElementById('dash-chart-data');
    if (!source || typeof ApexCharts === 'undefined') return;
    const data = JSON.parse(source.textContent);
    const palette = ['#1686ce', '#7958ce', '#18a888', '#efac42', '#e66578', '#45b6ca', '#7184a1', '#bb78bd'];
    const styles = getComputedStyle(document.documentElement);
    const textColor = styles.getPropertyValue('--bs-body-color').trim() || '#556777';
    const borderColor = styles.getPropertyValue('--bs-border-color').trim() || '#e8edf3';
    const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const number = value => Number(value).toLocaleString('es-PE', { maximumFractionDigits: 0 });
    // Algunas etiquetas y tooltips de ApexCharts se insertan como HTML.
    const safe = value => String(value).replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
    const short = value => value.length > 48 ? value.slice(0, 45) + '…' : value;
    const base = type => ({
        chart: { type, height: 310, fontFamily: 'inherit', foreColor: textColor, toolbar: { show: false }, animations: { enabled: !window.matchMedia('(prefers-reduced-motion: reduce)').matches } },
        colors: palette, dataLabels: { enabled: false }, grid: { borderColor, strokeDashArray: 4 },
        legend: { position: 'bottom', fontSize: '12px' },
        tooltip: { theme: dark ? 'dark' : 'light', y: { formatter: number } },
        noData: { text: 'Sin datos disponibles', style: { color: textColor } }
    });
    function render(id, options) {
        const el = document.getElementById('dash-chart-' + id);
        if (!el) return;
        const fallback = el.innerHTML;
        el.innerHTML = '';
        try {
            const chart = new ApexCharts(el, options);
            Promise.resolve(chart.render()).catch(() => { el.innerHTML = fallback; });
        } catch (_) { el.innerHTML = fallback; }
    }
    const s = data.stats;
    if (s.cobertura !== null) {
        render('cobertura', { ...base('radialBar'), series: [s.cobertura], colors: ['#18a888'], labels: ['Cobertura'], stroke: { lineCap: 'round' },
            plotOptions: { radialBar: { hollow: { size: '68%' }, track: { background: borderColor }, dataLabels: { name: { fontSize: '14px', offsetY: 24 }, value: { offsetY: -16, fontSize: '38px', fontWeight: 700, color: textColor, formatter: value => Number(value).toLocaleString('es-PE') + '%' } } } }
        });
    }
    const journey = [['Ingresaron', s.ingresaron], ['Ingreso pendiente', s.pendientes], ['Posibles faltas', s.posibles_faltas], ['Licencia / vacaciones', s.justificados], ['Turno por iniciar', s.futuro]];
    render('jornada', { ...base('bar'), series: [{ name: 'Personas', data: journey.map(r => r[1]) }], colors: ['#18a888', '#efac42', '#e66578', '#1686ce', '#7958ce'],
        plotOptions: { bar: { horizontal: true, distributed: true, borderRadius: 5, barHeight: '55%' } },
        dataLabels: { enabled: true, formatter: number }, legend: { show: false }, xaxis: { categories: journey.map(r => r[0]), labels: { formatter: number }, min: 0 }, tooltip: { enabled: false }
    });
    Object.entries(data.grupos).forEach(([key, group]) => {
        const rows = Object.entries(group);
        if (!rows.length) return;
        if (key === 'sexo') {
            render(key, { ...base('donut'), series: rows.map(r => r[1].total), labels: rows.map(r => safe(r[0])),
                stroke: { width: 3, colors: [styles.getPropertyValue('--bs-body-bg').trim() || '#fff'] },
                plotOptions: { pie: { donut: { size: '72%', labels: { show: true, value: { formatter: number }, total: { show: true, showAlways: true, label: 'Personal activo', color: textColor, formatter: () => number(rows.reduce((sum, r) => sum + r[1].total, 0)) } } } } },
                tooltip: { enabled: false }
            });
            return;
        }
        // No se suman servicios restantes: una persona puede pertenecer a varios.
        const top = rows.slice(0, 8);
        const opts = base('bar');
        opts.chart.height = Math.max(310, top.length * 54);
        render(key, { ...opts,
            series: [{ name: key === 'servicio' ? 'Programados' : 'Activos', data: top.map(r => r[1].total) }, { name: 'Ingresaron', data: top.map(r => r[1].ingresos) }],
            colors: ['#1686ce', '#18a888'], plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '65%' } },
            xaxis: { categories: top.map(r => safe(short(r[0]))), min: 0, labels: { formatter: number } }, yaxis: { labels: { maxWidth: 210 } },
            tooltip: { theme: dark ? 'dark' : 'light', x: { formatter: (_value, context) => safe(top[context.dataPointIndex][0]) }, y: { formatter: number } },
            responsive: [{ breakpoint: 576, options: { yaxis: { labels: { maxWidth: 120 } } } }]
        });
    });
});
