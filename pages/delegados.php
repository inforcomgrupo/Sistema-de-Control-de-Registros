<?php
/**
 * Página: Delegados
 * Vista filtrada de registros que tienen Delegado asignado
 */
if (!defined('SISTEMA_REGISTROS')) {
    define('SISTEMA_REGISTROS', true);
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/app.php';
    require_once __DIR__ . '/../includes/auth.php';
}
?>

<!-- Estadísticas compactas -->
<div class="stats-bar" id="statsBarDelegado" style="flex-shrink:0;">
    <div class="stat-card stat-total">
        <div class="stat-icon"><i class="fas fa-database"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statTotalDelegado">0</span>
            <span class="stat-label">Total</span>
        </div>
    </div>
    <div class="stat-card stat-today">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statHoyDelegado">0</span>
            <span class="stat-label">Hoy</span>
        </div>
    </div>
    <div class="stat-card stat-week">
        <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statSemanaDelegado">0</span>
            <span class="stat-label">Semana</span>
        </div>
    </div>
    <div class="stat-card stat-month">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statMesDelegado">0</span>
            <span class="stat-label">Mes</span>
        </div>
    </div>
    <div class="stat-card stat-delegados">
        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statDelegadosCount">0</span>
            <span class="stat-label">Delegados</span>
        </div>
    </div>
    <div class="stat-card stat-cursos">
        <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statCursosDelegado">0</span>
            <span class="stat-label">Cursos</span>
        </div>
    </div>
    <div class="stat-card stat-paises">
        <div class="stat-icon"><i class="fas fa-globe-americas"></i></div>
        <div class="stat-info">
            <span class="stat-value" id="statPaisesDelegado">0</span>
            <span class="stat-label">Países</span>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filters-bar" id="filtersBarDelegado" style="flex-shrink:0;">
    <div class="filters-row" id="delegadoFiltersRow1">
        <span class="filters-row-label" id="delegadoFiltroDelegadoLabel"><i class="fas fa-user-tie"></i> Delegados:</span>
        <select class="filter-select" id="delegadoFilterDelegado" title="Delegado">
            <option value="">Todos los Delegados</option>
        </select>

        <div class="filter-search-wrapper" id="delegadoFiltroBusquedaWrapper">
            <i class="fas fa-search"></i>
            <input type="text" class="filter-search" id="delegadoFilterSearch" placeholder="Buscar en todos los campos...">
        </div>

        <div class="records-counter" id="delegadoFiltroMostrandoWrapper">
            <i class="fas fa-database"></i>
            <strong id="delegadoCountFiltered">0</strong> de <strong id="delegadoCountTotal">0</strong>
            <span class="filter-separator">|</span>
            <select class="filter-page-size" id="delegadoFilterPageSize">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="0">Todos</option>
            </select>
        </div>

        <button class="btn-filter-action btn-clear-filters" id="delegadoBtnClear">
            <i class="fas fa-eraser"></i> Limpiar
        </button>
        <button class="btn-filter-action btn-export-excel" id="delegadoBtnExcel">
            <i class="fas fa-file-excel"></i> Excel
        </button>
    </div>

    <div class="filters-row" id="delegadoFiltersRow2">
        <span class="filters-row-label"><i class="fas fa-calendar"></i> Fecha:</span>
        <input type="date" class="filter-date-input" id="delegadoFilterFechaDesde">
        <span class="filter-separator">a</span>
        <input type="date" class="filter-date-input" id="delegadoFilterFechaHasta">
        <span class="filters-row-label" style="margin-left:8px;"><i class="fas fa-clock"></i> Hora:</span>
        <div class="time-picker-wrapper">
            <select class="filter-time-select" id="delegadoFilterHoraDesdeH"><option value="">hh</option></select>
            <span class="time-sep">:</span>
            <select class="filter-time-select" id="delegadoFilterHoraDesdeM"><option value="">mm</option></select>
        </div>
        <span class="filter-separator">a</span>
        <div class="time-picker-wrapper">
            <select class="filter-time-select" id="delegadoFilterHoraHastaH"><option value="">hh</option></select>
            <span class="time-sep">:</span>
            <select class="filter-time-select" id="delegadoFilterHoraHastaM"><option value="">mm</option></select>
        </div>
    </div>

    <div class="filters-row">
        <select class="filter-select" id="delegadoFilterCurso"><option value="">Curso</option></select>
        <select class="filter-select" id="delegadoFilterPais"><option value="">País</option></select>
        <select class="filter-select" id="delegadoFilterCiudad"><option value="">Ciudad</option></select>
        <select class="filter-select" id="delegadoFilterMoneda"><option value="">Moneda</option></select>
        <select class="filter-select" id="delegadoFilterMetodoPago"><option value="">Método de Pago</option></select>
        <select class="filter-select" id="delegadoFilterWeb"><option value="">Web</option></select>
    </div>
</div>

<!-- Tabla -->
<div class="table-container">
    <div class="table-scroll" id="delegadoTableScroll">
        <table class="data-table" id="delegadoDataTable">
            <thead>
                <tr id="delegadoTableHeaders"></tr>
            </thead>
            <tbody id="delegadoTableBody"></tbody>
        </table>
        <div class="table-loader" id="delegadoTableLoader"><div class="mini-spinner"></div></div>
        <div class="no-results" id="delegadoNoResults" style="display:none;">
            <i class="fas fa-inbox"></i>
            <p>No se encontraron registros</p>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    var VISTA_TIPO = 'delegado';
    var PREFIX = 'delegado';
    var CONFIG = { POLL_INTERVAL: 3000, PAGE_SIZE: 50, DEBOUNCE_DELAY: 300 };
    var STATE = {
        registros: [], offset: 0, hasMore: true, isLoading: false,
        isPollActive: true, lastId: 0, totalFiltered: 0, totalGeneral: 0,
        sortColumn: 'fecha_registro', sortDir: 'DESC', searchTimer: null,
        pollTimer: null, camposDinamicos: [], editingCell: null, requestId: 0,
        permisosTimer: null, sesionInvalidada: false, reordenarPermitido: true
    };

    var COLUMNAS = [
        { key: 'nombre',        label: 'Nombre',         sortable: true },
        { key: 'apellidos',     label: 'Apellidos',      sortable: true },
        { key: 'telefono',      label: 'Teléfono',       sortable: true, type: 'whatsapp' },
        { key: 'correo',        label: 'Correo',         sortable: true },
        { key: 'delegado',      label: 'Delegado',       sortable: true },
        { key: 'curso',         label: 'Curso',          sortable: true },
        { key: 'pais',          label: 'País',           sortable: true },
        { key: 'ciudad',        label: 'Ciudad',         sortable: true },
        { key: 'moneda',        label: 'Moneda',         sortable: true },
        { key: 'metodo_pago',   label: 'Método de Pago', sortable: true },
        { key: 'ip',            label: 'IP',             sortable: true },
        { key: 'fecha',         label: 'Fecha',          sortable: true },
        { key: 'hora',          label: 'Hora',           sortable: true },
        { key: 'categoria',     label: 'Categoría',      sortable: true },
        { key: 'file_url',      label: 'File',           sortable: false, type: 'file' },
        { key: 'formulario_id', label: 'ID',             sortable: true },
        { key: 'web',           label: 'Web',            sortable: true }
    ];

    var DOM = {};

    function cacheDom() {
        DOM.tableHeaders    = document.getElementById(PREFIX + 'TableHeaders');
        DOM.tableBody       = document.getElementById(PREFIX + 'TableBody');
        DOM.tableScroll     = document.getElementById(PREFIX + 'TableScroll');
        DOM.tableLoader     = document.getElementById(PREFIX + 'TableLoader');
        DOM.noResults       = document.getElementById(PREFIX + 'NoResults');
        DOM.countFiltered   = document.getElementById(PREFIX + 'CountFiltered');
        DOM.countTotal      = document.getElementById(PREFIX + 'CountTotal');
        DOM.filterSearch    = document.getElementById(PREFIX + 'FilterSearch');
        DOM.filterPrincipal = document.getElementById(PREFIX + 'FilterDelegado');
        DOM.filterCurso     = document.getElementById(PREFIX + 'FilterCurso');
        DOM.filterPais      = document.getElementById(PREFIX + 'FilterPais');
        DOM.filterCiudad    = document.getElementById(PREFIX + 'FilterCiudad');
        DOM.filterMoneda    = document.getElementById(PREFIX + 'FilterMoneda');
        DOM.filterMetodoPago= document.getElementById(PREFIX + 'FilterMetodoPago');
        DOM.filterWeb       = document.getElementById(PREFIX + 'FilterWeb');
        DOM.filterFechaDesde= document.getElementById(PREFIX + 'FilterFechaDesde');
        DOM.filterFechaHasta= document.getElementById(PREFIX + 'FilterFechaHasta');
        DOM.filterHoraDesdeH= document.getElementById(PREFIX + 'FilterHoraDesdeH');
        DOM.filterHoraDesdeM= document.getElementById(PREFIX + 'FilterHoraDesdeM');
        DOM.filterHoraHastaH= document.getElementById(PREFIX + 'FilterHoraHastaH');
        DOM.filterHoraHastaM= document.getElementById(PREFIX + 'FilterHoraHastaM');
        DOM.filterPageSize  = document.getElementById(PREFIX + 'FilterPageSize');
        DOM.btnClear        = document.getElementById(PREFIX + 'BtnClear');
        DOM.btnExcel        = document.getElementById(PREFIX + 'BtnExcel');
    }

    function init() {
        cacheDom();
        if (!DOM.tableHeaders || !DOM.tableBody) return;
        llenarSelectsHora();
        renderHeaders();
        cargarFiltros();
        cargarRegistros(true);
        bindEvents();
        iniciarPolling();
        // Permisos en tiempo real
        cargarYAplicarPermisos();
        STATE.permisosTimer = setInterval(cargarYAplicarPermisos, 5000);
    }

    function formatearFecha(fecha) {
        if (!fecha) return '';
        var partes = fecha.split('-');
        if (partes.length !== 3) return fecha;
        return partes[2] + '/' + partes[1] + '/' + partes[0];
    }

    function llenarSelectsHora() {
        [DOM.filterHoraDesdeH, DOM.filterHoraHastaH].forEach(function (sel) {
            if (!sel) return;
            var h = '<option value="">hh</option>';
            for (var i = 0; i < 24; i++) { var x = i < 10 ? '0' + i : '' + i; h += '<option value="' + x + '">' + x + '</option>'; }
            sel.innerHTML = h;
        });
        [DOM.filterHoraDesdeM, DOM.filterHoraHastaM].forEach(function (sel) {
            if (!sel) return;
            var h = '<option value="">mm</option>';
            for (var i = 0; i < 60; i += 5) { var x = i < 10 ? '0' + i : '' + i; h += '<option value="' + x + '">' + x + '</option>'; }
            sel.innerHTML = h;
        });
    }

    function getHoraDesde() { var h = DOM.filterHoraDesdeH ? DOM.filterHoraDesdeH.value : '', m = DOM.filterHoraDesdeM ? DOM.filterHoraDesdeM.value : ''; return h === '' ? '' : h + ':' + (m || '00') + ':00'; }
    function getHoraHasta() { var h = DOM.filterHoraHastaH ? DOM.filterHoraHastaH.value : '', m = DOM.filterHoraHastaM ? DOM.filterHoraHastaM.value : ''; return h === '' ? '' : h + ':' + (m || '59') + ':59'; }

    // =====================================================
    // HEADERS
    // =====================================================
    function renderHeaders() {
        var html = '';
        COLUMNAS.forEach(function (col) {
            var esSortable = col.sortable && STATE.reordenarPermitido;
            var sc = '', si = '<i class="fas fa-sort sort-icon"></i>';
            if (esSortable && col.key === STATE.sortColumn) {
                sc = STATE.sortDir === 'ASC' ? 'sort-asc' : 'sort-desc';
                si = STATE.sortDir === 'ASC' ? '<i class="fas fa-sort-up sort-icon"></i>' : '<i class="fas fa-sort-down sort-icon"></i>';
            }
            var noSortClass = esSortable ? '' : ' no-sort';
            html += '<th class="' + sc + noSortClass + '" data-column="' + col.key + '" data-sortable="' + (esSortable ? 'true' : 'false') + '">' + col.label + (esSortable ? ' ' + si : '') + '</th>';
        });
        DOM.tableHeaders.innerHTML = html;
    }

    function getPageSize() { if (!DOM.filterPageSize) return CONFIG.PAGE_SIZE; var v = parseInt(DOM.filterPageSize.value); return v === 0 ? 99999 : v; }

    function actualizarStats(s) {
        document.getElementById('statTotalDelegado').textContent    = (s.total     || 0).toLocaleString();
        document.getElementById('statHoyDelegado').textContent      = (s.hoy       || 0).toLocaleString();
        document.getElementById('statSemanaDelegado').textContent   = (s.semana    || 0).toLocaleString();
        document.getElementById('statMesDelegado').textContent      = (s.mes       || 0).toLocaleString();
        document.getElementById('statDelegadosCount').textContent   = (s.delegados || 0).toLocaleString();
        document.getElementById('statCursosDelegado').textContent   = (s.cursos    || 0).toLocaleString();
        document.getElementById('statPaisesDelegado').textContent   = (s.paises    || 0).toLocaleString();
    }

    function buildFilterParams() {
        var p = { vista_tipo: VISTA_TIPO };
        if (DOM.filterSearch     && DOM.filterSearch.value.trim() !== '')  p.search      = DOM.filterSearch.value.trim();
        if (DOM.filterPrincipal  && DOM.filterPrincipal.value !== '')       p.delegado    = DOM.filterPrincipal.value;
        if (DOM.filterCurso      && DOM.filterCurso.value !== '')           p.curso       = DOM.filterCurso.value;
        if (DOM.filterPais       && DOM.filterPais.value !== '')            p.pais        = DOM.filterPais.value;
        if (DOM.filterCiudad     && DOM.filterCiudad.value !== '')          p.ciudad      = DOM.filterCiudad.value;
        if (DOM.filterMoneda     && DOM.filterMoneda.value !== '')          p.moneda      = DOM.filterMoneda.value;
        if (DOM.filterMetodoPago && DOM.filterMetodoPago.value !== '')      p.metodo_pago = DOM.filterMetodoPago.value;
        if (DOM.filterWeb        && DOM.filterWeb.value !== '')             p.web         = DOM.filterWeb.value;
        if (DOM.filterFechaDesde && DOM.filterFechaDesde.value !== '')      p.fecha_desde = DOM.filterFechaDesde.value;
        if (DOM.filterFechaHasta && DOM.filterFechaHasta.value !== '')      p.fecha_hasta = DOM.filterFechaHasta.value;
        var hd = getHoraDesde(), hh = getHoraHasta();
        if (hd !== '') p.hora_desde = hd; if (hh !== '') p.hora_hasta = hh;
        return p;
    }

    function hayFiltrosActivos() { var p = buildFilterParams(); delete p.vista_tipo; return Object.keys(p).length > 0; }

    function cargarFiltros() {
        var params = buildFilterParams();
        var qs = Object.keys(params).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(params[k]); }).join('&');
        fetch('includes/ajax/get_filtros.php?' + qs, { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                llenarSelect(DOM.filterPrincipal,  data.filtros.delegado,     'Todos los Delegados');
                llenarSelect(DOM.filterCurso,      data.filtros.curso,        'Curso');
                llenarSelect(DOM.filterPais,       data.filtros.pais,         'País');
                llenarSelect(DOM.filterCiudad,     data.filtros.ciudad,       'Ciudad');
                llenarSelect(DOM.filterMoneda,     data.filtros.moneda,       'Moneda');
                llenarSelect(DOM.filterMetodoPago, data.filtros.metodo_pago,  'Método de Pago');
                llenarSelect(DOM.filterWeb,        data.filtros.web,          'Web');
                if (data.stats) actualizarStats(data.stats);
            }
        }).catch(function (err) { console.error(err); });
    }

    function llenarSelect(el, v, ph) {
        if (!el || !v) return;
        var cv = el.value;
        var h = '<option value="">' + ph + '</option>';
        v.forEach(function (x) { h += '<option value="' + escapeHtml(x) + '"' + (x === cv ? ' selected' : '') + '>' + escapeHtml(x) + '</option>'; });
        el.innerHTML = h;
        if (cv && !v.includes(cv)) { el.value = ''; el.classList.remove('active-filter'); }
    }

    // =====================================================
    // CARGAR REGISTROS
    // =====================================================
    function cargarRegistros(reset) {
        var ps = getPageSize();
        if (reset) {
            STATE.requestId++; var cr = STATE.requestId;
            STATE.offset = 0; STATE.registros = []; STATE.hasMore = true; STATE.lastId = 0;
            DOM.tableBody.style.opacity = '0.5'; DOM.tableBody.style.pointerEvents = 'none'; DOM.tableBody.style.transition = 'opacity 0.15s ease';
            var p = buildFilterParams(); p.offset = 0; p.limit = ps; p.sort_column = STATE.sortColumn; p.sort_dir = STATE.sortDir;
            var q = Object.keys(p).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(p[k]); }).join('&');
            fetch('includes/ajax/get_registros.php?' + q, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (cr !== STATE.requestId) return;
                if (d.success) {
                    STATE.totalFiltered = d.total_filtered; STATE.totalGeneral = d.total_general;
                    STATE.hasMore = d.has_more; STATE.camposDinamicos = d.campos_dinamicos || []; STATE.registros = [];
                    renderHeaders();
                    if (d.registros.length === 0) { DOM.tableBody.innerHTML = ''; DOM.tableBody.style.opacity = '1'; DOM.tableBody.style.pointerEvents = ''; DOM.noResults.style.display = 'block'; updateCounters(); return; }
                    DOM.noResults.style.display = 'none';
                    var f = document.createDocumentFragment();
                    d.registros.forEach(function (r) { STATE.registros.push(r); f.appendChild(crearFila(r, false)); if (r.id > STATE.lastId) STATE.lastId = r.id; });
                    DOM.tableBody.innerHTML = ''; DOM.tableBody.appendChild(f); DOM.tableBody.style.opacity = '1'; DOM.tableBody.style.pointerEvents = ''; STATE.offset = d.registros.length; updateCounters();
                }
            }).catch(function () { if (cr !== STATE.requestId) return; DOM.tableBody.style.opacity = '1'; DOM.tableBody.style.pointerEvents = ''; });
        } else {
            if (STATE.isLoading || !STATE.hasMore) return; STATE.isLoading = true;
            if (DOM.tableLoader) DOM.tableLoader.classList.add('active');
            var p2 = buildFilterParams(); p2.offset = STATE.offset; p2.limit = ps; p2.sort_column = STATE.sortColumn; p2.sort_dir = STATE.sortDir;
            var q2 = Object.keys(p2).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(p2[k]); }).join('&');
            fetch('includes/ajax/get_registros.php?' + q2, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                STATE.isLoading = false; if (DOM.tableLoader) DOM.tableLoader.classList.remove('active');
                if (d.success) {
                    STATE.hasMore = d.has_more; STATE.totalFiltered = d.total_filtered; STATE.totalGeneral = d.total_general;
                    var f = document.createDocumentFragment();
                    d.registros.forEach(function (r) { STATE.registros.push(r); f.appendChild(crearFila(r, false)); if (r.id > STATE.lastId) STATE.lastId = r.id; });
                    DOM.tableBody.appendChild(f); STATE.offset += d.registros.length; updateCounters();
                }
            }).catch(function () { STATE.isLoading = false; if (DOM.tableLoader) DOM.tableLoader.classList.remove('active'); });
        }
    }

    // =====================================================
    // CREAR FILA
    // =====================================================
    function crearFila(reg, isNew) {
        var tr = document.createElement('tr'); tr.setAttribute('data-id', reg.id); if (isNew) tr.classList.add('new-row');
        var html = '';
        COLUMNAS.forEach(function (c) {
            var v = reg[c.key], e = (v === null || v === '' || v === undefined);
            if (c.type === 'whatsapp') html += cellWhatsApp(reg.id, c.key, v, e);
            else if (c.type === 'file') html += cellFile(v, e);
            else html += cellEditable(reg.id, c.key, v, e);
        });
        tr.innerHTML = html; return tr;
    }

    function cellEditable(id, c, v, e) {
        var displayVal = v;
        if (c === 'fecha' && v) displayVal = formatearFecha(v);
        var d = e ? '<span class="cell-empty">—</span>' : escapeHtml(displayVal);
        return '<td><div class="cell-content"><span class="cell-text" data-reg-id="' + id + '" data-campo="' + c + '">' + d + '</span><button class="edit-btn" data-id="' + id + '" data-campo="' + c + '" title="Editar"><i class="fas fa-pencil-alt"></i></button></div></td>';
    }

    function cellWhatsApp(id, c, v, e) {
        if (e) return '<td><div class="cell-content"><span class="cell-text cell-empty" data-reg-id="' + id + '" data-campo="' + c + '">—</span><button class="edit-btn" data-id="' + id + '" data-campo="' + c + '" title="Editar"><i class="fas fa-pencil-alt"></i></button></div></td>';
        var p = v.replace(/[^0-9+]/g, ''); if (!p.startsWith('+')) p = '+' + p;
        return '<td><div class="cell-content"><span class="cell-text" data-reg-id="' + id + '" data-campo="' + c + '" style="display:none;">' + escapeHtml(v) + '</span><a href="https://wa.me/' + p.replace('+', '') + '" target="_blank" class="btn-whatsapp"><i class="fab fa-whatsapp"></i> ' + escapeHtml(v) + '</a><button class="edit-btn" data-id="' + id + '" data-campo="' + c + '" title="Editar"><i class="fas fa-pencil-alt"></i></button></div></td>';
    }

    function cellFile(v, e) {
        if (e) return '<td><span class="no-file">—</span></td>';
        return '<td><a href="' + escapeHtml(v) + '" target="_blank" class="btn-file-link"><i class="fas fa-paperclip"></i></a></td>';
    }

    // =====================================================
    // EDICIÓN INLINE
    // =====================================================
    function iniciarEdicion(btn) {
        if (STATE.editingCell) cancelarEdicion();
        var id = parseInt(btn.getAttribute('data-id')), c = btn.getAttribute('data-campo');
        var cc = btn.closest('.cell-content'), ct = cc.querySelector('.cell-text');
        var cv = ct ? (ct.textContent === '—' ? '' : ct.textContent) : '';
        if (c === 'fecha' && cv && cv.indexOf('/') !== -1) {
            var fp = cv.split('/'); if (fp.length === 3) cv = fp[2] + '-' + fp[1] + '-' + fp[0];
        }
        STATE.editingCell = { element: cc, id: id, campo: c, originalValue: cv, originalHtml: cc.innerHTML };
        cc.innerHTML = '<input type="text" class="inline-edit-input" value="' + escapeHtml(cv) + '"><div class="inline-edit-actions"><button class="inline-edit-save"><i class="fas fa-check"></i></button><button class="inline-edit-cancel"><i class="fas fa-times"></i></button></div>';
        var inp = cc.querySelector('.inline-edit-input'); inp.focus(); inp.select();
        inp.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); guardarEdicion(); } else if (e.key === 'Escape') { e.preventDefault(); cancelarEdicion(); } });
        cc.querySelector('.inline-edit-save').addEventListener('click', guardarEdicion);
        cc.querySelector('.inline-edit-cancel').addEventListener('click', cancelarEdicion);
    }

    function guardarEdicion() {
        if (!STATE.editingCell) return;
        var inp = STATE.editingCell.element.querySelector('.inline-edit-input');
        var nv = inp.value.trim(), id = STATE.editingCell.id, campo = STATE.editingCell.campo;
        if (nv === STATE.editingCell.originalValue) { cancelarEdicion(); return; }
        var fd = new FormData();
        fd.append('registro_id', id); fd.append('campo', campo); fd.append('valor', nv);
        fd.append('csrf_token', document.getElementById('csrfTokenDash').value);
        fetch('includes/ajax/update_registro.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                var reg = STATE.registros.find(function (r) { return r.id == id; }); if (reg) reg[campo] = nv;
                var colDef = COLUMNAS.find(function (x) { return x.key === campo; });
                var empty = (nv === ''), nh = '';
                if (colDef && colDef.type === 'whatsapp') {
                    if (empty) nh = '<span class="cell-text cell-empty" data-reg-id="' + id + '" data-campo="' + campo + '">—</span><button class="edit-btn" data-id="' + id + '" data-campo="' + campo + '" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                    else { var ph = nv.replace(/[^0-9+]/g, ''); if (!ph.startsWith('+')) ph = '+' + ph; nh = '<span class="cell-text" data-reg-id="' + id + '" data-campo="' + campo + '" style="display:none;">' + escapeHtml(nv) + '</span><a href="https://wa.me/' + ph.replace('+', '') + '" target="_blank" class="btn-whatsapp"><i class="fab fa-whatsapp"></i> ' + escapeHtml(nv) + '</a><button class="edit-btn" data-id="' + id + '" data-campo="' + campo + '" title="Editar"><i class="fas fa-pencil-alt"></i></button>'; }
                } else {
                    var displayVal = nv;
                    if (campo === 'fecha' && nv) displayVal = formatearFecha(nv);
                    nh = '<span class="cell-text" data-reg-id="' + id + '" data-campo="' + campo + '">' + (empty ? '<span class="cell-empty">—</span>' : escapeHtml(displayVal)) + '</span><button class="edit-btn" data-id="' + id + '" data-campo="' + campo + '" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                }
                STATE.editingCell.element.innerHTML = nh; STATE.editingCell = null;
                if (typeof mostrarToast === 'function') mostrarToast('Campo actualizado', 'success', 2000);
            } else { if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error'); }
        }).catch(function () { if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error'); });
    }

    function cancelarEdicion() { if (!STATE.editingCell) return; STATE.editingCell.element.innerHTML = STATE.editingCell.originalHtml; STATE.editingCell = null; }

    // =====================================================
    // EVENTOS
    // =====================================================
    function bindEvents() {
        if (DOM.filterSearch) DOM.filterSearch.addEventListener('input', function () {
            clearTimeout(STATE.searchTimer); STATE.searchTimer = setTimeout(function () { cargarFiltros(); cargarRegistros(true); }, CONFIG.DEBOUNCE_DELAY);
        });
        [DOM.filterPrincipal, DOM.filterCurso, DOM.filterPais, DOM.filterCiudad, DOM.filterMoneda, DOM.filterMetodoPago, DOM.filterWeb].forEach(function (s) {
            if (s) s.addEventListener('change', function () { this.classList.toggle('active-filter', this.value !== ''); cargarFiltros(); cargarRegistros(true); });
        });
        [DOM.filterFechaDesde, DOM.filterFechaHasta].forEach(function (el) { if (el) el.addEventListener('change', function () { cargarFiltros(); cargarRegistros(true); }); });
        [DOM.filterHoraDesdeH, DOM.filterHoraDesdeM, DOM.filterHoraHastaH, DOM.filterHoraHastaM].forEach(function (el) { if (el) el.addEventListener('change', function () { cargarFiltros(); cargarRegistros(true); }); });
        if (DOM.filterPageSize) DOM.filterPageSize.addEventListener('change', function () { cargarRegistros(true); });
        if (DOM.btnClear) DOM.btnClear.addEventListener('click', limpiarFiltros);
        if (DOM.btnExcel) DOM.btnExcel.addEventListener('click', exportarExcel);

        if (DOM.tableHeaders) DOM.tableHeaders.addEventListener('click', function (e) {
            // ── FIX: si reordenar no está permitido, ignorar clicks ──
            if (!STATE.reordenarPermitido) return;
            var th = e.target.closest('th'); if (!th || th.getAttribute('data-sortable') === 'false') return;
            var col = th.getAttribute('data-column');
            if (STATE.sortColumn === col) STATE.sortDir = STATE.sortDir === 'ASC' ? 'DESC' : 'ASC';
            else { STATE.sortColumn = col; STATE.sortDir = 'ASC'; }
            renderHeaders(); cargarRegistros(true);
        });
        if (DOM.tableScroll) DOM.tableScroll.addEventListener('scroll', function () {
            if (this.scrollTop + this.clientHeight >= this.scrollHeight - 100 && !STATE.isLoading && STATE.hasMore) cargarRegistros(false);
        });
        if (DOM.tableBody) DOM.tableBody.addEventListener('click', function (e) {
            var btn = e.target.closest('.edit-btn'); if (btn) { e.preventDefault(); iniciarEdicion(btn); }
        });
    }

    function limpiarFiltros() {
        if (DOM.filterSearch) DOM.filterSearch.value = '';
        [DOM.filterPrincipal, DOM.filterCurso, DOM.filterPais, DOM.filterCiudad, DOM.filterMoneda, DOM.filterMetodoPago, DOM.filterWeb].forEach(function (s) { if (s) { s.value = ''; s.classList.remove('active-filter'); } });
        if (DOM.filterFechaDesde) DOM.filterFechaDesde.value = '';
        if (DOM.filterFechaHasta) DOM.filterFechaHasta.value = '';
        [DOM.filterHoraDesdeH, DOM.filterHoraDesdeM, DOM.filterHoraHastaH, DOM.filterHoraHastaM].forEach(function (el) { if (el) el.value = ''; });
        if (DOM.filterPageSize) DOM.filterPageSize.value = '50';
        STATE.sortColumn = 'fecha_registro'; STATE.sortDir = 'DESC';
        renderHeaders(); cargarFiltros(); cargarRegistros(true);
    }

    // =====================================================
    // POLLING
    // =====================================================
    function iniciarPolling() {
        STATE.pollTimer = setInterval(function () {
            if (!STATE.isPollActive || STATE.isLoading || hayFiltrosActivos() || STATE.lastId <= 0) return;
            fetch('includes/ajax/poll_registros.php?last_id=' + STATE.lastId + '&vista_tipo=' + VISTA_TIPO, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.success && d.count > 0) {
                    var f = document.createDocumentFragment();
                    d.nuevos.forEach(function (r) {
                        if (!STATE.registros.find(function (x) { return x.id == r.id; })) { STATE.registros.unshift(r); f.appendChild(crearFila(r, true)); if (r.id > STATE.lastId) STATE.lastId = r.id; }
                    });
                    if (f.childNodes.length > 0) { if (DOM.tableBody.firstChild) DOM.tableBody.insertBefore(f, DOM.tableBody.firstChild); else DOM.tableBody.appendChild(f); if (DOM.noResults) DOM.noResults.style.display = 'none'; }
                    STATE.totalFiltered += d.count; STATE.totalGeneral += d.count; updateCounters(); cargarFiltros();
                    if (typeof mostrarToast === 'function') mostrarToast(d.count === 1 ? 'Nuevo registro' : d.count + ' nuevos', 'new-record', 5000);
                }
            }).catch(function () {});
        }, CONFIG.POLL_INTERVAL);
    }

    function updateCounters() {
        if (DOM.countFiltered) DOM.countFiltered.textContent = STATE.totalFiltered.toLocaleString();
        if (DOM.countTotal)    DOM.countTotal.textContent    = STATE.totalGeneral.toLocaleString();
    }

    // =====================================================
    // EXPORTAR EXCEL
    // =====================================================
    function exportarExcel() {
        var h = [], r = [];
        COLUMNAS.forEach(function (c) { h.push(c.label); });
        STATE.registros.forEach(function (reg) {
            var row = [];
            COLUMNAS.forEach(function (c) {
                var v = reg[c.key];
                if (v !== null && v !== undefined) { if (c.key === 'fecha') v = formatearFecha(v); row.push(v); } else { row.push(''); }
            });
            r.push(row);
        });
        if (typeof XLSX === 'undefined') { var s = document.createElement('script'); s.src = 'https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js'; s.onload = function () { genExcel(h, r); }; document.head.appendChild(s); }
        else genExcel(h, r);
    }

    function genExcel(h, r) {
        var ws = XLSX.utils.aoa_to_sheet([h].concat(r)); var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Delegados');
        ws['!cols'] = h.map(function (x) { return { wch: Math.max(x.length + 2, 12) }; });
        XLSX.writeFile(wb, 'Delegados_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        if (typeof mostrarToast === 'function') mostrarToast('Excel exportado', 'success', 3000);
    }

    function escapeHtml(t) { if (t === null || t === undefined) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; }

    // =====================================================
    // PERMISOS EN TIEMPO REAL (sección: asesores_delegados)
    // =====================================================
    function expulsarSesion(mensaje) {
        if (STATE.sesionInvalidada) return;
        STATE.sesionInvalidada = true;
        if (STATE.pollTimer)     clearInterval(STATE.pollTimer);
        if (STATE.permisosTimer) clearInterval(STATE.permisosTimer);
        STATE.isPollActive = false;
        if (typeof mostrarToast === 'function') mostrarToast(mensaje || 'Tu sesión ha sido cerrada. Redirigiendo...', 'error', 4000);
        var overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:99999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px';
        overlay.innerHTML = '<i class="fas fa-lock" style="font-size:48px;color:#fff;"></i>' +
            '<p style="color:#fff;font-size:16px;font-weight:600;margin:0;text-align:center;">' + (mensaje || 'Tu sesión ha sido cerrada.') + '</p>' +
            '<p style="color:rgba(255,255,255,0.7);font-size:13px;margin:0;">Redirigiendo al inicio de sesión...</p>';
        document.body.appendChild(overlay);
        setTimeout(function () { window.location.href = 'index.php?session=expired'; }, 3000);
    }

    function cargarYAplicarPermisos() {
        if (STATE.sesionInvalidada) return;
        fetch('includes/ajax/get_permisos_usuario.php', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.session_invalida === true) { expulsarSesion(data.message || 'Tu cuenta ya no existe en el sistema.'); return; }
            if (!data.success) return;
            if (data.es_admin) return; // Admin ve todo

            var p  = data.permisos;
            var ad = p.asesores_delegados || {};

            // === COLUMNAS: ocultar/mostrar ===
            var colMap = {
                'nombre': 'col_nombre', 'apellidos': 'col_apellidos', 'telefono': 'col_telefono',
                'correo': 'col_correo', 'delegado': 'col_delegado', 'curso': 'col_curso',
                'pais': 'col_pais', 'ciudad': 'col_ciudad', 'moneda': 'col_moneda',
                'metodo_pago': 'col_metodo_pago', 'ip': 'col_ip', 'fecha': 'col_fecha',
                'hora': 'col_hora', 'categoria': 'col_categoria', 'file_url': 'col_file_url',
                'formulario_id': 'col_formulario_id', 'web': 'col_web'
            };
            var allTh = DOM.tableHeaders.querySelectorAll('th');
            allTh.forEach(function (th, idx) {
                var colKey = th.getAttribute('data-column');
                if (colKey && colMap[colKey] !== undefined) {
                    var visible = (ad[colMap[colKey]] !== undefined) ? ad[colMap[colKey]] : true;
                    var dv = visible ? '' : 'none';
                    th.style.display = dv;
                    DOM.tableBody.querySelectorAll('tr').forEach(function (row) {
                        var tds = row.querySelectorAll('td'); if (tds[idx]) tds[idx].style.display = dv;
                    });
                }
            });

            // === FILTROS FILA 3 (selectores) ===
            var filtroMap = {
                'filtro_curso':       'delegadoFilterCurso',
                'filtro_pais':        'delegadoFilterPais',
                'filtro_ciudad':      'delegadoFilterCiudad',
                'filtro_moneda':      'delegadoFilterMoneda',
                'filtro_metodo_pago': 'delegadoFilterMetodoPago',
                'filtro_web':         'delegadoFilterWeb'
            };
            Object.keys(filtroMap).forEach(function (permKey) {
                var el = document.getElementById(filtroMap[permKey]);
                if (el) el.style.display = (ad[permKey] !== false) ? '' : 'none';
            });

            // === FILTROS FILA 1 ===
            // filtro_formulario → label Delegados + select principal
            var labelDelegado = document.getElementById('delegadoFiltroDelegadoLabel');
            if (labelDelegado) labelDelegado.style.display = (ad.filtro_formulario !== false) ? '' : 'none';
            if (DOM.filterPrincipal) DOM.filterPrincipal.style.display = (ad.filtro_formulario !== false) ? '' : 'none';

            // filtro_busqueda → input búsqueda
            var wBusqueda = document.getElementById('delegadoFiltroBusquedaWrapper');
            if (wBusqueda) wBusqueda.style.display = (ad.filtro_busqueda !== false) ? '' : 'none';

            // filtro_mostrando → contador
            var wMostrando = document.getElementById('delegadoFiltroMostrandoWrapper');
            if (wMostrando) wMostrando.style.display = (ad.filtro_mostrando !== false) ? '' : 'none';

            // filtro_limpiar → botón Limpiar
            if (DOM.btnClear) DOM.btnClear.style.display = (ad.filtro_limpiar !== false) ? '' : 'none';

            // === FILTROS FILA 2: Fecha y Hora ===
            var row2 = document.getElementById('delegadoFiltersRow2');
            if (row2) row2.style.display = (ad.filtro_fecha_hora !== false) ? '' : 'none';

            // === REORDENAR COLUMNAS ===
            var nuevoReordenar = (ad.reordenar_columnas !== false);
            if (nuevoReordenar !== STATE.reordenarPermitido) {
                STATE.reordenarPermitido = nuevoReordenar;
                renderHeaders();
            }
            if (DOM.tableHeaders) {
                DOM.tableHeaders.style.cursor = nuevoReordenar ? '' : 'default';
                DOM.tableHeaders.querySelectorAll('th').forEach(function (th) {
                    th.style.pointerEvents = nuevoReordenar ? '' : 'none';
                });
            }

            // === DESCARGAR EXCEL ===
            if (DOM.btnExcel) DOM.btnExcel.style.display = (ad.descargar_excel !== false) ? '' : 'none';

            // === EDICIÓN INLINE ===
            if (ad.edicion_inline === false) {
                document.querySelectorAll('#delegadoTableBody .edit-btn').forEach(function(btn){ btn.style.visibility = 'hidden'; btn.style.pointerEvents = 'none'; });
            } else {
                document.querySelectorAll('#delegadoTableBody .edit-btn').forEach(function(btn){ btn.style.visibility = ''; btn.style.pointerEvents = ''; });
            }
        })
        .catch(function (err) { console.error('Error permisos delegados:', err); });
    }

    window.addEventListener('beforeunload', function () {
        if (STATE.pollTimer)     clearInterval(STATE.pollTimer);
        if (STATE.permisosTimer) clearInterval(STATE.permisosTimer);
    });

    init();
})();
</script>
