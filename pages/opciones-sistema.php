<?php
/**
 * Página: Opciones de Sistema
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

<!-- MODAL: CONFIRMAR SUSPENDER / ACTIVAR CONSULTOR -->
<div class="modal-overlay" id="modalConfirmConsultor">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header" id="modalConfirmHeader">
            <div class="modal-header-left">
                <i class="fas fa-user-slash" id="modalConfirmIcon"></i>
                <h3 id="modalConfirmTitle">Confirmar acción</h3>
            </div>
            <button class="modal-close-btn" id="btnCloseConfirm"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="text-align:center;padding:24px 20px;">
            <p id="modalConfirmMsg" style="font-size:13px;color:var(--gris-oscuro);line-height:1.6;margin:0;">¿Está seguro?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-cancelar" id="btnConfirmCancelar">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="btn" id="btnConfirmAceptar" style="background:var(--rojo);color:var(--blanco);">
                <i class="fas fa-check" id="btnConfirmIconAction"></i>
                <span id="btnConfirmTexto">Confirmar</span>
            </button>
        </div>
    </div>
</div>

<!-- MODAL: CONFIRMAR ELIMINAR CAMPO DINÁMICO -->
<div class="modal-overlay" id="modalConfirmCampo">
    <div class="modal" style="max-width:420px;">
        <div class="modal-header" style="background:linear-gradient(135deg,var(--rojo) 0%,#e63200 100%);">
            <div class="modal-header-left">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Eliminar Campo Dinámico</h3>
            </div>
            <button class="modal-close-btn" id="btnCloseCampo"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="text-align:center;padding:24px 20px;">
            <p id="modalCampoMsg" style="font-size:13px;color:var(--gris-oscuro);line-height:1.6;margin:0;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-cancelar" id="btnCampoEliminarCancelar">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="btn" id="btnCampoEliminarAceptar" style="background:var(--rojo);color:var(--blanco);">
                <i class="fas fa-trash"></i> Sí, eliminar todo
            </button>
        </div>
    </div>
</div>

<div class="opciones-container" id="opcionesContainer">

    <!-- SECCIÓN 1: OPCIONES GLOBALES -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="OPC.toggleSection(this)">
            <h3><i class="fas fa-globe"></i> Opciones Globales del Sistema</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body" id="secGlobales">
            <div class="opc-info">
                <i class="fas fa-info-circle"></i>
                <span>Estas opciones afectan al sistema completo. El nombre del sistema aparece en el Header y el control de login permite habilitar o deshabilitar el acceso al formulario de ingreso.</span>
            </div>
            <div class="opc-row">
                <div class="opc-row-label"><i class="fas fa-heading"></i> Nombre del Sistema</div>
                <div class="opc-row-control">
                    <input type="text" id="optSistemaNombre" placeholder="Escuela Internacional de Psicología | Sistema de Registros">
                </div>
            </div>
            <div class="opc-row">
                <div class="opc-row-label"><i class="fas fa-sign-in-alt"></i> Login Habilitado</div>
                <div class="opc-row-control" style="display:flex;align-items:center;">
                    <label class="toggle-switch">
                        <input type="checkbox" id="optLoginHabilitado" checked>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-status on" id="optLoginStatus">Habilitado</span>
                </div>
            </div>
            <div class="opc-row" id="rowLoginMensaje" style="display:none;">
                <div class="opc-row-label"><i class="fas fa-comment-alt"></i> Mensaje de Bloqueo</div>
                <div class="opc-row-control">
                    <textarea id="optLoginMensaje" placeholder="Mensaje que se mostrará cuando el login esté deshabilitado..."></textarea>
                </div>
            </div>
            <div class="opc-section-actions">
                <button class="opc-btn opc-btn-primary" id="btnGuardarGlobales">
                    <i class="fas fa-save"></i> Guardar Opciones Globales
                </button>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: API KEYS -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="OPC.toggleSection(this)">
            <h3><i class="fas fa-key"></i> API Keys (Conexión WordPress)</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body" id="secApiKeys">
            <div class="opc-info">
                <i class="fas fa-info-circle"></i>
                <span>Cada dominio de WordPress necesita una API Key y Secret para enviar datos al sistema. Al crear una API Key, copie el <strong>Secret</strong> ya que no se mostrará de nuevo.</span>
            </div>
            <div class="opc-api-form">
                <input type="text" id="apiNewDominio" placeholder="Ej: www.psicologiaenvivo.com">
                <button class="opc-btn opc-btn-success" id="btnCrearApiKey">
                    <i class="fas fa-plus"></i> Crear API Key
                </button>
            </div>
            <div id="apiKeysTableWrapper">
                <table class="opc-api-table">
                    <thead>
                        <tr>
                            <th>Dominio</th><th>API Key</th><th>Estado</th>
                            <th>Creada</th><th>Último Uso</th><th style="width:130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="apiKeysBody">
                        <tr><td colspan="6"><div class="opc-empty"><i class="fas fa-spinner fa-spin"></i><p>Cargando...</p></div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 3: PERMISOS POR USUARIO -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="OPC.toggleSection(this)">
            <h3><i class="fas fa-user-cog"></i> Permisos por Usuario</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body" id="secPermisos">
            <div class="opc-info">
                <i class="fas fa-info-circle"></i>
                <span>Configure permisos individuales para cada usuario. Los cambios se aplican <strong>en tiempo real</strong> sin que el usuario necesite recargar la página.</span>
            </div>
            <div class="opc-user-selector">
                <label><i class="fas fa-user"></i> Seleccionar Usuario:</label>
                <select id="permUserSelect">
                    <option value="">— Seleccione un usuario —</option>
                </select>
            </div>
            <div class="opc-permisos-wrapper" id="permisosWrapper">
                <!-- ========== DASHBOARD ========== -->
                <div class="opc-perm-group">
                    <div class="opc-perm-group-title"><i class="fas fa-th-large"></i> Dashboard</div>
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-columns"></i> Columnas visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_nombre" checked> Nombre</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_apellidos" checked> Apellidos</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_telefono" checked> Teléfono</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_correo" checked> Correo</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_asesor" checked> Asesor</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_delegado" checked> Delegado</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_curso" checked> Curso</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_pais" checked> País</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_ciudad" checked> Ciudad</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_moneda" checked> Moneda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_metodo_pago" checked> Método de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_ip" checked> IP</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_fecha" checked> Fecha</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_hora" checked> Hora</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_categoria" checked> Categoría</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_file_url" checked> File</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_formulario_id" checked> ID</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.col_web" checked> Web</label></div>
                        </div>
                    </div>
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-filter"></i> Filtros visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_asesor" checked> Asesor</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_delegado" checked> Delegado</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_curso" checked> Curso</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_pais" checked> País</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_ciudad" checked> Ciudad</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_moneda" checked> Moneda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_metodo_pago" checked> Método de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_web" checked> Web</label></div>
                            <!-- ── NUEVOS FILTROS DASHBOARD ── -->
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_formulario" checked> Formulario</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_busqueda" checked> Búsqueda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_mostrando" checked> Mostrando</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_limpiar" checked> Limpiar</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="dashboard.filtro_fecha_hora" checked> Fecha y Hora</label></div>
                        </div>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-sort"></i> Reordenar Columnas</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="dashboard.reordenar_columnas" checked><span class="toggle-slider"></span></label>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-file-excel"></i> Descargar Excel</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="dashboard.descargar_excel" checked><span class="toggle-slider"></span></label>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-pencil-alt"></i> Edición Inline</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="dashboard.edicion_inline" checked><span class="toggle-slider"></span></label>
                    </div>
                </div>

                <!-- ========== ASESORES / DELEGADOS ========== -->
                <div class="opc-perm-group">
                    <div class="opc-perm-group-title"><i class="fas fa-headset"></i> Asesores / Delegados</div>
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-columns"></i> Columnas visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_nombre" checked> Nombre</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_apellidos" checked> Apellidos</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_telefono" checked> Teléfono</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_correo" checked> Correo</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_asesor" checked> Asesor</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_delegado" checked> Delegado</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_curso" checked> Curso</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_pais" checked> País</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_ciudad" checked> Ciudad</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_moneda" checked> Moneda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_metodo_pago" checked> Método de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_ip" checked> IP</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_fecha" checked> Fecha</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_hora" checked> Hora</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_categoria" checked> Categoría</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_file_url" checked> File</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_formulario_id" checked> ID</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.col_web" checked> Web</label></div>
                        </div>
                    </div>
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-filter"></i> Filtros visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_curso" checked> Curso</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_pais" checked> País</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_ciudad" checked> Ciudad</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_moneda" checked> Moneda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_metodo_pago" checked> Método de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_web" checked> Web</label></div>
                            <!-- ── NUEVOS FILTROS ASESORES/DELEGADOS ── -->
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_formulario" checked> Formulario</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_busqueda" checked> Búsqueda</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_mostrando" checked> Mostrando</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_limpiar" checked> Limpiar</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="asesores_delegados.filtro_fecha_hora" checked> Fecha y Hora</label></div>
                        </div>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-sort"></i> Reordenar Columnas</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="asesores_delegados.reordenar_columnas" checked><span class="toggle-slider"></span></label>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-file-excel"></i> Descargar Excel</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="asesores_delegados.descargar_excel" checked><span class="toggle-slider"></span></label>
                    </div>
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-pencil-alt"></i> Edición Inline</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="asesores_delegados.edicion_inline" checked><span class="toggle-slider"></span></label>
                    </div>
                </div>

                <!-- ========== ESTADÍSTICAS ========== -->
                <div class="opc-perm-group">
                    <div class="opc-perm-group-title"><i class="fas fa-chart-bar"></i> Estadísticas</div>

                    <!-- Acceso general -->
                    <div class="opc-perm-item">
                        <span class="opc-perm-item-label"><i class="fas fa-chart-pie"></i> Acceso a Estadísticas</span>
                        <label class="toggle-switch"><input type="checkbox" data-perm="estadisticas.acceso_estadisticas" checked><span class="toggle-slider"></span></label>
                    </div>

                    <!-- Pestañas (una por una) -->
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-folder"></i> Pestañas</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.tab_general" checked> General</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.tab_asesor" checked> Asesor</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.tab_delegado" checked> Delegado</label></div>
                        </div>
                    </div>

                    <!-- Filtros visibles -->
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-filter"></i> Filtros visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_formulario" checked> Formulario / Selector</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_fecha_hora" checked> Fecha y Hora</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_limpiar" checked> Limpiar</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_curso" checked> Sub-filtro Curso</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_pais" checked> Sub-filtro País</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_ciudad" checked> Sub-filtro Ciudad</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_metodo_pago" checked> Sub-filtro Método de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.filtro_web" checked> Sub-filtro Web</label></div>
                        </div>
                    </div>

                    <!-- Gráficos visibles -->
                    <div class="opc-perm-subgroup">
                        <div class="opc-perm-subgroup-title"><i class="fas fa-chart-line"></i> Gráficos visibles</div>
                        <div class="opc-perm-grid">
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_tendencia" checked> Tendencia</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_asesores" checked> Asesores</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_delegados" checked> Delegados</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_cursos" checked> Cursos</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_paises" checked> Países</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_metodo_pago" checked> Métodos de Pago</label></div>
                            <div class="opc-perm-item-mini"><label><input type="checkbox" data-perm="estadisticas.grafico_horas" checked> Horas del Día</label></div>
                        </div>
                    </div>
                </div>

                <div class="opc-section-actions">
                    <button class="opc-btn opc-btn-primary" id="btnGuardarPermisos">
                        <i class="fas fa-save"></i> Guardar Permisos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 4: SUSPENDER / ACTIVAR CONSULTORES -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="OPC.toggleSection(this)">
            <h3><i class="fas fa-user-slash"></i> Suspender / Activar Consultores</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body" id="secConsultores">
            <div class="opc-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Al suspender un consultor, este no podrá ingresar al sistema. Sus datos no se eliminan. Puede volver a activarlo en cualquier momento.</span>
            </div>
            <div id="consultoresListOpc">
                <div class="opc-empty"><i class="fas fa-spinner fa-spin"></i><p>Cargando consultores...</p></div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- SECCIÓN 5: CAMPOS DINÁMICOS (NUEVA)               -->
    <!-- ══════════════════════════════════════════════════ -->
    <div class="opc-section">
        <div class="opc-section-header" onclick="OPC.toggleSection(this)">
            <h3><i class="fas fa-sliders-h"></i> Campos Dinámicos</h3>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
        <div class="opc-section-body" id="secCamposDinamicos">
            <div class="opc-info">
                <i class="fas fa-info-circle"></i>
                <span>Los campos dinámicos se almacenan en <code>campos_extra</code> y llegan desde el formulario de WordPress. Puedes crearlos antes o después de que lleguen los datos — los registros existentes se mostrarán automáticamente al crear el campo.</span>
            </div>

            <!-- Formulario agregar / editar campo -->
            <div class="opc-cd-form" id="cdForm">
                <div class="opc-row">
                    <div class="opc-row-label"><i class="fas fa-code"></i> Nombre interno</div>
                    <div class="opc-row-control">
                        <input type="text" id="cdNombreCampo" placeholder="Ej: nivel_ingles" style="max-width:250px;">
                        <span style="font-size:11px;color:#6b7280;margin-left:8px;">Solo letras, números y guion bajo. Debe coincidir exactamente con el campo del formulario.</span>
                    </div>
                </div>
                <div class="opc-row">
                    <div class="opc-row-label"><i class="fas fa-tag"></i> Etiqueta visible</div>
                    <div class="opc-row-control">
                        <input type="text" id="cdNombreMostrar" placeholder="Ej: Nivel de Inglés" style="max-width:250px;">
                    </div>
                </div>
                <div class="opc-row">
                    <div class="opc-row-label"><i class="fas fa-database"></i> Tipo de dato</div>
                    <div class="opc-row-control">
                        <select id="cdTipoDato" style="width:180px;" class="filter-select">
                            <option value="texto">Texto</option>
                            <option value="numero">Número</option>
                            <option value="lista">Lista</option>
                            <option value="fecha">Fecha</option>
                        </select>
                    </div>
                </div>
                <div class="opc-row">
                    <div class="opc-row-label"><i class="fas fa-toggle-on"></i> Opciones</div>
                    <div class="opc-row-control" style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;">
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                            <label class="toggle-switch" style="margin:0;">
                                <input type="checkbox" id="cdMostrarLista" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            Columna en tabla
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                            <label class="toggle-switch" style="margin:0;">
                                <input type="checkbox" id="cdMostrarFiltro" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            Filtro tipo lista
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                            <label class="toggle-switch" style="margin:0;">
                                <input type="checkbox" id="cdMostrarEstadisticas">
                                <span class="toggle-slider"></span>
                            </label>
                            Estadísticas
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                            <label class="toggle-switch" style="margin:0;">
                                <input type="checkbox" id="cdMostrarExcel" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            Exportar Excel
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                            <label class="toggle-switch" style="margin:0;">
                                <input type="checkbox" id="cdEsObligatorio">
                                <span class="toggle-slider"></span>
                            </label>
                            Obligatorio
                        </label>
                    </div>
                </div>
                <input type="hidden" id="cdEditId" value="">
                <div class="opc-section-actions" style="padding-top:8px;">
                    <button class="opc-btn opc-btn-success" id="btnGuardarCampo">
                        <i class="fas fa-plus"></i> Agregar Campo
                    </button>
                    <button class="opc-btn" id="btnCancelarCampo" style="display:none;margin-left:8px;">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>

            <!-- Tabla de campos configurados -->
            <div style="margin-top:16px;">
                <h4 style="font-size:13px;font-weight:600;color:var(--gris-oscuro);margin-bottom:8px;"><i class="fas fa-list"></i> Campos Configurados</h4>
                <div id="camposDinamicosTabla">
                    <div class="opc-empty"><i class="fas fa-spinner fa-spin"></i><p>Cargando...</p></div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
var OPC = (function () {
    'use strict';

    var CSRF = document.getElementById('csrfTokenDash') ? document.getElementById('csrfTokenDash').value : '';
    var selectedUserId = 0;
    var pendingConfirm = { uid: 0, estado: '' };
    var pendingDeleteCampo = { id: 0, nombre: '' };

    // ── FIX: guardar el timer para poder limpiarlo al navegar ──
    var _consultoresTimer = null;

    function toggleSection(header) {
        header.classList.toggle('collapsed');
        header.nextElementSibling.classList.toggle('collapsed');
    }

    function esc(str) {
        if (!str) return '';
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function init() {
        cargarOpcionesGlobales();
        cargarApiKeys();
        cargarUsuarios();
        cargarConsultoresOpc();
        cargarCamposDinamicos();
        bindEvents();

        // ── FIX: guardar referencia del timer ──
        _consultoresTimer = setInterval(cargarConsultoresOpc, 8000);

        // ── FIX: limpiar el timer cuando se navegue a otra página ──
        var _self = document.getElementById('opcionesContainer');
        if (_self) {
            var observer = new MutationObserver(function () {
                if (!document.getElementById('opcionesContainer')) {
                    clearInterval(_consultoresTimer);
                    observer.disconnect();
                }
            });
            var contentArea = document.getElementById('contentArea');
            if (contentArea) {
                observer.observe(contentArea, { childList: true, subtree: false });
            }
        }
    }

    function bindEvents() {
        var toggleLogin = document.getElementById('optLoginHabilitado');
        if (toggleLogin) {
            toggleLogin.addEventListener('change', function () {
                var status = document.getElementById('optLoginStatus');
                var rowMsg = document.getElementById('rowLoginMensaje');
                if (this.checked) {
                    status.textContent = 'Habilitado';
                    status.className = 'toggle-status on';
                    rowMsg.style.display = 'none';
                } else {
                    status.textContent = 'Deshabilitado';
                    status.className = 'toggle-status off';
                    rowMsg.style.display = '';
                }
            });
        }

        var btnG = document.getElementById('btnGuardarGlobales');
        if (btnG) btnG.addEventListener('click', guardarGlobales);

        var btnA = document.getElementById('btnCrearApiKey');
        if (btnA) btnA.addEventListener('click', crearApiKey);

        var sel = document.getElementById('permUserSelect');
        if (sel) sel.addEventListener('change', function () {
            selectedUserId = parseInt(this.value) || 0;
            if (selectedUserId > 0) {
                cargarPermisosUsuario(selectedUserId);
                document.getElementById('permisosWrapper').classList.add('active');
            } else {
                document.getElementById('permisosWrapper').classList.remove('active');
            }
        });

        var btnP = document.getElementById('btnGuardarPermisos');
        if (btnP) btnP.addEventListener('click', guardarPermisos);

        var btnCancelar = document.getElementById('btnConfirmCancelar');
        if (btnCancelar) btnCancelar.addEventListener('click', cerrarModalConfirm);

        var btnClose = document.getElementById('btnCloseConfirm');
        if (btnClose) btnClose.addEventListener('click', cerrarModalConfirm);

        var btnAceptar = document.getElementById('btnConfirmAceptar');
        if (btnAceptar) btnAceptar.addEventListener('click', ejecutarToggleConsultor);

        var overlay = document.getElementById('modalConfirmConsultor');
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) cerrarModalConfirm();
            });
        }

        // Campos dinámicos events
        var btnGuardarCampo = document.getElementById('btnGuardarCampo');
        if (btnGuardarCampo) btnGuardarCampo.addEventListener('click', guardarCampoDinamico);

        var btnCancelarCampo = document.getElementById('btnCancelarCampo');
        if (btnCancelarCampo) btnCancelarCampo.addEventListener('click', cancelarEditarCampo);

        var btnCloseCampo = document.getElementById('btnCloseCampo');
        if (btnCloseCampo) btnCloseCampo.addEventListener('click', cerrarModalCampo);

        var btnCampoEliminarCancelar = document.getElementById('btnCampoEliminarCancelar');
        if (btnCampoEliminarCancelar) btnCampoEliminarCancelar.addEventListener('click', cerrarModalCampo);

        var btnCampoEliminarAceptar = document.getElementById('btnCampoEliminarAceptar');
        if (btnCampoEliminarAceptar) btnCampoEliminarAceptar.addEventListener('click', ejecutarEliminarCampo);

        var overlayCampo = document.getElementById('modalConfirmCampo');
        if (overlayCampo) {
            overlayCampo.addEventListener('click', function (e) {
                if (e.target === overlayCampo) cerrarModalCampo();
            });
        }
    }

    // MODAL CONFIRMAR CONSULTOR
    function abrirModalConfirm(uid, estado, nombre) {
        pendingConfirm.uid = uid;
        pendingConfirm.estado = estado;

        var header       = document.getElementById('modalConfirmHeader');
        var icon         = document.getElementById('modalConfirmIcon');
        var title        = document.getElementById('modalConfirmTitle');
        var msg          = document.getElementById('modalConfirmMsg');
        var btnAceptar   = document.getElementById('btnConfirmAceptar');
        var btnTexto     = document.getElementById('btnConfirmTexto');
        var btnIconAction= document.getElementById('btnConfirmIconAction');

        if (estado === 'suspendido') {
            header.style.background = 'linear-gradient(135deg, var(--rojo) 0%, #e63200 100%)';
            icon.className = 'fas fa-user-slash';
            title.textContent = 'Suspender Consultor';
            msg.innerHTML = '¿Está seguro que desea <strong>suspender</strong> a<br><strong>' + esc(nombre) + '</strong>?<br><br><span style="font-size:11px;color:#92400e;">El consultor no podrá ingresar al sistema.</span>';
            btnAceptar.style.background = 'var(--rojo)';
            btnTexto.textContent = 'Suspender';
            btnIconAction.className = 'fas fa-user-slash';
        } else {
            header.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
            icon.className = 'fas fa-user-check';
            title.textContent = 'Activar Consultor';
            msg.innerHTML = '¿Está seguro que desea <strong>activar</strong> a<br><strong>' + esc(nombre) + '</strong>?<br><br><span style="font-size:11px;color:#059669;">El consultor podrá ingresar al sistema nuevamente.</span>';
            btnAceptar.style.background = '#059669';
            btnTexto.textContent = 'Activar';
            btnIconAction.className = 'fas fa-user-check';
        }

        document.getElementById('modalConfirmConsultor').classList.add('active');
    }

    function cerrarModalConfirm() {
        document.getElementById('modalConfirmConsultor').classList.remove('active');
        pendingConfirm.uid = 0;
        pendingConfirm.estado = '';
    }

    function ejecutarToggleConsultor() {
        if (pendingConfirm.uid <= 0) return;
        var uid    = pendingConfirm.uid;
        var estado = pendingConfirm.estado;
        cerrarModalConfirm();

        var fd = new FormData();
        fd.append('accion',     'toggle_consultor');
        fd.append('usuario_id', uid);
        fd.append('estado',     estado);
        fd.append('csrf_token', CSRF);

        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (typeof mostrarToast === 'function') mostrarToast(data.message, 'success');
                cargarConsultoresOpc();
                cargarUsuarios();
            } else {
                if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error');
            }
        })
        .catch(function () { if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error'); });
    }

    // OPCIONES GLOBALES
    function cargarOpcionesGlobales() {
        fetch('includes/ajax/opciones_sistema.php?accion=get_globales', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                var o = data.opciones;
                document.getElementById('optSistemaNombre').value = o.sistema_nombre || '';
                var loginHab  = document.getElementById('optLoginHabilitado');
                var isEnabled = (o.login_habilitado !== '0');
                loginHab.checked = isEnabled;
                var status = document.getElementById('optLoginStatus');
                status.textContent = isEnabled ? 'Habilitado' : 'Deshabilitado';
                status.className   = 'toggle-status ' + (isEnabled ? 'on' : 'off');
                document.getElementById('rowLoginMensaje').style.display = isEnabled ? 'none' : '';
                document.getElementById('optLoginMensaje').value = o.login_mensaje || '';
            }
        })
        .catch(function (err) { console.error('Error cargando opciones globales:', err); });
    }

    function guardarGlobales() {
        var btn = document.getElementById('btnGuardarGlobales');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        var fd = new FormData();
        fd.append('accion',          'save_globales');
        fd.append('sistema_nombre',  document.getElementById('optSistemaNombre').value.trim());
        fd.append('login_habilitado',document.getElementById('optLoginHabilitado').checked ? 1 : 0);
        fd.append('login_mensaje',   document.getElementById('optLoginMensaje').value.trim());
        fd.append('csrf_token',      CSRF);

        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Opciones Globales';
            if (data.success) {
                if (typeof mostrarToast === 'function') mostrarToast('Opciones globales guardadas', 'success');
                var nuevoNombre = document.getElementById('optSistemaNombre').value.trim();
                var headerH1 = document.querySelector('.header-title h1');
                if (headerH1) headerH1.textContent = nuevoNombre;
                document.title = nuevoNombre;
            } else {
                if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Opciones Globales';
            if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error');
        });
    }

    // API KEYS
    function cargarApiKeys() {
        fetch('includes/ajax/opciones_sistema.php?accion=get_api_keys', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) { if (data.success) renderApiKeys(data.api_keys); })
        .catch(function (err) { console.error('Error cargando API Keys:', err); });
    }

    function renderApiKeys(keys) {
        var tbody = document.getElementById('apiKeysBody');
        if (!keys || keys.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6"><div class="opc-empty"><i class="fas fa-key"></i><p>No hay API Keys registradas</p></div></td></tr>';
            return;
        }
        var html = '';
        keys.forEach(function (k) {
            var isActive    = k.activo == 1;
            var statusClass = isActive ? 'api-status-active' : 'api-status-inactive';
            var statusText  = isActive ? 'Activa' : 'Inactiva';
            var toggleIcon  = isActive ? 'fa-pause' : 'fa-play';
            var toggleClass = isActive ? 'opc-btn-danger' : 'opc-btn-success';
            var ultimoUso   = k.ultimo_uso || 'Nunca';
            html += '<tr>';
            html += '<td><strong>' + esc(k.dominio) + '</strong></td>';
            html += '<td><span class="api-key-text" title="Clic para copiar" onclick="OPC.copiarTexto(\'' + esc(k.api_key) + '\')">' + esc(k.api_key.substring(0, 16)) + '...</span></td>';
            html += '<td><span class="' + statusClass + '">' + statusText + '</span></td>';
            html += '<td>' + esc(k.fecha_creacion) + '</td>';
            html += '<td>' + esc(ultimoUso) + '</td>';
            html += '<td>';
            html += '<button class="opc-btn opc-btn-sm ' + toggleClass + '" onclick="OPC.toggleApiKey(' + k.id + ',' + (isActive ? 0 : 1) + ')"><i class="fas ' + toggleIcon + '"></i></button> ';
            html += '<button class="opc-btn opc-btn-sm opc-btn-danger" onclick="OPC.eliminarApiKey(' + k.id + ',\'' + esc(k.dominio) + '\')"><i class="fas fa-trash"></i></button>';
            html += '</td></tr>';
        });
        tbody.innerHTML = html;
    }

    function crearApiKey() {
        var dominio = document.getElementById('apiNewDominio').value.trim();
        if (!dominio) { if (typeof mostrarToast === 'function') mostrarToast('Ingrese un dominio', 'error'); return; }
        var fd = new FormData();
        fd.append('accion', 'create_api_key'); fd.append('dominio', dominio); fd.append('csrf_token', CSRF);
        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                document.getElementById('apiNewDominio').value = '';
                if (typeof mostrarToast === 'function') mostrarToast('API Key creada. Secret: ' + data.api_secret.substring(0, 20) + '... (copiado al portapapeles)', 'success', 8000);
                try { navigator.clipboard.writeText(data.api_secret); } catch (e) {}
                cargarApiKeys();
            } else { if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error'); }
        })
        .catch(function () { if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error'); });
    }

    function toggleApiKey(id, activo) {
        var fd = new FormData();
        fd.append('accion', 'toggle_api_key'); fd.append('id', id); fd.append('activo', activo); fd.append('csrf_token', CSRF);
        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) { if (typeof mostrarToast === 'function') mostrarToast(data.message, 'success'); cargarApiKeys(); }
            else { if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error'); }
        });
    }

    function eliminarApiKey(id, dominio) {
        if (!confirm('¿Eliminar la API Key del dominio "' + dominio + '"?')) return;
        var fd = new FormData();
        fd.append('accion', 'delete_api_key'); fd.append('id', id); fd.append('csrf_token', CSRF);
        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) { if (typeof mostrarToast === 'function') mostrarToast(data.message, 'success'); cargarApiKeys(); }
            else { if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error'); }
        });
    }

    function copiarTexto(texto) {
        try { navigator.clipboard.writeText(texto); if (typeof mostrarToast === 'function') mostrarToast('Copiado al portapapeles', 'success'); }
        catch (e) { if (typeof mostrarToast === 'function') mostrarToast('No se pudo copiar', 'error'); }
    }

    // PERMISOS
    function cargarUsuarios() {
        fetch('includes/ajax/opciones_sistema.php?accion=get_usuarios', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                var sel = document.getElementById('permUserSelect');
                sel.innerHTML = '<option value="">— Seleccione un usuario —</option>';
                data.usuarios.forEach(function (u) {
                    var badge  = u.tipo === 'administrador' ? ' [Admin]' : ' [Consultor]';
                    var estado = u.estado === 'suspendido' ? ' (Suspendido)' : '';
                    sel.innerHTML += '<option value="' + u.id + '">' + esc(u.nombre + ' ' + u.apellidos) + badge + estado + '</option>';
                });
            }
        })
        .catch(function (err) { console.error('Error cargando usuarios:', err); });
    }

    function cargarPermisosUsuario(uid) {
        fetch('includes/ajax/opciones_sistema.php?accion=get_permisos&usuario_id=' + uid, { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                var permisos = data.permisos || {};
                document.querySelectorAll('[data-perm]').forEach(function (cb) {
                    var parts   = cb.getAttribute('data-perm').split('.');
                    var seccion = parts[0];
                    var permiso = parts[1];
                    var val = true;
                    if (permisos[seccion] !== undefined && permisos[seccion][permiso] !== undefined) val = permisos[seccion][permiso];
                    cb.checked = val;
                });
            }
        })
        .catch(function (err) { console.error('Error cargando permisos:', err); });
    }

    function guardarPermisos() {
        if (selectedUserId <= 0) { if (typeof mostrarToast === 'function') mostrarToast('Seleccione un usuario', 'error'); return; }
        var btn = document.getElementById('btnGuardarPermisos');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        var permisos = {};
        document.querySelectorAll('[data-perm]').forEach(function (cb) {
            var parts = cb.getAttribute('data-perm').split('.');
            if (!permisos[parts[0]]) permisos[parts[0]] = {};
            permisos[parts[0]][parts[1]] = cb.checked;
        });

        var fd = new FormData();
        fd.append('accion', 'save_permisos'); fd.append('usuario_id', selectedUserId);
        fd.append('permisos', JSON.stringify(permisos)); fd.append('csrf_token', CSRF);

        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Permisos';
            if (data.success) { if (typeof mostrarToast === 'function') mostrarToast('Permisos guardados correctamente. Se aplican en tiempo real.', 'success'); }
            else { if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error'); }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Permisos';
            if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error');
        });
    }

    // CONSULTORES
    function cargarConsultoresOpc() {
        fetch('includes/ajax/consultores.php?action=listar', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) renderConsultoresOpc(data.consultores);
        })
        .catch(function (err) { console.error('cargarConsultoresOpc:', err); });
    }

    function renderConsultoresOpc(consultores) {
        var container = document.getElementById('consultoresListOpc');
        if (!container) return;
        if (!consultores || consultores.length === 0) {
            container.innerHTML = '<div class="opc-empty"><i class="fas fa-users"></i><p>No hay consultores registrados</p></div>';
            return;
        }
        var html = '<table class="opc-api-table"><thead><tr><th>Nombre</th><th>Usuario</th><th>País</th><th>Estado</th><th style="width:120px;">Acción</th></tr></thead><tbody>';
        consultores.forEach(function (c) {
            var isActive    = c.estado === 'activo';
            var estadoClass = isActive ? 'api-status-active' : 'api-status-inactive';
            var estadoText  = isActive ? 'Activo' : 'Suspendido';
            var btnText     = isActive ? 'Suspender' : 'Activar';
            var btnClass    = isActive ? 'opc-btn-danger' : 'opc-btn-success';
            var btnIcon     = isActive ? 'fa-user-slash' : 'fa-user-check';
            var nuevoEstado = isActive ? 'suspendido' : 'activo';
            var nombreCompleto = (c.nombre + ' ' + c.apellidos).replace(/'/g, "\\'");
            html += '<tr>';
            html += '<td><strong>' + esc(c.nombre + ' ' + c.apellidos) + '</strong></td>';
            html += '<td>' + esc(c.usuario) + '</td>';
            html += '<td>' + esc(c.pais) + '</td>';
            html += '<td><span class="' + estadoClass + '">' + estadoText + '</span></td>';
            html += '<td><button class="opc-btn opc-btn-sm ' + btnClass + '" onclick="OPC.toggleConsultor(' + c.id + ',\'' + nuevoEstado + '\',\'' + nombreCompleto + '\')"><i class="fas ' + btnIcon + '"></i> ' + btnText + '</button></td>';
            html += '</tr>';
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }

    function toggleConsultor(uid, estado, nombre) {
        abrirModalConfirm(uid, estado, nombre || 'este consultor');
    }

    // ══════════════════════════════════════════════════
    // CAMPOS DINÁMICOS
    // ══════════════════════════════════════════════════
    function cargarCamposDinamicos() {
        fetch('includes/ajax/opciones_sistema.php?accion=get_campos_dinamicos', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) renderCamposDinamicos(data.campos);
            else document.getElementById('camposDinamicosTabla').innerHTML = '<div class="opc-empty"><i class="fas fa-exclamation-circle"></i><p>Error cargando campos</p></div>';
        })
        .catch(function () {
            document.getElementById('camposDinamicosTabla').innerHTML = '<div class="opc-empty"><i class="fas fa-exclamation-circle"></i><p>Error de conexión</p></div>';
        });
    }

    function renderCamposDinamicos(campos) {
        var cont = document.getElementById('camposDinamicosTabla');
        if (!campos || campos.length === 0) {
            cont.innerHTML = '<div class="opc-empty"><i class="fas fa-sliders-h"></i><p>No hay campos dinámicos configurados aún.</p></div>';
            return;
        }
        var html = '<table class="opc-api-table"><thead><tr>'
            + '<th>Nombre interno</th><th>Etiqueta</th><th>Tipo</th>'
            + '<th style="text-align:center;">Tabla</th>'
            + '<th style="text-align:center;">Filtro</th>'
            + '<th style="text-align:center;">Stats</th>'
            + '<th style="text-align:center;">Excel</th>'
            + '<th style="text-align:center;">Activo</th>'
            + '<th style="width:110px;">Acciones</th>'
            + '</tr></thead><tbody>';
        campos.forEach(function (c) {
            var icon = function(v) { return v == 1 ? '<span style="color:#059669;font-size:14px;">✔</span>' : '<span style="color:#d1d5db;font-size:14px;">✖</span>'; };
            var activoClass = c.activo == 1 ? 'api-status-active' : 'api-status-inactive';
            var activoText  = c.activo == 1 ? 'Activo' : 'Inactivo';
            html += '<tr>';
            html += '<td><code style="font-size:11px;">' + esc(c.nombre_campo) + '</code></td>';
            html += '<td><strong>' + esc(c.nombre_mostrar) + '</strong></td>';
            html += '<td><span style="font-size:11px;background:#f3f4f6;padding:2px 6px;border-radius:4px;">' + esc(c.tipo_dato) + '</span></td>';
            html += '<td style="text-align:center;">' + icon(c.mostrar_lista) + '</td>';
            html += '<td style="text-align:center;">' + icon(c.mostrar_filtro) + '</td>';
            html += '<td style="text-align:center;">' + icon(c.mostrar_estadisticas) + '</td>';
            html += '<td style="text-align:center;">' + icon(c.mostrar_excel) + '</td>';
            html += '<td style="text-align:center;"><span class="' + activoClass + '">' + activoText + '</span></td>';
            html += '<td>';
            html += '<button class="opc-btn opc-btn-sm" style="background:#2271b1;color:#fff;" onclick="OPC.editarCampo(' + c.id + ')"><i class="fas fa-pencil-alt"></i></button> ';
            html += '<button class="opc-btn opc-btn-sm opc-btn-danger" onclick="OPC.confirmarEliminarCampo(' + c.id + ',\'' + esc(c.nombre_campo) + '\',\'' + esc(c.nombre_mostrar) + '\')"><i class="fas fa-trash"></i></button>';
            html += '</td></tr>';
        });
        html += '</tbody></table>';
        cont.innerHTML = html;
    }

    function guardarCampoDinamico() {
        var editId       = document.getElementById('cdEditId').value;
        var nombreCampo  = document.getElementById('cdNombreCampo').value.trim();
        var nombreMostrar= document.getElementById('cdNombreMostrar').value.trim();
        var tipoDato     = document.getElementById('cdTipoDato').value;
        var mostrarLista = document.getElementById('cdMostrarLista').checked ? 1 : 0;
        var mostrarFiltro= document.getElementById('cdMostrarFiltro').checked ? 1 : 0;
        var mostrarStats = document.getElementById('cdMostrarEstadisticas').checked ? 1 : 0;
        var mostrarExcel = document.getElementById('cdMostrarExcel').checked ? 1 : 0;
        var esOblig      = document.getElementById('cdEsObligatorio').checked ? 1 : 0;

        if (!nombreMostrar) { if (typeof mostrarToast === 'function') mostrarToast('La etiqueta visible es obligatoria', 'error'); return; }
        if (!editId && !nombreCampo) { if (typeof mostrarToast === 'function') mostrarToast('El nombre interno es obligatorio', 'error'); return; }

        var accionAjax = editId ? 'update_campo_dinamico' : 'save_campo_dinamico';
        var btn = document.getElementById('btnGuardarCampo');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        var fd = new FormData();
        fd.append('accion',               accionAjax);
        fd.append('csrf_token',           CSRF);
        fd.append('nombre_mostrar',       nombreMostrar);
        fd.append('tipo_dato',            tipoDato);
        fd.append('mostrar_lista',        mostrarLista);
        fd.append('mostrar_filtro',       mostrarFiltro);
        fd.append('mostrar_estadisticas', mostrarStats);
        fd.append('mostrar_excel',        mostrarExcel);
        fd.append('es_obligatorio',       esOblig);
        if (editId) {
            fd.append('id',     editId);
            fd.append('activo', 1);
        } else {
            fd.append('nombre_campo', nombreCampo);
        }

        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = editId ? '<i class="fas fa-save"></i> Guardar Cambios' : '<i class="fas fa-plus"></i> Agregar Campo';
            if (data.success) {
                if (typeof mostrarToast === 'function') mostrarToast(data.message, 'success');
                cancelarEditarCampo();
                cargarCamposDinamicos();
            } else {
                if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Agregar Campo';
            if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error');
        });
    }

    function editarCampo(id) {
        fetch('includes/ajax/opciones_sistema.php?accion=get_campos_dinamicos', { credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            var campo = null;
            data.campos.forEach(function (c) { if (c.id == id) campo = c; });
            if (!campo) return;

            document.getElementById('cdEditId').value              = campo.id;
            document.getElementById('cdNombreCampo').value         = campo.nombre_campo;
            document.getElementById('cdNombreCampo').disabled      = true; // no editar nombre interno
            document.getElementById('cdNombreMostrar').value       = campo.nombre_mostrar;
            document.getElementById('cdTipoDato').value            = campo.tipo_dato;
            document.getElementById('cdMostrarLista').checked      = campo.mostrar_lista == 1;
            document.getElementById('cdMostrarFiltro').checked     = campo.mostrar_filtro == 1;
            document.getElementById('cdMostrarEstadisticas').checked = campo.mostrar_estadisticas == 1;
            document.getElementById('cdMostrarExcel').checked      = campo.mostrar_excel == 1;
            document.getElementById('cdEsObligatorio').checked     = campo.es_obligatorio == 1;

            var btn = document.getElementById('btnGuardarCampo');
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';

            document.getElementById('btnCancelarCampo').style.display = '';

            // Scroll al formulario
            var secBody = document.getElementById('secCamposDinamicos');
            if (secBody) secBody.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    function cancelarEditarCampo() {
        document.getElementById('cdEditId').value              = '';
        document.getElementById('cdNombreCampo').value         = '';
        document.getElementById('cdNombreCampo').disabled      = false;
        document.getElementById('cdNombreMostrar').value       = '';
        document.getElementById('cdTipoDato').value            = 'texto';
        document.getElementById('cdMostrarLista').checked      = true;
        document.getElementById('cdMostrarFiltro').checked     = true;
        document.getElementById('cdMostrarEstadisticas').checked = false;
        document.getElementById('cdMostrarExcel').checked      = true;
        document.getElementById('cdEsObligatorio').checked     = false;
        var btn = document.getElementById('btnGuardarCampo');
        btn.innerHTML = '<i class="fas fa-plus"></i> Agregar Campo';
        document.getElementById('btnCancelarCampo').style.display = 'none';
    }

    function confirmarEliminarCampo(id, nombreCampo, nombreMostrar) {
        pendingDeleteCampo.id     = id;
        pendingDeleteCampo.nombre = nombreMostrar;
        document.getElementById('modalCampoMsg').innerHTML =
            '¿Está seguro que desea eliminar el campo <strong>"' + esc(nombreMostrar) + '"</strong> (<code>' + esc(nombreCampo) + '</code>)?<br><br>' +
            '<span style="color:#dc2626;font-size:11px;">⚠️ Esta acción eliminará el campo de la configuración <strong>y borrará sus datos de TODOS los registros</strong>. Esta acción es <strong>irreversible</strong>.</span>';
        document.getElementById('modalConfirmCampo').classList.add('active');
    }

    function cerrarModalCampo() {
        document.getElementById('modalConfirmCampo').classList.remove('active');
        pendingDeleteCampo.id = 0;
        pendingDeleteCampo.nombre = '';
    }

    function ejecutarEliminarCampo() {
        if (pendingDeleteCampo.id <= 0) return;
        var id = pendingDeleteCampo.id;
        cerrarModalCampo();

        var fd = new FormData();
        fd.append('accion',      'delete_campo_dinamico');
        fd.append('id',          id);
        fd.append('csrf_token',  CSRF);

        fetch('includes/ajax/opciones_sistema.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (typeof mostrarToast === 'function') mostrarToast(data.message, 'success');
                cargarCamposDinamicos();
            } else {
                if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error', 'error');
            }
        })
        .catch(function () {
            if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error');
        });
    }

    init();

    return {
        toggleSection:         toggleSection,
        toggleApiKey:          toggleApiKey,
        eliminarApiKey:        eliminarApiKey,
        copiarTexto:           copiarTexto,
        toggleConsultor:       toggleConsultor,
        editarCampo:           editarCampo,
        confirmarEliminarCampo:confirmarEliminarCampo
    };

})();
</script>
