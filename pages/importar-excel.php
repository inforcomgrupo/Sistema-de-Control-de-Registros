<?php
/**
 * Página: Importar desde Excel
 * Solo accesible por Administrador
 */
if (!defined('SISTEMA_REGISTROS')) {
    define('SISTEMA_REGISTROS', true);
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/app.php';
    require_once __DIR__ . '/../includes/auth.php';
}

iniciarSesionSegura();
if (!estaAutenticado() || !esAdministrador()) {
    echo '<div style="text-align:center;padding:40px;color:#FF3600;">
        <i class="fas fa-lock" style="font-size:30px;margin-bottom:10px;display:block;"></i>
        <p>No tiene permisos para acceder a esta sección.</p>
    </div>';
    return;
}
?>

<link rel="stylesheet" href="assets/css/opciones-sistema.css?v=<?php echo SYSTEM_VERSION; ?>">

<style>
/* =====================================================
   IMPORTAR EXCEL - ESTILOS
   ===================================================== */
.importar-container {
    display: flex;
    flex-direction: column;
    gap: 0;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0 2px 20px 0;
}
.importar-container::-webkit-scrollbar { width: 5px; }
.importar-container::-webkit-scrollbar-track { background: transparent; }
.importar-container::-webkit-scrollbar-thumb { background: var(--gris-borde); border-radius: 5px; }

/* ZONA DE DROP */
.drop-zone {
    border: 2px dashed var(--gris-borde);
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafbfc;
    margin-bottom: 16px;
}
.drop-zone:hover, .drop-zone.drag-over {
    border-color: var(--celeste);
    background: #f0f9ff;
}
.drop-zone-icon { font-size: 40px; color: var(--gris-borde); margin-bottom: 10px; display: block; }
.drop-zone:hover .drop-zone-icon, .drop-zone.drag-over .drop-zone-icon { color: var(--celeste); }
.drop-zone-text { font-size: 13px; color: var(--gris-oscuro); margin-bottom: 4px; }
.drop-zone-sub { font-size: 11px; color: #9ca3af; }
.drop-zone input[type="file"] { display: none; }

/* ARCHIVO SELECCIONADO */
.file-selected {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f0f7ff;
    border: 1px solid #d1e5f7;
    border-radius: 6px;
    margin-bottom: 16px;
}
.file-selected.active { display: flex; }
.file-selected-icon { font-size: 24px; color: #059669; }
.file-selected-info { flex: 1; }
.file-selected-name { font-size: 12px; font-weight: 600; color: var(--gris-oscuro); }
.file-selected-size { font-size: 11px; color: #9ca3af; }
.file-selected-remove {
    background: none; border: none; color: var(--rojo);
    cursor: pointer; font-size: 14px; padding: 4px;
}

/* PREVIEW TABLE */
.preview-wrapper {
    display: none;
    margin-bottom: 16px;
}
.preview-wrapper.active { display: block; }
.preview-scroll {
    max-height: 350px;
    overflow: auto;
    border: 1px solid var(--gris-medio);
    border-radius: 4px;
}
.preview-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
.preview-scroll::-webkit-scrollbar-track { background: transparent; }
.preview-scroll::-webkit-scrollbar-thumb { background: var(--gris-borde); border-radius: 5px; }
.preview-table {
    width: 100%; border-collapse: collapse; font-size: 11px;
}
.preview-table th {
    background: var(--gris-claro); padding: 6px 8px; text-align: left;
    font-weight: 600; color: var(--gris-oscuro); border-bottom: 2px solid var(--gris-medio);
    font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px;
    position: sticky; top: 0; z-index: 2;
}
.preview-table td {
    padding: 5px 8px; border-bottom: 1px solid #f3f4f6;
    color: var(--gris-oscuro); max-width: 180px; overflow: hidden;
    text-overflow: ellipsis; white-space: nowrap;
}
.preview-table tr:hover td { background: #fafbfc; }
.preview-table tr.row-error td { background: #fef2f2; }

/* MAPEO DE COLUMNAS */
.mapping-wrapper {
    display: none;
    margin-bottom: 16px;
}
.mapping-wrapper.active { display: block; }
.mapping-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 8px;
}
.mapping-item {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 10px; background: #fafbfc; border-radius: 4px;
    border: 1px solid #f0f1f3;
}
.mapping-item-label {
    flex: 0 0 100px; font-size: 11px; font-weight: 600;
    color: var(--gris-oscuro); display: flex; align-items: center; gap: 4px;
}
.mapping-item-label i { color: var(--azul3); font-size: 10px; width: 12px; text-align: center; }
.mapping-item select {
    flex: 1; padding: 5px 8px; border: 1px solid var(--gris-borde);
    border-radius: 4px; font-size: 11px; color: var(--gris-oscuro);
    background: var(--blanco); cursor: pointer;
}
.mapping-item select:focus { outline: none; border-color: var(--celeste); }
.mapping-auto { color: #059669; font-size: 10px; font-weight: 500; }

/* BARRA DE PROGRESO */
.progress-wrapper {
    display: none;
    margin-bottom: 16px;
}
.progress-wrapper.active { display: block; }
.progress-bar-bg {
    width: 100%; height: 22px; background: #e5e7eb; border-radius: 11px;
    overflow: hidden; position: relative;
}
.progress-bar-fill {
    height: 100%; background: linear-gradient(90deg, var(--celeste), var(--azul3));
    border-radius: 11px; transition: width 0.3s ease; width: 0%;
}
.progress-bar-text {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%); font-size: 11px;
    font-weight: 600; color: var(--gris-oscuro);
}
.progress-status {
    text-align: center; font-size: 12px; color: var(--gris-oscuro);
    margin-top: 8px;
}

/* RESULTADO */
.result-wrapper {
    display: none;
    margin-bottom: 16px;
}
.result-wrapper.active { display: block; }
.result-cards {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px; margin-bottom: 14px;
}
.result-card {
    text-align: center; padding: 14px 10px; border-radius: 6px;
    border: 1px solid #e5e7eb;
}
.result-card-value { font-size: 24px; font-weight: 700; display: block; margin-bottom: 2px; }
.result-card-label { font-size: 11px; color: #6b7280; }
.result-card.success { background: #f0fdf4; border-color: #bbf7d0; }
.result-card.success .result-card-value { color: #059669; }
.result-card.duplicates { background: #fffbeb; border-color: #fde68a; }
.result-card.duplicates .result-card-value { color: #d97706; }
.result-card.errors { background: #fef2f2; border-color: #fecaca; }
.result-card.errors .result-card-value { color: var(--rojo); }
.result-card.total { background: #f0f7ff; border-color: #bfdbfe; }
.result-card.total .result-card-value { color: var(--azul3); }
</style>

<div class="importar-container" id="importarContainer">

    <!-- SECCIÓN: INSTRUCCIONES Y DESCARGA MODELO -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="IMP.toggleSection(this)">
            <h3><i class="fas fa-info-circle"></i> Instrucciones y Modelo de Excel</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body">
            <div class="opc-info">
                <i class="fas fa-info-circle"></i>
                <span>
                    Descargue el modelo de Excel, llene los datos y súbalo al sistema.
                    <strong>No es necesario llenar todas las columnas</strong>, los campos vacíos se importarán sin problema.
                    Se permiten registros duplicados.
                    Formatos aceptados: <strong>.xlsx</strong> y <strong>.csv</strong>
                </span>
            </div>
            <div class="opc-section-actions" style="justify-content:flex-start; border-top:none; margin-top:0; padding-top:0;">
                <button class="opc-btn opc-btn-success" id="btnDescargarModelo">
                    <i class="fas fa-download"></i> Descargar Modelo Excel
                </button>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: IMPORTAR -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="IMP.toggleSection(this)">
            <h3><i class="fas fa-file-excel"></i> Importar Archivo</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body">

            <!-- Drop Zone -->
            <div class="drop-zone" id="dropZone">
                <i class="fas fa-cloud-upload-alt drop-zone-icon"></i>
                <p class="drop-zone-text">Arrastre su archivo Excel aquí o <strong>haga clic para seleccionar</strong></p>
                <p class="drop-zone-sub">Formatos: .xlsx, .csv — Máximo 10MB</p>
                <input type="file" id="fileInput" accept=".xlsx,.csv">
            </div>

            <!-- Archivo seleccionado -->
            <div class="file-selected" id="fileSelected">
                <i class="fas fa-file-excel file-selected-icon"></i>
                <div class="file-selected-info">
                    <div class="file-selected-name" id="fileName"></div>
                    <div class="file-selected-size" id="fileSize"></div>
                </div>
                <button class="file-selected-remove" id="btnRemoveFile" title="Quitar archivo">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            <!-- Mapeo de columnas -->
            <div class="mapping-wrapper" id="mappingWrapper">
                <div class="opc-info">
                    <i class="fas fa-columns"></i>
                    <span>Verifique que las columnas del Excel estén mapeadas correctamente a los campos del sistema. Las columnas se detectan automáticamente por nombre.</span>
                </div>
                <div class="mapping-grid" id="mappingGrid"></div>
            </div>

            <!-- Preview -->
            <div class="preview-wrapper" id="previewWrapper">
                <p style="font-size:12px;font-weight:600;color:var(--gris-oscuro);margin-bottom:8px;">
                    <i class="fas fa-eye" style="color:var(--celeste);"></i>
                    Vista previa (primeras 20 filas):
                </p>
                <div class="preview-scroll">
                    <table class="preview-table" id="previewTable">
                        <thead id="previewHead"></thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Progreso -->
            <div class="progress-wrapper" id="progressWrapper">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" id="progressFill"></div>
                    <span class="progress-bar-text" id="progressText">0%</span>
                </div>
                <div class="progress-status" id="progressStatus">Preparando importación...</div>
            </div>

            <!-- Resultado -->
            <div class="result-wrapper" id="resultWrapper">
                <div class="result-cards">
                    <div class="result-card total">
                        <span class="result-card-value" id="resTotal">0</span>
                        <span class="result-card-label">Total Procesados</span>
                    </div>
                    <div class="result-card success">
                        <span class="result-card-value" id="resInsertados">0</span>
                        <span class="result-card-label">Insertados</span>
                    </div>
                    <div class="result-card duplicates">
                        <span class="result-card-value" id="resDuplicados">0</span>
                        <span class="result-card-label">Duplicados (insertados)</span>
                    </div>
                    <div class="result-card errors">
                        <span class="result-card-value" id="resErrores">0</span>
                        <span class="result-card-label">Con Errores</span>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="opc-section-actions" id="importActions" style="display:none;">
                <button class="opc-btn opc-btn-danger" id="btnCancelar" style="display:none;">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button class="opc-btn opc-btn-primary" id="btnImportar">
                    <i class="fas fa-upload"></i> Importar Datos
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script>
/**
 * Importar desde Excel - Script
 * Namespace IMP
 */
var IMP = (function () {
    'use strict';

    var CSRF = document.getElementById('csrfTokenDash') ? document.getElementById('csrfTokenDash').value : '';
    var BATCH_SIZE = 100;

    // Campos del sistema con alias para auto-detectar columnas del Excel
    var CAMPOS_SISTEMA = [
        { key: 'nombre',        label: 'Nombre',         aliases: ['nombre','name','nombres','primer nombre','first name'] },
        { key: 'apellidos',     label: 'Apellidos',      aliases: ['apellidos','apellido','lastname','last name','surnames','surname'] },
        { key: 'telefono',      label: 'Teléfono',       aliases: ['telefono','teléfono','celular','phone','tel','whatsapp','móvil','movil'] },
        { key: 'correo',        label: 'Correo',         aliases: ['correo','email','e-mail','correo electrónico','correo electronico','mail'] },
        { key: 'asesor',        label: 'Asesor',         aliases: ['asesor','asesora','advisor','consultor'] },
        { key: 'delegado',      label: 'Delegado',       aliases: ['delegado','delegada','delegate'] },
        { key: 'curso',         label: 'Curso',          aliases: ['curso','cursos','course','programa','diplomado'] },
        { key: 'pais',          label: 'País',           aliases: ['pais','país','country'] },
        { key: 'ciudad',        label: 'Ciudad',         aliases: ['ciudad','city','localidad'] },
        { key: 'moneda',        label: 'Moneda',         aliases: ['moneda','currency','divisa'] },
        { key: 'metodo_pago',   label: 'Método de Pago', aliases: ['metodo_pago','método de pago','metodo de pago','payment','forma de pago','pago'] },
        { key: 'ip',            label: 'IP',             aliases: ['ip','dirección ip','direccion ip'] },
        { key: 'fecha',         label: 'Fecha',          aliases: ['fecha','date','fecha registro','fecha de registro'] },
        { key: 'hora',          label: 'Hora',           aliases: ['hora','time','hora registro'] },
        { key: 'categoria',     label: 'Categoría',      aliases: ['categoria','categoría','category','tipo'] },
        { key: 'file_url',      label: 'URL Archivo',    aliases: ['file_url','archivo','file','url archivo','adjunto','attachment'] },
        { key: 'formulario_id', label: 'ID Formulario',  aliases: ['formulario_id','id formulario','form id','id','formulario'] },
        { key: 'web',           label: 'Web',            aliases: ['web','website','sitio web','dominio','sitio','página'] }
    ];

    var STATE = {
        workbook: null,
        sheetData: [],
        headers: [],
        mapping: {},
        file: null
    };

    function init() {
        bindEvents();
    }

    function toggleSection(header) {
        header.classList.toggle('collapsed');
        header.nextElementSibling.classList.toggle('collapsed');
    }

    function esc(str) {
        if (str === null || str === undefined) return '';
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(String(str)));
        return d.innerHTML;
    }

    // =====================================================
    // EVENTOS
    // =====================================================
    function bindEvents() {
        var dropZone = document.getElementById('dropZone');
        var fileInput = document.getElementById('fileInput');

        if (dropZone) {
            dropZone.addEventListener('click', function () { fileInput.click(); });
            dropZone.addEventListener('dragover', function (e) { e.preventDefault(); this.classList.add('drag-over'); });
            dropZone.addEventListener('dragleave', function () { this.classList.remove('drag-over'); });
            dropZone.addEventListener('drop', function (e) {
                e.preventDefault(); this.classList.remove('drag-over');
                if (e.dataTransfer.files.length > 0) procesarArchivo(e.dataTransfer.files[0]);
            });
        }
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) procesarArchivo(this.files[0]);
            });
        }

        var btnRemove = document.getElementById('btnRemoveFile');
        if (btnRemove) btnRemove.addEventListener('click', resetear);

        var btnImportar = document.getElementById('btnImportar');
        if (btnImportar) btnImportar.addEventListener('click', iniciarImportacion);

        var btnCancelar = document.getElementById('btnCancelar');
        if (btnCancelar) btnCancelar.addEventListener('click', resetear);

        var btnModelo = document.getElementById('btnDescargarModelo');
        if (btnModelo) btnModelo.addEventListener('click', descargarModelo);
    }

    // =====================================================
    // DESCARGAR MODELO EXCEL
    // =====================================================
    function descargarModelo() {
        var headers = CAMPOS_SISTEMA.map(function (c) { return c.label; });
        var ejemplo = [
            'Juan', 'Pérez López', '+51987654321', 'juan@email.com',
            'María García', 'Carlos López', 'Diplomado en Psicología Clínica',
            'Perú', 'Lima', 'USD', 'PayPal', '192.168.1.1',
            '2025-01-15', '14:30', 'Diplomado', 'https://drive.google.com/archivo',
            'FORM-001', 'www.psicologiaenvivo.com'
        ];
        var ws = XLSX.utils.aoa_to_sheet([headers, ejemplo]);
        ws['!cols'] = headers.map(function (h) { return { wch: Math.max(h.length + 4, 16) }; });
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Modelo');
        XLSX.writeFile(wb, 'Modelo_Importacion_Registros.xlsx');
        if (typeof mostrarToast === 'function') mostrarToast('Modelo descargado', 'success');
    }

    // =====================================================
    // PROCESAR ARCHIVO
    // =====================================================
    function procesarArchivo(file) {
        var ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'xlsx' && ext !== 'csv') {
            if (typeof mostrarToast === 'function') mostrarToast('Formato no válido. Use .xlsx o .csv', 'error');
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            if (typeof mostrarToast === 'function') mostrarToast('El archivo excede 10MB', 'error');
            return;
        }

        STATE.file = file;

        // Mostrar archivo seleccionado
        document.getElementById('dropZone').style.display = 'none';
        document.getElementById('fileSelected').classList.add('active');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';

        // Leer archivo
        var reader = new FileReader();
        reader.onload = function (e) {
            try {
                var data = new Uint8Array(e.target.result);
                STATE.workbook = XLSX.read(data, { type: 'array', cellDates: true });
                var sheet = STATE.workbook.Sheets[STATE.workbook.SheetNames[0]];
                STATE.sheetData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });

                if (STATE.sheetData.length < 2) {
                    if (typeof mostrarToast === 'function') mostrarToast('El archivo no tiene datos (solo encabezados o vacío)', 'error');
                    resetear();
                    return;
                }

                STATE.headers = STATE.sheetData[0].map(function (h) { return String(h).trim(); });
                autoDetectarMapeo();
                renderMapeo();
                renderPreview();
                document.getElementById('importActions').style.display = '';
                document.getElementById('btnCancelar').style.display = '';
                document.getElementById('btnImportar').style.display = '';
            } catch (err) {
                console.error('Error leyendo Excel:', err);
                if (typeof mostrarToast === 'function') mostrarToast('Error al leer el archivo', 'error');
                resetear();
            }
        };
        reader.readAsArrayBuffer(file);
    }

    // =====================================================
    // AUTO-DETECTAR MAPEO
    // =====================================================
    function autoDetectarMapeo() {
        STATE.mapping = {};
        CAMPOS_SISTEMA.forEach(function (campo) {
            var found = -1;
            for (var i = 0; i < STATE.headers.length; i++) {
                var headerLower = STATE.headers[i].toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
                for (var j = 0; j < campo.aliases.length; j++) {
                    var aliasLower = campo.aliases[j].toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
                    if (headerLower === aliasLower) { found = i; break; }
                }
                if (found >= 0) break;
            }
            STATE.mapping[campo.key] = found;
        });
    }

    // =====================================================
    // RENDER MAPEO
    // =====================================================
    function renderMapeo() {
        var grid = document.getElementById('mappingGrid');
        var html = '';
        CAMPOS_SISTEMA.forEach(function (campo) {
            var selectedIdx = STATE.mapping[campo.key];
            var autoText = selectedIdx >= 0 ? '<span class="mapping-auto">(auto)</span>' : '';
            html += '<div class="mapping-item">';
            html += '<div class="mapping-item-label"><i class="fas fa-arrow-right"></i> ' + campo.label + ' ' + autoText + '</div>';
            html += '<select data-campo="' + campo.key + '" onchange="IMP.updateMapping(this)">';
            html += '<option value="-1">— No importar —</option>';
            STATE.headers.forEach(function (h, idx) {
                var sel = (idx === selectedIdx) ? ' selected' : '';
                html += '<option value="' + idx + '"' + sel + '>' + esc(h) + '</option>';
            });
            html += '</select>';
            html += '</div>';
        });
        grid.innerHTML = html;
        document.getElementById('mappingWrapper').classList.add('active');
    }

    function updateMapping(select) {
        STATE.mapping[select.getAttribute('data-campo')] = parseInt(select.value);
        renderPreview();
    }

    // =====================================================
    // RENDER PREVIEW
    // =====================================================
    function renderPreview() {
        var headHtml = '<tr>';
        CAMPOS_SISTEMA.forEach(function (c) {
            var mapped = STATE.mapping[c.key] >= 0;
            headHtml += '<th style="' + (mapped ? '' : 'color:#9ca3af;') + '">' + c.label + '</th>';
        });
        headHtml += '</tr>';
        document.getElementById('previewHead').innerHTML = headHtml;

        var bodyHtml = '';
        var maxRows = Math.min(STATE.sheetData.length, 21); // header + 20 filas
        for (var i = 1; i < maxRows; i++) {
            var row = STATE.sheetData[i];
            bodyHtml += '<tr>';
            CAMPOS_SISTEMA.forEach(function (c) {
                var idx = STATE.mapping[c.key];
                var val = (idx >= 0 && row[idx] !== undefined) ? String(row[idx]) : '';
                // Formatear fechas de Date objects
                if (c.key === 'fecha' && val && val.indexOf('GMT') !== -1) {
                    try {
                        var d = new Date(val);
                        val = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                    } catch (e) {}
                }
                bodyHtml += '<td>' + esc(val) + '</td>';
            });
            bodyHtml += '</tr>';
        }
        document.getElementById('previewBody').innerHTML = bodyHtml;
        document.getElementById('previewWrapper').classList.add('active');
    }

    // =====================================================
    // IMPORTAR DATOS (POR LOTES)
    // =====================================================
    function iniciarImportacion() {
        // Preparar datos
        var registros = [];
        for (var i = 1; i < STATE.sheetData.length; i++) {
            var row = STATE.sheetData[i];
            // Verificar que la fila no esté completamente vacía
            var hayDato = false;
            for (var k = 0; k < row.length; k++) {
                if (row[k] !== null && row[k] !== undefined && String(row[k]).trim() !== '') {
                    hayDato = true; break;
                }
            }
            if (!hayDato) continue;

            var reg = {};
            CAMPOS_SISTEMA.forEach(function (c) {
                var idx = STATE.mapping[c.key];
                var val = '';
                if (idx >= 0 && row[idx] !== undefined && row[idx] !== null) {
                    val = String(row[idx]).trim();
                    // Formatear fechas de Date objects
                    if (c.key === 'fecha' && val && val.indexOf('GMT') !== -1) {
                        try {
                            var d = new Date(val);
                            val = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                        } catch (e) {}
                    }
                    if (c.key === 'hora' && val && val.indexOf('GMT') !== -1) {
                        try {
                            var d2 = new Date(val);
                            val = String(d2.getHours()).padStart(2, '0') + ':' + String(d2.getMinutes()).padStart(2, '0');
                        } catch (e) {}
                    }
                }
                reg[c.key] = val;
            });
            registros.push(reg);
        }

        if (registros.length === 0) {
            if (typeof mostrarToast === 'function') mostrarToast('No hay datos para importar', 'error');
            return;
        }

        // Mostrar progreso
        document.getElementById('btnImportar').style.display = 'none';
        document.getElementById('mappingWrapper').classList.remove('active');
        document.getElementById('previewWrapper').classList.remove('active');
        document.getElementById('progressWrapper').classList.add('active');
        document.getElementById('resultWrapper').classList.remove('active');

        var totalRegistros = registros.length;
        var totalBatches = Math.ceil(totalRegistros / BATCH_SIZE);
        var batchIndex = 0;
        var totalInsertados = 0;
        var totalDuplicados = 0;
        var totalErrores = 0;

        function enviarBatch() {
            if (batchIndex >= totalBatches) {
                // Finalizado
                var pct = 100;
                document.getElementById('progressFill').style.width = pct + '%';
                document.getElementById('progressText').textContent = pct + '%';
                document.getElementById('progressStatus').textContent = '¡Importación completada!';

                document.getElementById('resTotal').textContent = totalRegistros;
                document.getElementById('resInsertados').textContent = totalInsertados;
                document.getElementById('resDuplicados').textContent = totalDuplicados;
                document.getElementById('resErrores').textContent = totalErrores;
                document.getElementById('resultWrapper').classList.add('active');

                document.getElementById('btnCancelar').innerHTML = '<i class="fas fa-redo"></i> Nueva Importación';
                document.getElementById('btnCancelar').style.display = '';

                if (typeof mostrarToast === 'function') {
                    mostrarToast('Importación completada: ' + totalInsertados + ' registros insertados', 'success', 6000);
                }
                return;
            }

            var start = batchIndex * BATCH_SIZE;
            var end = Math.min(start + BATCH_SIZE, totalRegistros);
            var batch = registros.slice(start, end);

            var pct = Math.round(((start) / totalRegistros) * 100);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('progressText').textContent = pct + '%';
            document.getElementById('progressStatus').textContent = 'Importando registros ' + (start + 1) + ' - ' + end + ' de ' + totalRegistros + '...';

            var fd = new FormData();
            fd.append('accion', 'importar_batch');
            fd.append('registros', JSON.stringify(batch));
            fd.append('csrf_token', CSRF);

            fetch('includes/ajax/importar_excel.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    totalInsertados += data.insertados || 0;
                    totalDuplicados += data.duplicados || 0;
                    totalErrores += data.errores || 0;
                    batchIndex++;
                    enviarBatch();
                } else {
                    totalErrores += batch.length;
                    batchIndex++;
                    enviarBatch();
                }
            })
            .catch(function () {
                totalErrores += batch.length;
                batchIndex++;
                enviarBatch();
            });
        }

        enviarBatch();
    }

    // =====================================================
    // RESETEAR
    // =====================================================
    function resetear() {
        STATE = { workbook: null, sheetData: [], headers: [], mapping: {}, file: null };
        document.getElementById('dropZone').style.display = '';
        document.getElementById('fileSelected').classList.remove('active');
        document.getElementById('mappingWrapper').classList.remove('active');
        document.getElementById('previewWrapper').classList.remove('active');
        document.getElementById('progressWrapper').classList.remove('active');
        document.getElementById('resultWrapper').classList.remove('active');
        document.getElementById('importActions').style.display = 'none';
        document.getElementById('btnCancelar').style.display = 'none';
        document.getElementById('btnCancelar').innerHTML = '<i class="fas fa-times"></i> Cancelar';
        document.getElementById('btnImportar').style.display = '';
        document.getElementById('progressFill').style.width = '0%';
        document.getElementById('progressText').textContent = '0%';
        var fileInput = document.getElementById('fileInput');
        if (fileInput) fileInput.value = '';
    }

    init();

    return {
        toggleSection: toggleSection,
        updateMapping: updateMapping
    };
})();
</script>