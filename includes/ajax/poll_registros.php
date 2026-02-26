<?php
/**
 * AJAX: Polling de nuevos registros + cambios inline
 * Modos:
 *   - nuevos:   ?last_id=N&vista_tipo=...        → registros nuevos
 *   - cambios:  ?modo=cambios&last_ts=...&vista_tipo=...  → registros editados
 */
define('SISTEMA_REGISTROS', true);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');
iniciarSesionSegura();
if (!estaAutenticado()) { echo json_encode(['success' => false, 'message' => 'No autenticado']); exit; }

try {
    $db        = Database::getInstance()->getConnection();
    $modo      = isset($_GET['modo'])       ? trim($_GET['modo'])       : 'nuevos';
    $vistaTipo = isset($_GET['vista_tipo']) ? trim($_GET['vista_tipo']) : '';

    // Filtro de vista (asesor / delegado)
    $whereVista = '';
    if ($vistaTipo === 'asesor') {
        $whereVista = " AND asesor IS NOT NULL AND asesor != ''";
    } elseif ($vistaTipo === 'delegado') {
        $whereVista = " AND delegado IS NOT NULL AND delegado != ''";
    }

    if ($modo === 'cambios') {
        // Registros editados después de last_ts
        $lastTs = isset($_GET['last_ts']) ? trim($_GET['last_ts']) : '';
        if ($lastTs === '') {
            echo json_encode(['success' => true, 'cambios' => [], 'count' => 0, 'server_ts' => date('Y-m-d H:i:s')]);
            exit;
        }
        $stmt = $db->prepare(
            "SELECT * FROM registros
             WHERE fecha_actualizacion > :last_ts $whereVista
             ORDER BY fecha_actualizacion ASC LIMIT 100"
        );
        $stmt->execute([':last_ts' => $lastTs]);
        $cambios = $stmt->fetchAll();
        foreach ($cambios as &$reg) {
            $reg['campos_extra'] = !empty($reg['campos_extra']) ? json_decode($reg['campos_extra'], true) : [];
        }
        unset($reg);
        echo json_encode([
            'success'   => true,
            'cambios'   => $cambios,
            'count'     => count($cambios),
            'server_ts' => date('Y-m-d H:i:s')
        ]);

    } else {
        // Modo original: registros nuevos
        $lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
        $where  = "WHERE id > :last_id" . $whereVista;
        $stmt   = $db->prepare("SELECT * FROM registros $where ORDER BY id ASC LIMIT 50");
        $stmt->execute([':last_id' => $lastId]);
        $nuevos = $stmt->fetchAll();
        foreach ($nuevos as &$reg) {
            $reg['campos_extra'] = !empty($reg['campos_extra']) ? json_decode($reg['campos_extra'], true) : [];
        }
        unset($reg);
        echo json_encode(['success' => true, 'nuevos' => $nuevos, 'count' => count($nuevos)]);
    }

} catch (PDOException $e) {
    error_log("Error poll_registros: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error']);
}
