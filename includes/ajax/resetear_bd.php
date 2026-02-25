<?php
/**
 * AJAX: Resetear Base de Datos
 * Elimina TODOS los datos excepto el usuario Administrador actual
 * Reinicia todos los AUTO_INCREMENT a 1
 */

// Capturar errores fatales para que devuelva JSON en vez de HTML
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Registrar handler de errores fatales
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode([
            'success' => false,
            'message' => 'Error fatal: ' . $error['message'] . ' en ' . $error['file'] . ':' . $error['line']
        ]);
    }
});

define('SISTEMA_REGISTROS', true);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

try {
    iniciarSesionSegura();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error de sesión: ' . $e->getMessage()]);
    exit;
}

if (!estaAutenticado() || !esAdministrador()) {
    echo json_encode(['success' => false, 'message' => 'Sin permisos']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$accion = isset($_POST['accion']) ? trim($_POST['accion']) : '';
$confirmacion = isset($_POST['confirmacion']) ? trim($_POST['confirmacion']) : '';

if ($accion !== 'resetear_todo' || $confirmacion !== 'RESETEAR') {
    echo json_encode(['success' => false, 'message' => 'Confirmación inválida']);
    exit;
}

$csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!validarTokenCSRF($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Token CSRF inválido. Recargue la página e intente de nuevo.']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    $adminId = $_SESSION['user_id'];

    // Guardar datos del admin ANTES de tocar nada
    $stmtAdmin = $db->prepare("SELECT nombre, apellidos, usuario, password, telefono, pais, fecha_creacion FROM usuarios WHERE id = :aid AND tipo = 'administrador' LIMIT 1");
    $stmtAdmin->execute([':aid' => $adminId]);
    $adminData = $stmtAdmin->fetch();

    if (!$adminData) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el usuario administrador con id=' . $adminId]);
        exit;
    }

    // Desactivar restricciones de FK
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

    // =====================================================
    // 1. VACIAR TABLAS una por una (con try/catch individual)
    // =====================================================
    $tablasVaciar = ['registros', 'logs', 'api_keys', 'campos_dinamicos', 'opciones_sistema'];
    $tablasLimpiadas = [];
    $tablasError = [];

    foreach ($tablasVaciar as $tabla) {
        try {
            $db->exec("TRUNCATE TABLE `$tabla`");
            $tablasLimpiadas[] = $tabla;
        } catch (PDOException $e) {
            // Si no existe la tabla, intentar con DELETE
            try {
                $db->exec("DELETE FROM `$tabla`");
                $tablasLimpiadas[] = $tabla;
            } catch (PDOException $e2) {
                $tablasError[] = $tabla . ': ' . $e2->getMessage();
            }
        }
    }

    // =====================================================
    // 2. USUARIOS: eliminar todos, reinsertar solo el admin
    // =====================================================
    $db->exec("DELETE FROM usuarios");
    $db->exec("ALTER TABLE usuarios AUTO_INCREMENT = 1");

    $stmtReinsert = $db->prepare(
        "INSERT INTO usuarios (nombre, apellidos, usuario, password, telefono, pais, tipo, estado, fecha_creacion)
         VALUES (:nombre, :apellidos, :usuario, :password, :telefono, :pais, 'administrador', 'activo', :fecha)"
    );
    $stmtReinsert->execute([
        ':nombre'    => $adminData['nombre'],
        ':apellidos' => $adminData['apellidos'],
        ':usuario'   => $adminData['usuario'],
        ':password'  => $adminData['password'],
        ':telefono'  => isset($adminData['telefono']) ? $adminData['telefono'] : '',
        ':pais'      => isset($adminData['pais']) ? $adminData['pais'] : '',
        ':fecha'     => $adminData['fecha_creacion']
    ]);

    $nuevoAdminId = (int)$db->lastInsertId();

    // Actualizar sesión
    $_SESSION['user_id'] = $nuevoAdminId;

    // Reactivar FK
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");

    // =====================================================
    // 3. LOG del reset
    // =====================================================
    try {
        registrarLog(
            $nuevoAdminId,
            $adminData['nombre'] . ' ' . $adminData['apellidos'],
            'administrador',
            'Resetear BD',
            'Sistema reseteado. Tablas limpiadas: ' . implode(', ', $tablasLimpiadas)
        );
    } catch (Exception $logErr) {
        // No fallar por el log
    }

    echo json_encode([
        'success' => true,
        'message' => 'Base de datos reseteada exitosamente',
        'tablas_limpiadas' => $tablasLimpiadas,
        'tablas_error' => $tablasError
    ]);

} catch (PDOException $e) {
    try { $db->exec("SET FOREIGN_KEY_CHECKS = 1"); } catch (Exception $ex) {}
    error_log("Error resetear_bd: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error BD: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("Error general resetear_bd: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}