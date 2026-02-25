<?php
/**
 * AJAX: Importar registros desde Excel (por lotes)
 * Recibe un batch de registros en JSON y los inserta en la BD
 * Permite duplicados y campos vacíos
 */
define('SISTEMA_REGISTROS', true);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
iniciarSesionSegura();

if (!estaAutenticado() || !esAdministrador()) {
    echo json_encode(['success' => false, 'message' => 'Sin permisos']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$accion = isset($_POST['accion']) ? trim($_POST['accion']) : '';

if ($accion !== 'importar_batch') {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    exit;
}

// Validar CSRF
$csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!validarTokenCSRF($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

$registrosJson = isset($_POST['registros']) ? $_POST['registros'] : '[]';
$registros = json_decode($registrosJson, true);

if (!is_array($registros) || count($registros) === 0) {
    echo json_encode(['success' => false, 'message' => 'No hay registros']);
    exit;
}

// Campos permitidos
$camposPermitidos = [
    'nombre', 'apellidos', 'telefono', 'correo', 'asesor', 'delegado',
    'curso', 'pais', 'ciudad', 'moneda', 'metodo_pago', 'ip',
    'fecha', 'hora', 'categoria', 'file_url', 'formulario_id', 'web'
];

try {
    $db = Database::getInstance()->getConnection();
    $insertados = 0;
    $duplicados = 0;
    $errores = 0;

    $sql = "INSERT INTO registros (nombre, apellidos, telefono, correo, asesor, delegado, curso, pais, ciudad, moneda, metodo_pago, ip, fecha, hora, categoria, file_url, formulario_id, web, fecha_registro)
            VALUES (:nombre, :apellidos, :telefono, :correo, :asesor, :delegado, :curso, :pais, :ciudad, :moneda, :metodo_pago, :ip, :fecha, :hora, :categoria, :file_url, :formulario_id, :web, NOW())";
    $stmt = $db->prepare($sql);

    foreach ($registros as $reg) {
        try {
            // Sanitizar cada campo
            $datos = [];
            foreach ($camposPermitidos as $campo) {
                $valor = isset($reg[$campo]) ? trim($reg[$campo]) : '';
                // Validar fecha
                if ($campo === 'fecha' && $valor !== '') {
                    // Intentar normalizar fecha
                    if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $valor, $m)) {
                        $valor = $m[3] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    }
                    // Validar formato final
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
                        $valor = '';
                    }
                }
                // Validar hora
                if ($campo === 'hora' && $valor !== '') {
                    if (!preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $valor)) {
                        $valor = '';
                    }
                }
                $datos[$campo] = $valor;
            }

            // Verificar si es duplicado (mismo nombre + apellidos + correo + telefono, todos no vacíos)
            $esDuplicado = false;
            if ($datos['nombre'] !== '' && $datos['correo'] !== '') {
                $stmtCheck = $db->prepare("SELECT COUNT(*) as c FROM registros WHERE nombre = :n AND apellidos = :a AND correo = :c LIMIT 1");
                $stmtCheck->execute([':n' => $datos['nombre'], ':a' => $datos['apellidos'], ':c' => $datos['correo']]);
                if ($stmtCheck->fetch()['c'] > 0) {
                    $esDuplicado = true;
                }
            }

            // Insertar (incluso si es duplicado, como se pidió)
            $stmt->execute([
                ':nombre'        => $datos['nombre'],
                ':apellidos'     => $datos['apellidos'],
                ':telefono'      => $datos['telefono'],
                ':correo'        => $datos['correo'],
                ':asesor'        => $datos['asesor'],
                ':delegado'      => $datos['delegado'],
                ':curso'         => $datos['curso'],
                ':pais'          => $datos['pais'],
                ':ciudad'        => $datos['ciudad'],
                ':moneda'        => $datos['moneda'],
                ':metodo_pago'   => $datos['metodo_pago'],
                ':ip'            => $datos['ip'],
                ':fecha'         => $datos['fecha'] !== '' ? $datos['fecha'] : null,
                ':hora'          => $datos['hora'] !== '' ? $datos['hora'] : null,
                ':categoria'     => $datos['categoria'],
                ':file_url'      => $datos['file_url'],
                ':formulario_id' => $datos['formulario_id'],
                ':web'           => $datos['web']
            ]);

            if ($esDuplicado) {
                $duplicados++;
            } else {
                $insertados++;
            }

        } catch (PDOException $e) {
            error_log("Error importar registro: " . $e->getMessage());
            $errores++;
        }
    }

    // Log de actividad
    try {
        $stmtLog = $db->prepare("INSERT INTO logs (usuario_id, accion, detalle, ip, fecha) VALUES (:uid, :accion, :detalle, :ip, NOW())");
        $stmtLog->execute([
            ':uid'     => $_SESSION['user_id'],
            ':accion'  => 'importar_excel',
            ':detalle' => "Importación Excel: $insertados insertados, $duplicados duplicados, $errores errores (batch de " . count($registros) . ")",
            ':ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    } catch (Exception $e) {}

    echo json_encode([
        'success'    => true,
        'insertados' => $insertados,
        'duplicados' => $duplicados,
        'errores'    => $errores
    ]);

} catch (PDOException $e) {
    error_log("Error importar_excel: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
}