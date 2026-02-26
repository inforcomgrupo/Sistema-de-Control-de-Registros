<?php
/**
 * AJAX: Obtener permisos del usuario autenticado
 * Se consulta desde dashboard-main, asesores, delegados, estadísticas
 * para aplicar permisos EN TIEMPO REAL.
 *
 * También actúa como watchdog de sesión:
 * si el usuario fue eliminado de la BD (p.ej. tras un Reset),
 * o fue SUSPENDIDO por el admin, devuelve session_invalida: true
 * para que el frontend lo expulse.
 */

define('SISTEMA_REGISTROS', true);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

iniciarSesionSegura();

if (!estaAutenticado()) {
    echo json_encode([
        'success'          => false,
        'session_invalida' => true,
        'message'          => 'Sesión no válida'
    ]);
    exit;
}

/**
 * Permisos por defecto: TODO habilitado
 */
function getPermisosDefault() {
    return [
        'dashboard' => [
            'col_nombre' => true, 'col_apellidos' => true, 'col_telefono' => true,
            'col_correo' => true, 'col_asesor' => true, 'col_delegado' => true,
            'col_curso' => true, 'col_pais' => true, 'col_ciudad' => true,
            'col_moneda' => true, 'col_metodo_pago' => true, 'col_ip' => true,
            'col_fecha' => true, 'col_hora' => true, 'col_categoria' => true,
            'col_file_url' => true, 'col_formulario_id' => true, 'col_web' => true,
            'filtro_asesor' => true, 'filtro_delegado' => true, 'filtro_curso' => true,
            'filtro_pais' => true, 'filtro_ciudad' => true, 'filtro_moneda' => true,
            'filtro_metodo_pago' => true, 'filtro_web' => true,
            // ── NUEVOS FILTROS ──
            'filtro_formulario' => true, 'filtro_busqueda' => true,
            'filtro_mostrando' => true, 'filtro_limpiar' => true, 'filtro_fecha_hora' => true,
            'reordenar_columnas' => true, 'descargar_excel' => true, 'edicion_inline' => true
        ],
        'asesores_delegados' => [
            'col_nombre' => true, 'col_apellidos' => true, 'col_telefono' => true,
            'col_correo' => true, 'col_asesor' => true, 'col_delegado' => true,
            'col_curso' => true, 'col_pais' => true, 'col_ciudad' => true,
            'col_moneda' => true, 'col_metodo_pago' => true, 'col_ip' => true,
            'col_fecha' => true, 'col_hora' => true, 'col_categoria' => true,
            'col_file_url' => true, 'col_formulario_id' => true, 'col_web' => true,
            'filtro_curso' => true, 'filtro_pais' => true, 'filtro_ciudad' => true,
            'filtro_moneda' => true, 'filtro_metodo_pago' => true, 'filtro_web' => true,
            // ── NUEVOS FILTROS ──
            'filtro_formulario' => true, 'filtro_busqueda' => true,
            'filtro_mostrando' => true, 'filtro_limpiar' => true, 'filtro_fecha_hora' => true,
            'reordenar_columnas' => true, 'descargar_excel' => true, 'edicion_inline' => true
        ],
        'estadisticas' => [
            'acceso_estadisticas' => true,
            'filtro_fecha' => true, 'filtro_asesor' => true, 'filtro_delegado' => true,
            'filtro_curso' => true, 'filtro_pais' => true, 'filtro_ciudad' => true,
            'filtro_moneda' => true, 'filtro_metodo_pago' => true,
            'filtro_categoria' => true, 'filtro_id' => true,
            // ── NUEVOS FILTROS ──
            'filtro_formulario' => true, 'filtro_busqueda' => true,
            'filtro_mostrando' => true, 'filtro_limpiar' => true, 'filtro_fecha_hora_est' => true
        ]
    ];
}

try {
    $db       = Database::getInstance()->getConnection();
    $userId   = (int)$_SESSION['user_id'];
    $userTipo = $_SESSION['user_tipo'];

    // =====================================================
    // WATCHDOG: verificar que el usuario existe en BD
    // y que su estado es 'activo'.
    // ── FIX: ahora también expulsa a usuarios SUSPENDIDOS ──
    // =====================================================
    $stmtCheck = $db->prepare("SELECT id, estado FROM usuarios WHERE id = :id LIMIT 1");
    $stmtCheck->execute([':id' => $userId]);
    $userRow = $stmtCheck->fetch();

    if (!$userRow || $userRow['estado'] !== 'activo') {
        // Usuario eliminado o suspendido → destruir sesión y avisar al JS
        session_destroy();
        echo json_encode([
            'success'          => false,
            'session_invalida' => true,
            'message'          => 'Tu sesión ha sido cerrada porque tu cuenta ya no existe o fue suspendida.'
        ]);
        exit;
    }

    // Administrador tiene todos los permisos
    if ($userTipo === 'administrador') {
        echo json_encode([
            'success'  => true,
            'es_admin' => true,
            'permisos' => getPermisosDefault()
        ]);
        exit;
    }

    // Consultor: buscar permisos en la BD
    $stmt = $db->prepare(
        "SELECT valor FROM opciones_sistema
         WHERE usuario_id = :uid AND seccion = 'permisos' AND opcion = 'permisos_usuario'"
    );
    $stmt->execute([':uid' => $userId]);
    $row = $stmt->fetch();

    if ($row) {
        $permisosBD = json_decode($row['valor'], true);

        // Merge con defaults para que nuevos permisos tengan valor por defecto
        $defaults = getPermisosDefault();
        foreach ($defaults as $seccion => $perms) {
            if (!isset($permisosBD[$seccion])) {
                $permisosBD[$seccion] = $perms;
            } else {
                foreach ($perms as $key => $val) {
                    if (!isset($permisosBD[$seccion][$key])) {
                        $permisosBD[$seccion][$key] = $val;
                    }
                }
            }
        }
        $permisos = $permisosBD;
    } else {
        // Sin permisos guardados: todo habilitado por defecto
        $permisos = getPermisosDefault();
    }

    echo json_encode([
        'success'  => true,
        'es_admin' => false,
        'permisos' => $permisos
    ]);

} catch (PDOException $e) {
    error_log("Error get_permisos_usuario: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
}
