<?php
/**
 * AJAX: Opciones de Sistema
 * Acciones: get_globales, save_globales, get_api_keys, create_api_key,
 *           toggle_api_key, delete_api_key, get_usuarios, get_permisos,
 *           save_permisos, toggle_consultor, get_opciones_globales_realtime,
 *           get_campos_dinamicos, save_campo_dinamico, update_campo_dinamico,
 *           delete_campo_dinamico
 */

define('SISTEMA_REGISTROS', true);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

iniciarSesionSegura();

if (!estaAutenticado()) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

// Determinar acción
$accion = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['accion']) ? trim($_POST['accion']) : '';
} else {
    $accion = isset($_GET['accion']) ? trim($_GET['accion']) : '';
}

if (empty($accion)) {
    echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();

    switch ($accion) {

        // =====================================================
        // OPCIONES GLOBALES - OBTENER EN TIEMPO REAL (CUALQUIER USUARIO)
        // =====================================================
        case 'get_opciones_globales_realtime':
            $stmt = $db->query("SELECT opcion, valor FROM opciones_globales");
            $opciones = [];
            while ($row = $stmt->fetch()) {
                $opciones[$row['opcion']] = $row['valor'];
            }
            echo json_encode([
                'success' => true,
                'opciones' => $opciones
            ]);
            break;

        // =====================================================
        // OPCIONES GLOBALES - OBTENER (ADMIN SOLAMENTE)
        // =====================================================
        case 'get_globales':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            $stmt = $db->query("SELECT opcion, valor FROM opciones_globales ORDER BY opcion ASC");
            $opciones = [];
            while ($row = $stmt->fetch()) {
                $opciones[$row['opcion']] = $row['valor'];
            }

            echo json_encode([
                'success' => true,
                'opciones' => $opciones
            ]);
            break;

        // =====================================================
        // OPCIONES GLOBALES - GUARDAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'save_globales':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $sistemaNombre   = isset($_POST['sistema_nombre'])   ? trim($_POST['sistema_nombre'])   : '';
            $loginHabilitado = isset($_POST['login_habilitado']) ? trim($_POST['login_habilitado']) : '1';
            $loginMensaje    = isset($_POST['login_mensaje'])    ? trim($_POST['login_mensaje'])    : '';

            $opciones = [
                'sistema_nombre'   => $sistemaNombre,
                'login_habilitado' => $loginHabilitado,
                'login_mensaje'    => $loginMensaje
            ];

            foreach ($opciones as $key => $val) {
                $stmtCheck = $db->prepare("SELECT id FROM opciones_globales WHERE opcion = :opcion");
                $stmtCheck->execute([':opcion' => $key]);
                if ($stmtCheck->fetch()) {
                    $stmtUp = $db->prepare("UPDATE opciones_globales SET valor = :valor, fecha_actualizacion = NOW() WHERE opcion = :opcion");
                    $stmtUp->execute([':valor' => $val, ':opcion' => $key]);
                } else {
                    $stmtIn = $db->prepare("INSERT INTO opciones_globales (opcion, valor) VALUES (:opcion, :valor)");
                    $stmtIn->execute([':opcion' => $key, ':valor' => $val]);
                }
            }

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Editó opciones globales',
                'Nombre: ' . $sistemaNombre . ' | Login: ' . ($loginHabilitado ? 'Habilitado' : 'Deshabilitado')
            );

            echo json_encode(['success' => true, 'message' => 'Opciones globales guardadas correctamente']);
            break;

        // =====================================================
        // API KEYS - OBTENER (ADMIN SOLAMENTE)
        // =====================================================
        case 'get_api_keys':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            $stmt = $db->query("SELECT id, dominio, api_key, activo, fecha_creacion, ultimo_uso FROM api_keys ORDER BY fecha_creacion DESC");
            $keys = $stmt->fetchAll();

            echo json_encode(['success' => true, 'api_keys' => $keys]);
            break;

        // =====================================================
        // API KEYS - CREAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'create_api_key':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $dominio = isset($_POST['dominio']) ? trim($_POST['dominio']) : '';

            if (empty($dominio)) {
                echo json_encode(['success' => false, 'message' => 'El dominio no puede estar vacío']);
                exit;
            }

            // Validar que no exista
            $stmtCheck = $db->prepare("SELECT id FROM api_keys WHERE dominio = :dominio");
            $stmtCheck->execute([':dominio' => $dominio]);
            if ($stmtCheck->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Este dominio ya tiene una API Key']);
                exit;
            }

            // Generar API Key y Secret
            $apiKey    = bin2hex(random_bytes(32)); // 64 caracteres
            $apiSecret = bin2hex(random_bytes(64)); // 128 caracteres

            $stmtIns = $db->prepare(
                "INSERT INTO api_keys (dominio, api_key, api_secret, activo, fecha_creacion)
                 VALUES (:dominio, :api_key, :api_secret, 1, NOW())"
            );
            $stmtIns->execute([
                ':dominio'    => $dominio,
                ':api_key'    => $apiKey,
                ':api_secret' => $apiSecret
            ]);

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Creó API Key',
                'Dominio: ' . $dominio
            );

            echo json_encode([
                'success'    => true,
                'message'    => 'API Key creada correctamente',
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret
            ]);
            break;

        // =====================================================
        // API KEYS - TOGGLE (ADMIN SOLAMENTE)
        // =====================================================
        case 'toggle_api_key':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de API Key inválido']);
                exit;
            }

            $stmtGet = $db->prepare("SELECT dominio, activo FROM api_keys WHERE id = :id");
            $stmtGet->execute([':id' => $id]);
            $key = $stmtGet->fetch();

            if (!$key) {
                echo json_encode(['success' => false, 'message' => 'API Key no encontrada']);
                exit;
            }

            $nuevoEstado = $key['activo'] ? 0 : 1;
            $stmtUp = $db->prepare("UPDATE api_keys SET activo = :activo WHERE id = :id");
            $stmtUp->execute([':activo' => $nuevoEstado, ':id' => $id]);

            $estadoTexto = $nuevoEstado ? 'Activada' : 'Desactivada';
            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'API Key ' . $estadoTexto,
                'ID: ' . $id
            );

            echo json_encode(['success' => true, 'message' => 'API Key ' . $estadoTexto . ' correctamente']);
            break;

        // =====================================================
        // API KEYS - ELIMINAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'delete_api_key':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de API Key inválido']);
                exit;
            }

            $stmtGet = $db->prepare("SELECT dominio FROM api_keys WHERE id = :id");
            $stmtGet->execute([':id' => $id]);
            $key = $stmtGet->fetch();

            if (!$key) {
                echo json_encode(['success' => false, 'message' => 'API Key no encontrada']);
                exit;
            }

            $stmtDel = $db->prepare("DELETE FROM api_keys WHERE id = :id");
            $stmtDel->execute([':id' => $id]);

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Eliminó API Key',
                'Dominio: ' . $key['dominio']
            );

            echo json_encode(['success' => true, 'message' => 'API Key eliminada correctamente']);
            break;

        // =====================================================
        // USUARIOS - LISTAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'get_usuarios':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            $stmt = $db->query("SELECT id, nombre, apellidos, tipo, estado FROM usuarios ORDER BY tipo ASC, nombre ASC");
            $usuarios = $stmt->fetchAll();
            echo json_encode(['success' => true, 'usuarios' => $usuarios]);
            break;

        // =====================================================
        // PERMISOS - OBTENER POR USUARIO (ESTRUCTURA PLANA)
        // =====================================================
        case 'get_permisos':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            $usuarioId = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
            if ($usuarioId <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
                exit;
            }

            $stmt = $db->prepare("SELECT valor FROM opciones_sistema WHERE usuario_id = :uid AND seccion = 'permisos' AND opcion = 'permisos_usuario'");
            $stmt->execute([':uid' => $usuarioId]);
            $row = $stmt->fetch();

            if ($row) {
                $permisos = json_decode($row['valor'], true);
            } else {
                $permisos = [];
            }

            echo json_encode(['success' => true, 'permisos' => $permisos]);
            break;

        // =====================================================
        // PERMISOS - GUARDAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'save_permisos':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }
            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $usuarioId    = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
            $permisosJson = isset($_POST['permisos'])   ? trim($_POST['permisos'])   : '';

            if ($usuarioId <= 0) {
                echo json_encode(['success' => false, 'message' => 'Seleccione un usuario']);
                exit;
            }

            if (empty($permisosJson)) {
                echo json_encode(['success' => false, 'message' => 'Datos de permisos vacíos']);
                exit;
            }

            $permisosData = json_decode($permisosJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'JSON de permisos inválido']);
                exit;
            }

            $stmtCheck = $db->prepare("SELECT id FROM opciones_sistema WHERE usuario_id = :uid AND seccion = 'permisos' AND opcion = 'permisos_usuario'");
            $stmtCheck->execute([':uid' => $usuarioId]);

            if ($stmtCheck->fetch()) {
                $stmtUp = $db->prepare("UPDATE opciones_sistema SET valor = :valor, fecha_actualizacion = NOW() WHERE usuario_id = :uid AND seccion = 'permisos' AND opcion = 'permisos_usuario'");
                $stmtUp->execute([':valor' => $permisosJson, ':uid' => $usuarioId]);
            } else {
                $stmtIn = $db->prepare("INSERT INTO opciones_sistema (usuario_id, seccion, opcion, valor) VALUES (:uid, 'permisos', 'permisos_usuario', :valor)");
                $stmtIn->execute([':uid' => $usuarioId, ':valor' => $permisosJson]);
            }

            $stmtUser = $db->prepare("SELECT nombre, apellidos FROM usuarios WHERE id = :id");
            $stmtUser->execute([':id' => $usuarioId]);
            $userData      = $stmtUser->fetch();
            $nombreUsuario = $userData ? $userData['nombre'] . ' ' . $userData['apellidos'] : 'ID ' . $usuarioId;

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Editó permisos de usuario',
                'Usuario: ' . $nombreUsuario
            );

            echo json_encode(['success' => true, 'message' => 'Permisos guardados correctamente']);
            break;

        // =====================================================
        // SUSPENDER / ACTIVAR CONSULTOR (ADMIN SOLAMENTE)
        // ─────────────────────────────────────────────────────
        // El JS de opciones-sistema.php envía: usuario_id
        // El JS de consultores.php envía:      consultor_id
        // Ambos son aceptados para máxima compatibilidad.
        // =====================================================
        case 'toggle_consultor':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            // ← FIX: acepta 'usuario_id' (opciones-sistema) O 'consultor_id' (compatibilidad)
            $consultorId = 0;
            if (!empty($_POST['usuario_id'])) {
                $consultorId = (int)$_POST['usuario_id'];
            } elseif (!empty($_POST['consultor_id'])) {
                $consultorId = (int)$_POST['consultor_id'];
            }

            if ($consultorId <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de consultor inválido']);
                exit;
            }

            $stmtGet = $db->prepare("SELECT nombre, apellidos, estado FROM usuarios WHERE id = :id AND tipo = 'consultor'");
            $stmtGet->execute([':id' => $consultorId]);
            $consultor = $stmtGet->fetch();

            if (!$consultor) {
                echo json_encode(['success' => false, 'message' => 'Consultor no encontrado']);
                exit;
            }

            $nuevoEstado = $consultor['estado'] === 'activo' ? 'suspendido' : 'activo';

            $stmtUp = $db->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
            $stmtUp->execute([':estado' => $nuevoEstado, ':id' => $consultorId]);

            $accionLog = $nuevoEstado === 'suspendido' ? 'Suspendió consultor' : 'Activó consultor';
            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                $accionLog,
                'Consultor: ' . $consultor['nombre'] . ' ' . $consultor['apellidos']
            );

            echo json_encode([
                'success'     => true,
                'message'     => $accionLog . ' correctamente',
                'nuevoEstado' => $nuevoEstado
            ]);
            break;

        // =====================================================
        // CAMPOS DINÁMICOS - LISTAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'get_campos_dinamicos':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            $stmt = $db->query("SELECT id, nombre_campo, nombre_mostrar, tipo_dato, mostrar_lista, mostrar_filtro, mostrar_estadisticas, mostrar_excel, es_obligatorio, activo, orden, fecha_creacion FROM campos_dinamicos ORDER BY orden ASC, fecha_creacion ASC");
            $campos = $stmt->fetchAll();
            echo json_encode(['success' => true, 'campos' => $campos]);
            break;

        // =====================================================
        // CAMPOS DINÁMICOS - GUARDAR NUEVO (ADMIN SOLAMENTE)
        // =====================================================
        case 'save_campo_dinamico':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $nombreCampo         = isset($_POST['nombre_campo'])         ? trim($_POST['nombre_campo'])         : '';
            $nombreMostrar       = isset($_POST['nombre_mostrar'])       ? trim($_POST['nombre_mostrar'])       : '';
            $tipoDato            = isset($_POST['tipo_dato'])            ? trim($_POST['tipo_dato'])            : 'texto';
            $mostrarLista        = isset($_POST['mostrar_lista'])        ? (int)$_POST['mostrar_lista']        : 1;
            $mostrarFiltro       = isset($_POST['mostrar_filtro'])       ? (int)$_POST['mostrar_filtro']       : 1;
            $mostrarEstadisticas = isset($_POST['mostrar_estadisticas']) ? (int)$_POST['mostrar_estadisticas'] : 0;
            $mostrarExcel        = isset($_POST['mostrar_excel'])        ? (int)$_POST['mostrar_excel']        : 1;
            $esObligatorio       = isset($_POST['es_obligatorio'])       ? (int)$_POST['es_obligatorio']       : 0;

            if (empty($nombreCampo) || empty($nombreMostrar)) {
                echo json_encode(['success' => false, 'message' => 'Nombre interno y etiqueta son obligatorios']);
                exit;
            }

            // Validar formato nombre_campo (solo letras, números y guion bajo)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombreCampo)) {
                echo json_encode(['success' => false, 'message' => 'El nombre interno solo puede contener letras, números y guion bajo (_)']);
                exit;
            }

            // Verificar que no exista
            $stmtCheck = $db->prepare("SELECT id FROM campos_dinamicos WHERE nombre_campo = :nombre_campo");
            $stmtCheck->execute([':nombre_campo' => $nombreCampo]);
            if ($stmtCheck->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Ya existe un campo con ese nombre interno']);
                exit;
            }

            $tiposPermitidos = ['texto', 'numero', 'lista', 'fecha'];
            if (!in_array($tipoDato, $tiposPermitidos)) $tipoDato = 'texto';

            // Obtener el máximo orden actual
            $stmtOrden = $db->query("SELECT COALESCE(MAX(orden), 0) + 1 as next_orden FROM campos_dinamicos");
            $nextOrden = (int)$stmtOrden->fetch()['next_orden'];

            $stmtIns = $db->prepare(
                "INSERT INTO campos_dinamicos
                 (nombre_campo, nombre_mostrar, tipo_dato, mostrar_lista, mostrar_filtro, mostrar_estadisticas, mostrar_excel, es_obligatorio, activo, orden, fuente)
                 VALUES (:nc, :nm, :td, :ml, :mf, :me, :mx, :eo, 1, :orden, 'manual')"
            );
            $stmtIns->execute([
                ':nc'    => $nombreCampo,
                ':nm'    => $nombreMostrar,
                ':td'    => $tipoDato,
                ':ml'    => $mostrarLista,
                ':mf'    => $mostrarFiltro,
                ':me'    => $mostrarEstadisticas,
                ':mx'    => $mostrarExcel,
                ':eo'    => $esObligatorio,
                ':orden' => $nextOrden,
            ]);

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Creó campo dinámico',
                'Campo: ' . $nombreCampo . ' | Etiqueta: ' . $nombreMostrar
            );

            echo json_encode(['success' => true, 'message' => 'Campo dinámico creado correctamente']);
            break;

        // =====================================================
        // CAMPOS DINÁMICOS - ACTUALIZAR (ADMIN SOLAMENTE)
        // =====================================================
        case 'update_campo_dinamico':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $id                  = isset($_POST['id'])                   ? (int)$_POST['id']                   : 0;
            $nombreMostrar       = isset($_POST['nombre_mostrar'])       ? trim($_POST['nombre_mostrar'])       : '';
            $tipoDato            = isset($_POST['tipo_dato'])            ? trim($_POST['tipo_dato'])            : 'texto';
            $mostrarLista        = isset($_POST['mostrar_lista'])        ? (int)$_POST['mostrar_lista']        : 1;
            $mostrarFiltro       = isset($_POST['mostrar_filtro'])       ? (int)$_POST['mostrar_filtro']       : 1;
            $mostrarEstadisticas = isset($_POST['mostrar_estadisticas']) ? (int)$_POST['mostrar_estadisticas'] : 0;
            $mostrarExcel        = isset($_POST['mostrar_excel'])        ? (int)$_POST['mostrar_excel']        : 1;
            $esObligatorio       = isset($_POST['es_obligatorio'])       ? (int)$_POST['es_obligatorio']       : 0;
            $activo              = isset($_POST['activo'])               ? (int)$_POST['activo']               : 1;

            if ($id <= 0 || empty($nombreMostrar)) {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                exit;
            }

            $tiposPermitidos = ['texto', 'numero', 'lista', 'fecha'];
            if (!in_array($tipoDato, $tiposPermitidos)) $tipoDato = 'texto';

            $stmtUp = $db->prepare(
                "UPDATE campos_dinamicos SET
                 nombre_mostrar = :nm, tipo_dato = :td, mostrar_lista = :ml,
                 mostrar_filtro = :mf, mostrar_estadisticas = :me, mostrar_excel = :mx,
                 es_obligatorio = :eo, activo = :activo
                 WHERE id = :id"
            );
            $stmtUp->execute([
                ':nm'     => $nombreMostrar,
                ':td'     => $tipoDato,
                ':ml'     => $mostrarLista,
                ':mf'     => $mostrarFiltro,
                ':me'     => $mostrarEstadisticas,
                ':mx'     => $mostrarExcel,
                ':eo'     => $esObligatorio,
                ':activo' => $activo,
                ':id'     => $id,
            ]);

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Actualizó campo dinámico',
                'ID: ' . $id . ' | Etiqueta: ' . $nombreMostrar
            );

            echo json_encode(['success' => true, 'message' => 'Campo dinámico actualizado correctamente']);
            break;

        // =====================================================
        // CAMPOS DINÁMICOS - ELIMINAR (ADMIN SOLAMENTE)
        // Elimina el campo Y limpia sus datos de todos los registros
        // =====================================================
        case 'delete_campo_dinamico':
            if (!esAdministrador()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                exit;
            }

            if (!isset($_POST['csrf_token']) || !validarTokenCSRF($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'Token inválido']);
                exit;
            }

            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                exit;
            }

            // Obtener nombre_campo antes de eliminar
            $stmtGet = $db->prepare("SELECT nombre_campo, nombre_mostrar FROM campos_dinamicos WHERE id = :id");
            $stmtGet->execute([':id' => $id]);
            $campo = $stmtGet->fetch();

            if (!$campo) {
                echo json_encode(['success' => false, 'message' => 'Campo no encontrado']);
                exit;
            }

            $nombreCampo   = $campo['nombre_campo'];
            $nombreMostrar = $campo['nombre_mostrar'];

            // 1. Eliminar el campo dinámico
            $stmtDel = $db->prepare("DELETE FROM campos_dinamicos WHERE id = :id");
            $stmtDel->execute([':id' => $id]);

            // 2. Limpiar los datos de ESE campo en todos los registros
            $stmtClean = $db->prepare(
                "UPDATE registros
                 SET campos_extra = JSON_REMOVE(campos_extra, :path)
                 WHERE JSON_EXTRACT(campos_extra, :path2) IS NOT NULL"
            );
            $stmtClean->execute([
                ':path'  => '$.' . $nombreCampo,
                ':path2' => '$.' . $nombreCampo,
            ]);

            registrarLog(
                $_SESSION['user_id'],
                $_SESSION['user_nombre'] . ' ' . $_SESSION['user_apellidos'],
                $_SESSION['user_tipo'],
                'Eliminó campo dinámico',
                'Campo: ' . $nombreCampo . ' | Etiqueta: ' . $nombreMostrar
            );

            echo json_encode(['success' => true, 'message' => 'Campo "' . $nombreMostrar . '" eliminado y datos limpiados correctamente']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
            break;
    }

} catch (PDOException $e) {
    error_log("Error opciones_sistema.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
}
?>
