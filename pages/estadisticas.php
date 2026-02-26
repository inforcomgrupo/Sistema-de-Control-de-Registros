<?php
/**
 * Página: Estadísticas
 * Dashboard completo con Chart.js
 */
if (!defined('SISTEMA_REGISTROS')) {
    define('SISTEMA_REGISTROS', true);
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/app.php';
    require_once __DIR__ . '/../includes/auth.php';
}
?>

<!-- Resumen compacto -->
<div class="stats-bar" id="statsBarEstadisticas" style="flex-shrink:0;">
    <div class="stat-card stat-total">
        <div class="stat-icon"><i class="fas fa-database"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estTotal">0</span>
            <span class="stat-label">Total</span>
        </div>
    </div>
    <div class="stat-card stat-today">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estHoy">0</span>
            <span class="stat-label">Hoy</span>
        </div>
    </div>
    <div class="stat-card stat-week">
        <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estSemana">0</span>
            <span class="stat-label">Semana</span>
        </div>
    </div>
    <div class="stat-card stat-month">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estMes">0</span>
            <span class="stat-label">Mes</span>
        </div>
    </div>
    <div class="stat-card stat-asesores">
        <div class="stat-icon"><i class="fas fa-headset"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estAsesores">0</span>
            <span class="stat-label">Asesores</span>
        </div>
    </div>
    <div class="stat-card stat-delegados">
        <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estDelegados">0</span>
            <span class="stat-label">Delegados</span>
        </div>
    </div>
    <div class="stat-card stat-cursos">
        <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estCursos">0</span>
            <span class="stat-label">Cursos</span>
        </div>
    </div>
    <div class="stat-card stat-paises">
        <div class="stat-icon"><i class="fas fa-globe-americas"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="estPaises">0</span>
            <span class="stat-label">Países</span>
        </div>
    </div>
</div>

<!-- TABS -->
<div class="est-tabs-container">
    <div class="est-tabs-nav" id="estTabsNav">
        <!-- ── IDs individuales para control por permiso ── -->
        <button class="est-tab-item active" data-tab="general" id="estTabGeneral">
            <i class="fas fa-globe"></i><span>GENERAL</span>
        </button>
        <button class="est-tab-item" data-tab="asesor" id="estTabAsesor">
            <i class="fas fa-headset"></i><span>ASESOR</span>
        </button>
        <button class="est-tab-item" data-tab="delegado" id="estTabDelegado">
            <i class="fas fa-user-shield"></i><span>DELEGADO</span>
        </button>
    </div>

    <!-- FILTROS DINÁMICOS POR TAB -->
    <div class="filters-bar" id="filtersBarEstadisticas" style="flex-shrink:0;">

        <!-- TAB: GENERAL -->
        <div class="est-tab-content active" id="tab-general">
            <div class="filters-row" style="flex-wrap:wrap;">
                <span class="filters-row-label" id="estLabelFormulario" style="width:86px;"><i class="fas fa-file-alt"></i> Formulario:</span>
                <select class="filter-select" id="estFormularioGeneral" style="min-width:220px;">
                    <option value="">Todos los Formularios</option>
                </select>

                <span class="filters-row-label" id="estLabelFechaGeneral" style="margin-left:12px;"><i class="fas fa-calendar"></i> Fecha:</span>
                <input type="date" class="filter-date-input" id="estFechaDesdeGeneral">
                <span class="filter-separator">a</span>
                <input type="date" class="filter-date-input" id="estFechaHastaGeneral">

                <span class="filters-row-label" style="margin-left:12px;"><i class="fas fa-chart-line"></i> Tendencia:</span>
                <select class="filter-select" id="estTendenciaGeneral" style="max-width:170px;">
                    <option value="dia" selected>Día</option>
                    <option value="semana">Semana</option>
                    <option value="mes">Mes</option>
                    <option value="bimestre">Bimestre</option>
                    <option value="trimestre">Trimestre</option>
                    <option value="semestre">Semestre</option>
                    <option value="anio">Año</option>
                </select>

                <button class="btn-filter-action btn-clear-filters" id="estBtnClearGeneral">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
            </div>
        </div>

        <!-- TAB: ASESOR -->
        <div class="est-tab-content" id="tab-asesor" style="display:none;">
            <div class="filters-row" style="flex-wrap:wrap;">
                <span class="filters-row-label" id="estLabelAsesor" style="width:86px;"><i class="fas fa-headset"></i> Asesor:</span>
                <select class="filter-select" id="estAsesor" style="min-width:220px;">
                    <option value="">Seleccionar Asesor</option>
                </select>

                <span class="filters-row-label" id="estLabelFechaAsesor" style="margin-left:12px;"><i class="fas fa-calendar"></i> Fecha:</span>
                <input type="date" class="filter-date-input" id="estFechaDesdeAsesor">
                <span class="filter-separator">a</span>
                <input type="date" class="filter-date-input" id="estFechaHastaAsesor">

                <span class="filters-row-label" style="margin-left:12px;"><i class="fas fa-chart-line"></i> Tendencia:</span>
                <select class="filter-select" id="estTendenciaAsesor" style="max-width:170px;">
                    <option value="dia" selected>Día</option>
                    <option value="semana">Semana</option>
                    <option value="mes">Mes</option>
                    <option value="bimestre">Bimestre</option>
                    <option value="trimestre">Trimestre</option>
                    <option value="semestre">Semestre</option>
                    <option value="anio">Año</option>
                </select>

                <button class="btn-filter-action btn-clear-filters" id="estBtnClearAsesor">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
            </div>
        </div>

        <!-- TAB: DELEGADO -->
        <div class="est-tab-content" id="tab-delegado" style="display:none;">
            <div class="filters-row" style="flex-wrap:wrap;">
                <span class="filters-row-label" id="estLabelDelegado" style="width:86px;"><i class="fas fa-user-shield"></i> Delegado:</span>
                <select class="filter-select" id="estDelegado" style="min-width:220px;">
                    <option value="">Seleccionar Delegado</option>
                </select>

                <span class="filters-row-label" id="estLabelFechaDelegado" style="margin-left:12px;"><i class="fas fa-calendar"></i> Fecha:</span>
                <input type="date" class="filter-date-input" id="estFechaDescuDelegado">
                <span class="filter-separator">a</span>
                <input type="date" class="filter-date-input" id="estFechaHastaDelegado">

                <span class="filters-row-label" style="margin-left:12px;"><i class="fas fa-chart-line"></i> Tendencia:</span>
                <select class="filter-select" id="estTendenciaDelegado" style="max-width:170px;">
                    <option value="dia" selected>Día</option>
                    <option value="semana">Semana</option>
                    <option value="mes">Mes</option>
                    <option value="bimestre">Bimestre</option>
                    <option value="trimestre">Trimestre</option>
                    <option value="semestre">Semestre</option>
                    <option value="anio">Año</option>
                </select>

                <button class="btn-filter-action btn-clear-filters" id="estBtnClearDelegado">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
            </div>
        </div>

        <!-- SUB-FILTROS (Siempre visibles) -->
        <div class="filters-row" id="estSubFiltrosRow" style="flex-wrap:wrap;">
            <span class="filters-row-label"><i class="fas fa-sliders-h"></i> Sub filtros:</span>
            <select class="filter-select" id="estFilterCurso"><option value="">Curso</option></select>
            <select class="filter-select" id="estFilterPais"><option value="">País</option></select>
            <select class="filter-select" id="estFilterCiudad"><option value="">Ciudad</option></select>
            <select class="filter-select" id="estFilterMetodoPago"><option value="">Método de Pago</option></select>
            <select class="filter-select" id="estFilterWeb"><option value="">Web</option></select>
        </div>
    </div>
</div>

<!-- Contenedor de gráficos -->
<div class="charts-container" id="chartsContainer">

    <!-- Fila 1: Tendencia (ancho completo) -->
    <div class="chart-card chart-full" id="chartCardTendencia">
        <div class="chart-header">
            <h4><i class="fas fa-chart-line"></i> Tendencia de Registros</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartTendencia"></canvas>
        </div>
    </div>

    <!-- Fila 2: Asesores + Delegados -->
    <div class="chart-card chart-half" id="chartCardAsesores">
        <div class="chart-header">
            <h4><i class="fas fa-headset"></i> Registros por Asesor</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartAsesores"></canvas>
        </div>
    </div>

    <div class="chart-card chart-half" id="chartCardDelegados">
        <div class="chart-header">
            <h4><i class="fas fa-user-tie"></i> Registros por Delegado</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartDelegados"></canvas>
        </div>
    </div>

    <!-- Fila 3: Cursos + Países -->
    <div class="chart-card chart-half" id="chartCardCursos">
        <div class="chart-header">
            <h4><i class="fas fa-graduation-cap"></i> Registros por Curso</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartCursos"></canvas>
        </div>
    </div>

    <div class="chart-card chart-half" id="chartCardPaises">
        <div class="chart-header">
            <h4><i class="fas fa-globe-americas"></i> Registros por País</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartPaises"></canvas>
        </div>
    </div>

    <!-- Fila 4: Método de Pago + Horas -->
    <div class="chart-card chart-half" id="chartCardMetodoPago">
        <div class="chart-header">
            <h4><i class="fas fa-credit-card"></i> Métodos de Pago</h4>
        </div>
        <div class="chart-body chart-body-dona">
            <canvas id="chartMetodoPago"></canvas>
        </div>
    </div>

    <div class="chart-card chart-half" id="chartCardHoras">
        <div class="chart-header">
            <h4><i class="fas fa-clock"></i> Registros por Hora del Día</h4>
        </div>
        <div class="chart-body">
            <canvas id="chartHoras"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
/* =====================================================
   TABS PROFESIONALES
   ===================================================== */
.est-tabs-container {
    background: var(--blanco);
    border-radius: var(--radius);
    margin-bottom: 12px;
    box-shadow: 0 1px 3px var(--sombra);
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.est-tabs-nav {
    display: flex;
    gap: 0;
    padding: 0;
    margin: 0;
    border-bottom: 2px solid #e5e7eb;
    align-items: center;
}
.est-tab-item {
    flex: 0 0 auto;
    padding: 14px 30px;
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    position: relative;
    border-bottom: 3px solid transparent;
}
.est-tab-item:hover { color: #07325A; background: rgba(7,50,90,0.02); }
.est-tab-item.active { color: #07325A; border-bottom-color: #07325A; background: rgba(7,50,90,0.03); }
.est-tab-item i { font-size: 13px; }
.est-tab-item.blocked { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

/* =====================================================
   CONTENIDO TAB + FILTROS
   ===================================================== */
.filters-bar { padding: 12px 16px; box-shadow: none; }
.est-tab-content { display: none; }
.est-tab-content.active { display: block; }

/* =====================================================
   GRÁFICOS
   ===================================================== */
.charts-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    overflow-y: auto;
    flex: 1;
}
.chart-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s ease;
}
.chart-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.chart-full  { width: 100%;             min-height: 320px; }
.chart-half  { width: calc(50% - 8px);  min-height: 300px; }
.chart-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}
.chart-header h4 { margin:0; font-size:13px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; }
.chart-header h4 i { color:#07325A; font-size:14px; }
.chart-body { padding:16px; flex:1; position:relative; min-height:400px; }
.chart-body-dona { display:flex; align-items:center; justify-content:center; max-height:300px; }
.chart-body-dona canvas { max-width:280px; max-height:280px; }
@media (max-width:900px) { .chart-half { width:100%; } }
</style>

<script>
(function () {
    'use strict';

    var COLORES = [
        '#07325A','#0ea5e9','#10b981','#f59e0b','#ef4444',
        '#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1',
        '#84cc16','#06b6d4','#d946ef','#0891b2','#dc2626'
    ];

    var STATE = {
        charts: {},
        debounceTimer: null,
        filterTimer: null,
        currentTab: 'general',
        permisosTimer: null,
        sesionInvalidada: false,
        filters: {
            general:  { fecha_desde: '', fecha_hasta: '', tendencia: 'dia', formulario_id: '' },
            asesor:   { asesor: '', fecha_desde: '', fecha_hasta: '', tendencia: 'dia' },
            delegado: { delegado: '', fecha_desde: '', fecha_hasta: '', tendencia: 'dia' }
        },
        subFilters: { curso: '', pais: '', ciudad: '', metodo_pago: '', web: '' }
    };

    var DOM = {};

    function cacheDom() {
        DOM.tabButtons  = document.querySelectorAll('.est-tab-item');
        DOM.tabContents = document.querySelectorAll('.est-tab-content');

        DOM.estFechaDesdeGeneral  = document.getElementById('estFechaDesdeGeneral');
        DOM.estFechaHastaGeneral  = document.getElementById('estFechaHastaGeneral');
        DOM.estTendenciaGeneral   = document.getElementById('estTendenciaGeneral');
        DOM.estFormularioGeneral  = document.getElementById('estFormularioGeneral');
        DOM.btnClearGeneral       = document.getElementById('estBtnClearGeneral');

        DOM.estAsesor             = document.getElementById('estAsesor');
        DOM.estFechaDesdeAsesor   = document.getElementById('estFechaDesdeAsesor');
        DOM.estFechaHastaAsesor   = document.getElementById('estFechaHastaAsesor');
        DOM.estTendenciaAsesor    = document.getElementById('estTendenciaAsesor');
        DOM.btnClearAsesor        = document.getElementById('estBtnClearAsesor');

        DOM.estDelegado           = document.getElementById('estDelegado');
        DOM.estFechaDescuDelegado = document.getElementById('estFechaDescuDelegado');
        DOM.estFechaHastaDelegado = document.getElementById('estFechaHastaDelegado');
        DOM.estTendenciaDelegado  = document.getElementById('estTendenciaDelegado');
        DOM.btnClearDelegado      = document.getElementById('estBtnClearDelegado');

        DOM.estFilterCurso        = document.getElementById('estFilterCurso');
        DOM.estFilterPais         = document.getElementById('estFilterPais');
        DOM.estFilterCiudad       = document.getElementById('estFilterCiudad');
        DOM.estFilterMetodoPago   = document.getElementById('estFilterMetodoPago');
        DOM.estFilterWeb          = document.getElementById('estFilterWeb');
    }

    function init() {
        cacheDom();
        cargarEstadisticas();
        cargarFiltros();
        bindEvents();
        // Permisos en tiempo real
        cargarYAplicarPermisos();
        STATE.permisosTimer = setInterval(cargarYAplicarPermisos, 5000);
    }

    // =====================================================
    // CAMBIAR TAB
    // =====================================================
    function cambiarTab(tabName) {
        Array.prototype.forEach.call(DOM.tabButtons, function (btn) { btn.classList.remove('active'); });
        Array.prototype.forEach.call(DOM.tabContents, function (c) { c.classList.remove('active'); c.style.display = 'none'; });
        STATE.currentTab = tabName;
        document.querySelector('[data-tab="' + tabName + '"]').classList.add('active');
        document.getElementById('tab-' + tabName).classList.add('active');
        document.getElementById('tab-' + tabName).style.display = 'block';
        limpiarFiltrosCompleto();
        cargarEstadisticas();
        cargarFiltros();
    }

    // =====================================================
    // LIMPIAR FILTROS
    // =====================================================
    function limpiarFiltrosCompleto() {
        if (STATE.currentTab === 'general') {
            STATE.filters.general = { fecha_desde: '', fecha_hasta: '', tendencia: 'dia', formulario_id: '' };
            if (DOM.estFechaDesdeGeneral)  DOM.estFechaDesdeGeneral.value  = '';
            if (DOM.estFechaHastaGeneral)  DOM.estFechaHastaGeneral.value  = '';
            if (DOM.estTendenciaGeneral)   DOM.estTendenciaGeneral.value   = 'dia';
            if (DOM.estFormularioGeneral)  { DOM.estFormularioGeneral.value = ''; DOM.estFormularioGeneral.classList.remove('active-filter'); }
        } else if (STATE.currentTab === 'asesor') {
            STATE.filters.asesor = { asesor: '', fecha_desde: '', fecha_hasta: '', tendencia: 'dia' };
            if (DOM.estAsesor)             { DOM.estAsesor.value = ''; DOM.estAsesor.classList.remove('active-filter'); }
            if (DOM.estFechaDesdeAsesor)   DOM.estFechaDesdeAsesor.value   = '';
            if (DOM.estFechaHastaAsesor)   DOM.estFechaHastaAsesor.value   = '';
            if (DOM.estTendenciaAsesor)    DOM.estTendenciaAsesor.value    = 'dia';
        } else if (STATE.currentTab === 'delegado') {
            STATE.filters.delegado = { delegado: '', fecha_desde: '', fecha_hasta: '', tendencia: 'dia' };
            if (DOM.estDelegado)           { DOM.estDelegado.value = ''; DOM.estDelegado.classList.remove('active-filter'); }
            if (DOM.estFechaDescuDelegado) DOM.estFechaDescuDelegado.value = '';
            if (DOM.estFechaHastaDelegado) DOM.estFechaHastaDelegado.value = '';
            if (DOM.estTendenciaDelegado)  DOM.estTendenciaDelegado.value  = 'dia';
        }
        STATE.subFilters = { curso: '', pais: '', ciudad: '', metodo_pago: '', web: '' };
        [DOM.estFilterCurso, DOM.estFilterPais, DOM.estFilterCiudad, DOM.estFilterMetodoPago, DOM.estFilterWeb].forEach(function (el) {
            if (el) { el.value = ''; el.classList.remove('active-filter'); }
        });
    }

    // =====================================================
    // CONSTRUIR PARÁMETROS
    // =====================================================
    function buildParams() {
        var p = { tab: STATE.currentTab };
        if (STATE.currentTab === 'general') {
            if (STATE.filters.general.formulario_id) p.formulario_id = STATE.filters.general.formulario_id;
            if (STATE.filters.general.fecha_desde)   p.fecha_desde   = STATE.filters.general.fecha_desde;
            if (STATE.filters.general.fecha_hasta)   p.fecha_hasta   = STATE.filters.general.fecha_hasta;
            if (STATE.filters.general.tendencia)     p.tendencia     = STATE.filters.general.tendencia;
        } else if (STATE.currentTab === 'asesor') {
            if (STATE.filters.asesor.asesor)         p.asesor        = STATE.filters.asesor.asesor;
            if (STATE.filters.asesor.fecha_desde)    p.fecha_desde   = STATE.filters.asesor.fecha_desde;
            if (STATE.filters.asesor.fecha_hasta)    p.fecha_hasta   = STATE.filters.asesor.fecha_hasta;
            if (STATE.filters.asesor.tendencia)      p.tendencia     = STATE.filters.asesor.tendencia;
        } else if (STATE.currentTab === 'delegado') {
            if (STATE.filters.delegado.delegado)     p.delegado      = STATE.filters.delegado.delegado;
            if (STATE.filters.delegado.fecha_desde)  p.fecha_desde   = STATE.filters.delegado.fecha_desde;
            if (STATE.filters.delegado.fecha_hasta)  p.fecha_hasta   = STATE.filters.delegado.fecha_hasta;
            if (STATE.filters.delegado.tendencia)    p.tendencia     = STATE.filters.delegado.tendencia;
        }
        if (STATE.subFilters.curso)        p.curso        = STATE.subFilters.curso;
        if (STATE.subFilters.pais)         p.pais         = STATE.subFilters.pais;
        if (STATE.subFilters.ciudad)       p.ciudad       = STATE.subFilters.ciudad;
        if (STATE.subFilters.metodo_pago)  p.metodo_pago  = STATE.subFilters.metodo_pago;
        if (STATE.subFilters.web)          p.web          = STATE.subFilters.web;
        return p;
    }

    // =====================================================
    // CARGAR FILTROS
    // =====================================================
    function cargarFiltros() {
        var params = buildParams();
        var qs = Object.keys(params).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(params[k]); }).join('&');
        fetch('includes/ajax/get_estadisticas.php?type=filtros&' + qs, { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data && data.success && data.filtros) {
                llenarSelect(DOM.estFormularioGeneral, data.filtros.formulario_id, 'Todos los Formularios');
                llenarSelect(DOM.estAsesor,            data.filtros.asesor,        'Seleccionar Asesor');
                llenarSelect(DOM.estDelegado,          data.filtros.delegado,      'Seleccionar Delegado');
                llenarSelect(DOM.estFilterCurso,       data.filtros.curso,         'Curso');
                llenarSelect(DOM.estFilterPais,        data.filtros.pais,          'País');
                llenarSelect(DOM.estFilterCiudad,      data.filtros.ciudad,        'Ciudad');
                llenarSelect(DOM.estFilterMetodoPago,  data.filtros.metodo_pago,   'Método de Pago');
                llenarSelect(DOM.estFilterWeb,         data.filtros.web,           'Web');
            }
        })
        .catch(function (err) { console.error('Error filtros:', err); });
    }

    function llenarSelect(el, valores, placeholder) {
        if (!el || !valores) return;
        var cv = el.value;
        var h = '<option value="">' + placeholder + '</option>';
        valores.forEach(function (v) { h += '<option value="' + esc(v) + '"' + (v === cv ? ' selected' : '') + '>' + esc(v) + '</option>'; });
        el.innerHTML = h;
        if (cv && !valores.includes(cv)) { el.value = ''; el.classList.remove('active-filter'); }
        else if (cv === '') { el.classList.remove('active-filter'); }
        else { el.classList.add('active-filter'); }
    }

    // =====================================================
    // CARGAR ESTADÍSTICAS
    // =====================================================
    function cargarEstadisticas() {
        var p = buildParams();
        var qs = Object.keys(p).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(p[k]); }).join('&');
        fetch('includes/ajax/get_estadisticas.php?' + qs, { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data && data.success) {
                actualizarResumen(data.resumen || {});
                renderCharts(data);
            }
        })
        .catch(function (err) { console.error('Error estadísticas:', err); });
    }

    function actualizarResumen(r) {
        document.getElementById('estTotal').textContent    = (r.total     || 0).toLocaleString();
        document.getElementById('estHoy').textContent      = (r.hoy       || 0).toLocaleString();
        document.getElementById('estSemana').textContent   = (r.semana    || 0).toLocaleString();
        document.getElementById('estMes').textContent      = (r.mes       || 0).toLocaleString();
        document.getElementById('estAsesores').textContent = (r.asesores  || 0).toLocaleString();
        // ── FIX: usar r.delegados no r.asesores ──
        document.getElementById('estDelegados').textContent= (r.delegados || 0).toLocaleString();
        document.getElementById('estCursos').textContent   = (r.cursos    || 0).toLocaleString();
        document.getElementById('estPaises').textContent   = (r.paises    || 0).toLocaleString();
    }

    // =====================================================
    // RENDER GRÁFICOS
    // =====================================================
    function renderCharts(data) {
        renderTendencia(data);
        renderBarras('chartAsesores',    data.por_asesor);
        renderBarras('chartDelegados',   data.por_delegado);
        renderBarras('chartCursos',      data.por_curso);
        renderBarras('chartPaises',      data.por_pais);
        renderDona('chartMetodoPago',    data.por_metodo_pago);
        renderHoras('chartHoras',        data.por_hora);
    }

    function renderTendencia(data) {
        var modo = STATE.filters[STATE.currentTab].tendencia || 'dia';
        var labels = [], valores = [], serie = [];
        if (modo === 'dia')       serie = data.por_dia      || [];
        else if (modo === 'semana')    serie = data.por_semana   || [];
        else if (modo === 'mes')       serie = data.por_mes      || [];
        else if (modo === 'bimestre')  serie = data.por_bimestre || [];
        else if (modo === 'trimestre') serie = data.por_trimestre|| [];
        else if (modo === 'semestre')  serie = data.por_semestre || [];
        else if (modo === 'anio')      serie = data.por_anio     || [];

        if (modo === 'dia')        serie.forEach(function (d) { labels.push(formatearFecha(d.dia));               valores.push(d.total); });
        else if (modo === 'semana')     serie.forEach(function (d) { labels.push('Sem ' + formatearFecha(d.inicio_semana)); valores.push(d.total); });
        else if (modo === 'mes')        serie.forEach(function (d) { labels.push(d.mes_nombre || d.periodo);      valores.push(d.total); });
        else                            serie.forEach(function (d) { labels.push(d.periodo);                      valores.push(d.total); });

        destroyChart('chartTendencia');
        var ctx = document.getElementById('chartTendencia').getContext('2d');
        STATE.charts['chartTendencia'] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros', data: valores,
                    borderColor: '#07325A', backgroundColor: 'rgba(7,50,90,0.1)',
                    borderWidth: 2.5, pointBackgroundColor: '#07325A', pointBorderColor: '#fff',
                    pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6, tension: 0.3, fill: true
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', titleFont:{size:12}, bodyFont:{size:11}, cornerRadius:8, padding:10 } },
                scales: {
                    x: { grid:{display:false}, ticks:{font:{size:10}, maxRotation:45, color:'#64748b'} },
                    y: { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{font:{size:10}, color:'#64748b', precision:0} }
                }
            }
        });
    }

    function renderBarras(id, datos) {
        datos = datos || [];
        var labels = [], valores = [];
        datos.forEach(function (d) { labels.push(d.nombre); valores.push(d.total); });
        destroyChart(id);
        var ctx = document.getElementById(id).getContext('2d');
        STATE.charts[id] = new Chart(ctx, {
            type: 'bar',
            data: { labels: labels, datasets: [{ label:'Registros', data:valores, backgroundColor: datos.map(function(d,i){return COLORES[i%COLORES.length];}), borderRadius:6, barThickness:18, maxBarThickness:24 }] },
            options: {
                responsive:true, maintainAspectRatio:false, indexAxis:'y',
                plugins: { legend:{display:false}, tooltip:{backgroundColor:'#1e293b', cornerRadius:8, padding:10, callbacks:{label:function(ctx){return ctx.parsed.x+' registros';}}} },
                scales: {
                    x: { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{font:{size:10}, color:'#64748b', precision:0} },
                    y: { grid:{display:false}, ticks:{font:{size:10}, color:'#374151', callback:function(val){var l=this.getLabelForValue(val); return l.length>25?l.substring(0,22)+'...':l;}}}
                }
            }
        });
    }

    function renderDona(id, datos) {
        datos = datos || [];
        var labels = [], valores = [];
        datos.forEach(function (d) { labels.push(d.nombre); valores.push(d.total); });
        destroyChart(id);
        var ctx = document.getElementById(id).getContext('2d');
        STATE.charts[id] = new Chart(ctx, {
            type: 'doughnut',
            data: { labels:labels, datasets:[{ data:valores, backgroundColor:datos.map(function(d,i){return COLORES[i%COLORES.length];}), borderWidth:2, borderColor:'#fff', hoverOffset:8 }] },
            options: {
                responsive:true, maintainAspectRatio:true, cutout:'55%',
                plugins: {
                    legend:{position:'bottom', labels:{padding:12, usePointStyle:true, pointStyle:'circle', font:{size:10}, color:'#374151'}},
                    tooltip:{backgroundColor:'#1e293b', cornerRadius:8, padding:10, callbacks:{label:function(ctx){var t=ctx.dataset.data.reduce(function(a,b){return a+b;},0); var p=t>0?((ctx.parsed/t)*100).toFixed(1):0; return ctx.label+': '+ctx.parsed+' ('+p+'%)';}}}
                }
            }
        });
    }

    function renderHoras(id, datos) {
        datos = datos || [];
        var horasData = new Array(24).fill(0);
        datos.forEach(function (d) { var h = parseInt(d.hora_num); if (h >= 0 && h < 24) horasData[h] = d.total; });
        var labels = [];
        for (var i = 0; i < 24; i++) labels.push((i < 10 ? '0' : '') + i + ':00');
        var coloresHora = horasData.map(function (v, i) {
            if (i >= 6  && i < 12) return '#f59e0b';
            if (i >= 12 && i < 18) return '#07325A';
            if (i >= 18 && i < 22) return '#0ea5e9';
            return '#64748b';
        });
        destroyChart(id);
        var ctx = document.getElementById(id).getContext('2d');
        STATE.charts[id] = new Chart(ctx, {
            type: 'bar',
            data: { labels:labels, datasets:[{ label:'Registros', data:horasData, backgroundColor:coloresHora, borderRadius:4, barThickness:14 }] },
            options: {
                responsive:true, maintainAspectRatio:false,
                plugins: { legend:{display:false}, tooltip:{backgroundColor:'#1e293b', cornerRadius:8, padding:10, callbacks:{title:function(items){return items[0].label+' hrs';}, label:function(ctx){return ctx.parsed.y+' registros';}}} },
                scales: {
                    x: { grid:{display:false}, ticks:{font:{size:9}, color:'#64748b', maxRotation:45} },
                    y: { beginAtZero:true, grid:{color:'#f1f5f9'}, ticks:{font:{size:10}, color:'#64748b', precision:0} }
                }
            }
        });
    }

    function destroyChart(id) { if (STATE.charts[id]) { STATE.charts[id].destroy(); STATE.charts[id] = null; } }

    function esc(t) { if (t === null || t === undefined) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; }

    function formatearFecha(fecha) { if (!fecha) return ''; var p = fecha.split('-'); if (p.length !== 3) return fecha; return p[2] + '/' + p[1] + '/' + p[0]; }

    // =====================================================
    // EVENTOS
    // =====================================================
    function bindEvents() {
        Array.prototype.forEach.call(DOM.tabButtons, function (btn) {
            btn.addEventListener('click', function () { var tab = this.getAttribute('data-tab'); if (tab === STATE.currentTab) return; cambiarTab(tab); });
        });

        [DOM.estFechaDesdeGeneral, DOM.estFechaHastaGeneral, DOM.estTendenciaGeneral, DOM.estFormularioGeneral].forEach(function (el) {
            if (!el) return;
            el.addEventListener('change', function () {
                if (el === DOM.estFechaDesdeGeneral)  STATE.filters.general.fecha_desde    = el.value;
                else if (el === DOM.estFechaHastaGeneral) STATE.filters.general.fecha_hasta = el.value;
                else if (el === DOM.estTendenciaGeneral)  STATE.filters.general.tendencia   = el.value;
                else if (el === DOM.estFormularioGeneral) STATE.filters.general.formulario_id = el.value;
                marcarFiltroActivo(el); recargar();
            });
        });

        [DOM.estAsesor, DOM.estFechaDesdeAsesor, DOM.estFechaHastaAsesor, DOM.estTendenciaAsesor].forEach(function (el) {
            if (!el) return;
            el.addEventListener('change', function () {
                if (el === DOM.estAsesor)             STATE.filters.asesor.asesor        = el.value;
                else if (el === DOM.estFechaDesdeAsesor) STATE.filters.asesor.fecha_desde = el.value;
                else if (el === DOM.estFechaHastaAsesor) STATE.filters.asesor.fecha_hasta = el.value;
                else if (el === DOM.estTendenciaAsesor)  STATE.filters.asesor.tendencia   = el.value;
                marcarFiltroActivo(el); recargar();
            });
        });

        [DOM.estDelegado, DOM.estFechaDescuDelegado, DOM.estFechaHastaDelegado, DOM.estTendenciaDelegado].forEach(function (el) {
            if (!el) return;
            el.addEventListener('change', function () {
                if (el === DOM.estDelegado)               STATE.filters.delegado.delegado    = el.value;
                else if (el === DOM.estFechaDescuDelegado) STATE.filters.delegado.fecha_desde = el.value;
                else if (el === DOM.estFechaHastaDelegado) STATE.filters.delegado.fecha_hasta = el.value;
                else if (el === DOM.estTendenciaDelegado)  STATE.filters.delegado.tendencia   = el.value;
                marcarFiltroActivo(el); recargar();
            });
        });

        [DOM.estFilterCurso, DOM.estFilterPais, DOM.estFilterCiudad, DOM.estFilterMetodoPago, DOM.estFilterWeb].forEach(function (el) {
            if (!el) return;
            el.addEventListener('change', function () {
                if (el === DOM.estFilterCurso)       STATE.subFilters.curso       = el.value;
                else if (el === DOM.estFilterPais)       STATE.subFilters.pais        = el.value;
                else if (el === DOM.estFilterCiudad)     STATE.subFilters.ciudad      = el.value;
                else if (el === DOM.estFilterMetodoPago) STATE.subFilters.metodo_pago = el.value;
                else if (el === DOM.estFilterWeb)        STATE.subFilters.web         = el.value;
                marcarFiltroActivo(el); recargar();
            });
        });

        [DOM.btnClearGeneral, DOM.btnClearAsesor, DOM.btnClearDelegado].forEach(function (btn) {
            if (btn) btn.addEventListener('click', function () { limpiarFiltrosCompleto(); cargarEstadisticas(); cargarFiltros(); });
        });
    }

    function marcarFiltroActivo(el) { if (el.tagName === 'SELECT') el.classList.toggle('active-filter', el.value !== ''); }

    function recargar() {
        clearTimeout(STATE.debounceTimer); clearTimeout(STATE.filterTimer);
        STATE.debounceTimer = setTimeout(function () { cargarEstadisticas(); }, 200);
        STATE.filterTimer   = setTimeout(function () { cargarFiltros(); },      250);
    }

    // =====================================================
    // PERMISOS EN TIEMPO REAL
    // =====================================================
    function expulsarSesion(mensaje) {
        if (STATE.sesionInvalidada) return;
        STATE.sesionInvalidada = true;
        if (STATE.permisosTimer) clearInterval(STATE.permisosTimer);
        if (typeof mostrarToast === 'function') mostrarToast(mensaje || 'Tu sesión ha sido cerrada.', 'error', 4000);
        var overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:99999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px';
        overlay.innerHTML = '<i class="fas fa-lock" style="font-size:48px;color:#fff;"></i>' +
            '<p style="color:#fff;font-size:16px;font-weight:600;margin:0;text-align:center;">' + (mensaje || 'Tu sesión ha sido cerrada.') + '</p>' +
            '<p style="color:rgba(255,255,255,0.7);font-size:13px;margin:0;">Redirigiendo al inicio de sesión...</p>';
        document.body.appendChild(overlay);
        setTimeout(function () { window.location.href = 'index.php?session=expired'; }, 3000);
    }

    function setVisible(id, visible) {
        var el = document.getElementById(id);
        if (el) el.style.display = visible ? '' : 'none';
    }

    function setVisibleEl(el, visible) {
        if (el) el.style.display = visible ? '' : 'none';
    }

    function cargarYAplicarPermisos() {
        if (STATE.sesionInvalidada) return;
        fetch('includes/ajax/get_permisos_usuario.php', { credentials: 'same-origin' })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data.session_invalida === true) { expulsarSesion(data.message || 'Tu cuenta ya no existe.'); return; }
            if (!data.success) return;
            if (data.es_admin) return;

            var p  = data.permisos;
            var es = p.estadisticas || {};

            // ── 1. ACCESO COMPLETO ──
            var tieneAcceso = (es.acceso_estadisticas !== false);
            var chartsContainer = document.getElementById('chartsContainer');
            var tabsContainer   = document.querySelector('.est-tabs-container');
            if (!tieneAcceso) {
                [chartsContainer, tabsContainer].forEach(function(el){
                    if (el) { el.style.pointerEvents='none'; el.style.opacity='0.3'; el.style.filter='blur(2px)'; }
                });
                if (!document.getElementById('estAccesoBloqueado')) {
                    var blk = document.createElement('div');
                    blk.id = 'estAccesoBloqueado';
                    blk.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;';
                    blk.innerHTML = '<i class="fas fa-chart-bar" style="font-size:48px;color:#fff;opacity:0.7;"></i>' +
                        '<p style="color:#fff;font-size:15px;font-weight:700;margin:0;">Sin acceso a Estadísticas</p>' +
                        '<p style="color:rgba(255,255,255,0.65);font-size:12px;margin:0;">Contacta al administrador para obtener permiso.</p>';
                    document.body.appendChild(blk);
                }
                return;
            } else {
                [chartsContainer, tabsContainer].forEach(function(el){
                    if (el) { el.style.pointerEvents=''; el.style.opacity=''; el.style.filter=''; }
                });
                var blk2 = document.getElementById('estAccesoBloqueado');
                if (blk2) blk2.remove();
            }

            // ── 2. PESTAÑAS INDIVIDUALES ──
            // Cada pestaña se controla por separado con su propio permiso
            var tabPermisos = {
                'tab_general':  'estTabGeneral',
                'tab_asesor':   'estTabAsesor',
                'tab_delegado': 'estTabDelegado'
            };
            Object.keys(tabPermisos).forEach(function(permKey){
                var visible = (es[permKey] !== false);
                setVisible(tabPermisos[permKey], visible);
                // Si la pestaña activa se oculta, redirigir a la primera visible
                if (!visible && STATE.currentTab === permKey.replace('tab_','')) {
                    // Buscar la primera tab visible y activarla
                    var tabs = ['general','asesor','delegado'];
                    for (var i=0; i<tabs.length; i++) {
                        var tpk = 'tab_' + tabs[i];
                        if (es[tpk] !== false) { cambiarTab(tabs[i]); break; }
                    }
                }
            });

            // ── 3. FILTRO FORMULARIO / SELECTOR PRINCIPAL ──
            var mostrarFormulario = (es.filtro_formulario !== false);
            setVisible('estLabelFormulario',  mostrarFormulario);
            setVisibleEl(DOM.estFormularioGeneral, mostrarFormulario);
            setVisible('estLabelAsesor',      mostrarFormulario);
            setVisibleEl(DOM.estAsesor,       mostrarFormulario);
            setVisible('estLabelDelegado',    mostrarFormulario);
            setVisibleEl(DOM.estDelegado,     mostrarFormulario);

            // ── 4. FILTRO FECHA (incluye separadores "a") ──
            var mostrarFecha = (es.filtro_fecha_hora !== false);
            setVisible('estLabelFechaGeneral',     mostrarFecha);
            setVisibleEl(DOM.estFechaDesdeGeneral, mostrarFecha);
            setVisible('estFechaSepGeneral',       mostrarFecha);
            setVisibleEl(DOM.estFechaHastaGeneral, mostrarFecha);
            setVisible('estLabelFechaAsesor',      mostrarFecha);
            setVisibleEl(DOM.estFechaDesdeAsesor,  mostrarFecha);
            setVisible('estFechaSepAsesor',        mostrarFecha);
            setVisibleEl(DOM.estFechaHastaAsesor,  mostrarFecha);
            setVisible('estLabelFechaDelegado',    mostrarFecha);
            setVisibleEl(DOM.estFechaDescuDelegado,mostrarFecha);
            setVisible('estFechaSepDelegado',      mostrarFecha);
            setVisibleEl(DOM.estFechaHastaDelegado,mostrarFecha);

            // ── 5. FILTRO TENDENCIA ── (usa clases .est-tendencia-label / .est-tendencia-select)
            var mostrarTendencia = (es.filtro_tendencia !== false);
            document.querySelectorAll('.est-tendencia-label').forEach(function(el){ el.style.display = mostrarTendencia ? '' : 'none'; });
            document.querySelectorAll('.est-tendencia-select').forEach(function(el){ el.style.display = mostrarTendencia ? '' : 'none'; });

            // ── 6. BOTÓN LIMPIAR ──
            var mostrarLimpiar = (es.filtro_limpiar !== false);
            setVisible('estBtnClearGeneral',  mostrarLimpiar);
            setVisible('estBtnClearAsesor',   mostrarLimpiar);
            setVisible('estBtnClearDelegado', mostrarLimpiar);

            // ── 7. SUB-FILTROS ──
            var subFiltrosMap = {
                'filtro_curso':       'estFilterCurso',
                'filtro_pais':        'estFilterPais',
                'filtro_ciudad':      'estFilterCiudad',
                'filtro_metodo_pago': 'estFilterMetodoPago',
                'filtro_web':         'estFilterWeb'
            };
            var hayAlgunSubFiltro = false;
            Object.keys(subFiltrosMap).forEach(function(permKey){
                var elId = subFiltrosMap[permKey];
                var visible = (es[permKey] !== false);
                setVisible(elId, visible);
                if (visible) hayAlgunSubFiltro = true;
            });
            setVisible('estSubFiltrosRow',   hayAlgunSubFiltro);
            setVisible('estSubFiltrosLabel', hayAlgunSubFiltro);

            // ── 8. GRÁFICOS VISIBLES ──
            var graficosMap = {
                'grafico_tendencia':   'chartCardTendencia',
                'grafico_asesores':    'chartCardAsesores',
                'grafico_delegados':   'chartCardDelegados',
                'grafico_cursos':      'chartCardCursos',
                'grafico_paises':      'chartCardPaises',
                'grafico_metodo_pago': 'chartCardMetodoPago',
                'grafico_horas':       'chartCardHoras'
            };
            Object.keys(graficosMap).forEach(function(permKey){
                setVisible(graficosMap[permKey], es[permKey] !== false);
            });
        })
        .catch(function(err){ console.error('Error permisos estadísticas:', err); });
    }

    window.addEventListener('beforeunload', function(){
        if (STATE.permisosTimer) clearInterval(STATE.permisosTimer);
    });

    init();
})();
</script>
