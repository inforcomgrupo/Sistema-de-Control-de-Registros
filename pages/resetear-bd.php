<?php
/**
 * Página: Resetear Base de Datos
 * Solo accesible por Administrador
 * ZONA PELIGROSA - Elimina TODO excepto el usuario Administrador
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
   RESETEAR BD - ESTILOS
   ===================================================== */
.reset-container {
    display: flex;
    flex-direction: column;
    gap: 0;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0 2px 20px 0;
}
.reset-container::-webkit-scrollbar { width: 5px; }
.reset-container::-webkit-scrollbar-track { background: transparent; }
.reset-container::-webkit-scrollbar-thumb { background: var(--gris-borde); border-radius: 5px; }

/* DANGER ZONE */
.danger-zone {
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
}
.danger-icon {
    font-size: 50px; color: var(--rojo); margin-bottom: 12px; display: block;
    animation: pulseIcon 2s infinite;
}
@keyframes pulseIcon {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}
.danger-title {
    font-size: 18px; font-weight: 700; color: var(--rojo); margin-bottom: 8px;
}
.danger-text {
    font-size: 12px; color: #991b1b; line-height: 1.6; max-width: 500px; margin: 0 auto 16px;
}
.danger-list {
    text-align: left; max-width: 400px; margin: 0 auto 20px; font-size: 12px;
    color: #991b1b; list-style: none; padding: 0;
}
.danger-list li {
    padding: 5px 0; display: flex; align-items: center; gap: 6px;
}
.danger-list li i { color: var(--rojo); font-size: 11px; flex-shrink: 0; }
.danger-list li.safe i { color: #059669; }
.danger-list li.safe { color: #065f46; }

.danger-confirm-box {
    max-width: 400px; margin: 0 auto;
}
.danger-confirm-label {
    font-size: 12px; font-weight: 600; color: #991b1b; margin-bottom: 6px;
    text-align: left;
}
.danger-confirm-input {
    width: 100%; padding: 10px 12px; border: 2px solid #fca5a5;
    border-radius: 6px; font-size: 14px; text-align: center;
    font-weight: 600; letter-spacing: 2px; color: var(--rojo);
    background: var(--blanco); font-family: inherit;
    transition: border-color 0.2s ease;
}
.danger-confirm-input:focus {
    outline: none; border-color: var(--rojo);
    box-shadow: 0 0 0 3px rgba(255, 54, 0, 0.15);
}
.danger-confirm-input::placeholder {
    color: #fca5a5; font-weight: 400; letter-spacing: 1px;
}

.danger-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 24px; background: var(--rojo); color: var(--blanco);
    border: none; border-radius: 6px; font-size: 14px; font-weight: 600;
    cursor: not-allowed; opacity: 0.4; transition: all 0.3s ease;
    font-family: inherit; margin-top: 14px;
}
.danger-btn.enabled {
    cursor: pointer; opacity: 1;
}
.danger-btn.enabled:hover {
    background: #cc2d00; transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 54, 0, 0.3);
}

/* MODAL CONFIRMACIÓN FINAL */
.reset-modal-overlay {
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.7); z-index: 9999;
    align-items: center; justify-content: center;
}
.reset-modal-overlay.active { display: flex; }
.reset-modal {
    background: var(--blanco); border-radius: 10px; padding: 30px;
    max-width: 420px; width: 90%; text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalIn 0.3s ease;
}
@keyframes modalIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.reset-modal-icon { font-size: 40px; color: var(--rojo); margin-bottom: 10px; }
.reset-modal-title { font-size: 16px; font-weight: 700; color: var(--rojo); margin-bottom: 8px; }
.reset-modal-text { font-size: 12px; color: var(--gris-oscuro); margin-bottom: 20px; line-height: 1.5; }
.reset-modal-actions { display: flex; gap: 10px; justify-content: center; }
.reset-modal-actions .opc-btn { padding: 8px 20px; font-size: 13px; }

/* RESULTADO */
.reset-result {
    display: none; text-align: center; padding: 30px;
}
.reset-result.active { display: block; }
.reset-result-icon { font-size: 50px; color: #059669; display: block; margin-bottom: 12px; }
.reset-result-title { font-size: 16px; font-weight: 700; color: #059669; margin-bottom: 8px; }
.reset-result-text { font-size: 12px; color: var(--gris-oscuro); line-height: 1.6; }

/* BANNER BROADCAST: avisa a otros usuarios que el admin reseteó */
.reset-broadcast-banner {
    display: none;
    background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    color: #fff;
    border-radius: 8px;
    padding: 20px 24px;
    text-align: center;
    margin-bottom: 12px;
    animation: pulseIcon 1.5s infinite;
}
.reset-broadcast-banner.active { display: block; }
.reset-broadcast-banner i { font-size: 28px; display: block; margin-bottom: 8px; }
.reset-broadcast-banner strong { font-size: 14px; display: block; }
.reset-broadcast-banner span { font-size: 12px; opacity: 0.9; }
</style>

<div class="reset-container" id="resetContainer">

    <!-- Banner de aviso a otros usuarios (broadcast) -->
    <div class="reset-broadcast-banner" id="resetBroadcastBanner">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>La Base de Datos ha sido reseteada por el Administrador.</strong>
        <span>Los datos que veías han sido eliminados. Recargando...</span>
    </div>

    <div class="opc-section">
        <div class="opc-section-header" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); cursor: default;">
            <h3><i class="fas fa-exclamation-triangle"></i> Resetear Base de Datos</h3>
        </div>
        <div class="opc-section-body">

            <!-- Zona principal -->
            <div class="danger-zone" id="dangerZone">
                <i class="fas fa-radiation danger-icon"></i>
                <div class="danger-title">⚠️ ZONA DE PELIGRO ⚠️</div>
                <p class="danger-text">
                    Esta acción es <strong>IRREVERSIBLE</strong>. Se eliminarán TODOS los datos del sistema
                    para dejarlo listo para producción.
                </p>

                <ul class="danger-list">
                    <li><i class="fas fa-trash-alt"></i> Todos los registros de formularios</li>
                    <li><i class="fas fa-trash-alt"></i> Todos los consultores</li>
                    <li><i class="fas fa-trash-alt"></i> Todos los logs del sistema</li>
                    <li><i class="fas fa-trash-alt"></i> Todas las API Keys</li>
                    <li><i class="fas fa-trash-alt"></i> Todos los permisos de usuarios</li>
                    <li><i class="fas fa-trash-alt"></i> Todos los campos dinámicos</li>
                    <li><i class="fas fa-trash-alt"></i> Opciones globales (se restauran por defecto)</li>
                    <li><i class="fas fa-trash-alt"></i> Tabla de mapeo de campos de formulario</li>
                    <li class="safe"><i class="fas fa-shield-alt"></i> <strong>Se conserva ÚNICAMENTE el usuario Administrador</strong></li>
                    <li class="safe"><i class="fas fa-shield-alt"></i> <strong>Todos los IDs inician desde 1</strong></li>
                </ul>

                <div class="danger-confirm-box">
                    <div class="danger-confirm-label">
                        Escriba <strong>RESETEAR</strong> para habilitar el botón:
                    </div>
                    <input type="text" class="danger-confirm-input" id="resetConfirmInput"
                           placeholder="Escriba RESETEAR" autocomplete="off" spellcheck="false">
                </div>

                <button class="danger-btn" id="btnResetear" disabled>
                    <i class="fas fa-skull-crossbones"></i> RESETEAR TODO
                </button>
            </div>

            <!-- Resultado (solo el admin que ejecutó el reset lo ve) -->
            <div class="reset-result" id="resetResult">
                <i class="fas fa-check-circle reset-result-icon"></i>
                <div class="reset-result-title">Base de Datos Reseteada Exitosamente</div>
                <p class="reset-result-text">
                    El sistema ha sido limpiado y está listo para producción.<br>
                    Todos los IDs inician desde 1.<br>
                    Solo se ha conservado su usuario Administrador.
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Modal de confirmación final -->
<div class="reset-modal-overlay" id="resetModalOverlay">
    <div class="reset-modal">
        <div class="reset-modal-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="reset-modal-title">¿ESTÁ COMPLETAMENTE SEGURO?</div>
        <p class="reset-modal-text">
            Esta es la <strong>última confirmación</strong>.<br>
            Se eliminarán <strong>TODOS</strong> los datos del sistema.<br>
            Esta acción <strong>NO se puede deshacer</strong>.
        </p>
        <div class="reset-modal-actions">
            <button class="opc-btn opc-btn-primary" id="btnCancelarReset">
                <i class="fas fa-times"></i> No, Cancelar
            </button>
            <button class="opc-btn opc-btn-danger" id="btnConfirmarReset">
                <i class="fas fa-skull-crossbones"></i> SÍ, RESETEAR TODO
            </button>
        </div>
    </div>
</div>

<script>
/**
 * Resetear BD - Script
 * Namespace RST
 *
 * NUEVO: polling de broadcast para detectar en tiempo real
 * si el admin ejecutó el reset mientras otro usuario tenía
 * esta página abierta.
 */
var RST = (function () {
    'use strict';

    var CSRF = document.getElementById('csrfTokenDash') ? document.getElementById('csrfTokenDash').value : '';

    // Timestamp del último reset que YA conocíamos al cargar la página
    // Se compara en el polling para detectar un reset NUEVO
    var ultimoBdReseteadaAt = null;
    var yaReseteadoPorMi    = false;  // true si fui yo quien ejecutó el reset
    var broadcastPollTimer  = null;

    function init() {
        var input = document.getElementById('resetConfirmInput');
        var btn   = document.getElementById('btnResetear');

        if (input && btn) {
            input.addEventListener('input', function () {
                var val = this.value.trim().toUpperCase();
                if (val === 'RESETEAR') {
                    btn.disabled = false;
                    btn.classList.add('enabled');
                } else {
                    btn.disabled = true;
                    btn.classList.remove('enabled');
                }
            });

            btn.addEventListener('click', function () {
                if (this.disabled) return;
                document.getElementById('resetModalOverlay').classList.add('active');
            });
        }

        var btnCancelar = document.getElementById('btnCancelarReset');
        if (btnCancelar) {
            btnCancelar.addEventListener('click', function () {
                document.getElementById('resetModalOverlay').classList.remove('active');
            });
        }

        var overlay = document.getElementById('resetModalOverlay');
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) overlay.classList.remove('active');
            });
        }

        var btnConfirmar = document.getElementById('btnConfirmarReset');
        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', ejecutarReset);
        }

        // Iniciar polling de broadcast (detecta reset hecho por otro)
        iniciarBroadcastPoll();
    }

    function ejecutarReset() {
        var btnConfirmar = document.getElementById('btnConfirmarReset');
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reseteando...';

        var fd = new FormData();
        fd.append('accion',       'resetear_todo');
        fd.append('confirmacion', 'RESETEAR');
        fd.append('csrf_token',   CSRF);

        fetch('includes/ajax/resetear_bd.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('resetModalOverlay').classList.remove('active');

            if (data.success) {
                yaReseteadoPorMi = true;
                document.getElementById('dangerZone').style.display = 'none';
                document.getElementById('resetResult').classList.add('active');
                if (typeof mostrarToast === 'function') mostrarToast('Base de datos reseteada exitosamente', 'success', 6000);
            } else {
                btnConfirmar.disabled = false;
                btnConfirmar.innerHTML = '<i class="fas fa-skull-crossbones"></i> SÍ, RESETEAR TODO';
                if (typeof mostrarToast === 'function') mostrarToast(data.message || 'Error al resetear', 'error');
            }
        })
        .catch(function () {
            document.getElementById('resetModalOverlay').classList.remove('active');
            btnConfirmar.disabled = false;
            btnConfirmar.innerHTML = '<i class="fas fa-skull-crossbones"></i> SÍ, RESETEAR TODO';
            if (typeof mostrarToast === 'function') mostrarToast('Error de conexión', 'error');
        });
    }

    // =====================================================
    // BROADCAST POLL
    // Consulta cada 3 seg si la BD fue reseteada por otro
    // usuario administrador. Si detecta un timestamp nuevo,
    // muestra el banner de aviso y recarga la página.
    // =====================================================
    function iniciarBroadcastPoll() {
        // Primera consulta inmediata para capturar el estado actual
        // (sin reaccionar, solo guardando el baseline)
        consultarBroadcast(true);

        broadcastPollTimer = setInterval(function () {
            if (yaReseteadoPorMi) return; // yo lo hice, no reaccionar
            consultarBroadcast(false);
        }, 3000);
    }

    function consultarBroadcast(esBaseline) {
        fetch(
            "includes/ajax/opciones_sistema.php?accion=get_bd_reseteada_at",
            { credentials: 'same-origin' }
        )
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;

            var ts = data.bd_reseteada_at || null;

            if (esBaseline) {
                // Solo guardamos el valor inicial, no reaccionamos
                ultimoBdReseteadaAt = ts;
                return;
            }

            // Si el timestamp cambió respecto al que teníamos → hubo un reset nuevo
            if (ts && ts !== ultimoBdReseteadaAt) {
                ultimoBdReseteadaAt = ts;

                // Mostrar banner de aviso
                var banner = document.getElementById('resetBroadcastBanner');
                if (banner) banner.classList.add('active');

                // Ocultar la zona de peligro para evitar confusión
                var dz = document.getElementById('dangerZone');
                if (dz) dz.style.display = 'none';

                // Detener el polling
                if (broadcastPollTimer) clearInterval(broadcastPollTimer);

                // Recargar tras 3 segundos para que el usuario vea el banner
                if (typeof mostrarToast === 'function') {
                    mostrarToast('La BD fue reseteada. Recargando página...', 'error', 4000);
                }
                setTimeout(function () { window.location.reload(); }, 4000);
            }
        })
        .catch(function () {});
    }

    window.addEventListener('beforeunload', function () {
        if (broadcastPollTimer) clearInterval(broadcastPollTimer);
    });

    init();
    return {};
})();
</script>
