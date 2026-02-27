<?php
/**
 * API: Obtener Estadísticas
 * Devuelve datos para gráficos Chart.js
 *
 * Soporta:
 * - Tabs: tab=general|asesor|delegado
 * - Filtro base según tab: formulario_id / asesor / delegado
 * - Subfiltros: fecha_desde, fecha_hasta, tendencia, curso, pais, ciudad, metodo_pago, web
 * - type=filtros para obtener filtros dinámicos
 * - Filtros dinámicos con prefijo dyn_*
 */
define('SISTEMA_REGISTROS', true);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

iniciarSesionSegura();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

function normTab($t) {
    $t = strtolower(trim((string)$t));
    $allowed = ['asesor', 'delegado', 'general'];
    return in_array($t, $allowed, true) ? $t : 'general';
}

try {
    $db = Database::getInstance()->getConnection();

    $tab  = normTab($_GET['tab'] ?? 'general');
    $type = $_GET['type'] ?? 'stats';

    // =====================================================
    // PARÁMETROS DE ENTRADA (fijos)
    // =====================================================
    $inputParams = [
        'formulario_id' => $_GET['formulario_id'] ?? '',
        'asesor'        => $_GET['asesor']         ?? '',
        'delegado'      => $_GET['delegado']       ?? '',
        'curso'         => $_GET['curso']           ?? '',
        'pais'          => $_GET['pais']            ?? '',
        'ciudad'        => $_GET['ciudad']          ?? '',
        'metodo_pago'   => $_GET['metodo_pago']     ?? '',
        'web'           => $_GET['web']             ?? '',
        'fecha_desde'   => $_GET['fecha_desde']     ?? '',
        'fecha_hasta'   => $_GET['fecha_hasta']     ?? ''
    ];

    // ── Leer todos los parámetros dyn_* que lleguen en GET ──
    $dynInput = [];
    foreach ($_GET as $key => $val) {
        if (strpos($key, 'dyn_') === 0 && trim($val) !== '') {
            $dynInput[substr($key, 4)] = trim($val); // 'dyn_nivel_ingles' → 'nivel_ingles'
        }
    }

    // ── Cargar campos dinámicos con mostrar_filtro_estadisticas = 1 ──
    $stmtCampos = $db->query(
        "SELECT nombre_campo, nombre_mostrar
         FROM campos_dinamicos
         WHERE mostrar_filtro_estadisticas = 1 AND activo = 1
         ORDER BY orden ASC"
    );
    $camposFiltroStats = $stmtCampos->fetchAll(PDO::FETCH_ASSOC);
    $camposFiltroNames = array_column($camposFiltroStats, 'nombre_campo');

    // ── Cargar campos dinámicos con mostrar_estadisticas = 1 ──
    $stmtCamposStats = $db->query(
        "SELECT nombre_campo, nombre_mostrar
         FROM campos_dinamicos
         WHERE mostrar_estadisticas = 1 AND activo = 1
         ORDER BY orden ASC"
    );
    $camposGraficoStats = $stmtCamposStats->fetchAll(PDO::FETCH_ASSOC);

    // =====================================================
    // FUNCIÓN: CONSTRUIR WHERE (excluyendo un campo)
    // =====================================================
    function buildWhereExcluding($excludeField, $tab, $inputParams, $dynInput, $camposFiltroNames) {
        $where  = [];
        $params = [];

        // Filtro base por tab
        if ($tab === 'general' && $excludeField !== 'formulario_id' && !empty($inputParams['formulario_id'])) {
            $where[] = "r.formulario_id = :formulario_id";
            $params[':formulario_id'] = $inputParams['formulario_id'];
        }
        if ($tab === 'asesor' && $excludeField !== 'asesor' && !empty($inputParams['asesor'])) {
            $where[] = "r.asesor = :asesor";
            $params[':asesor'] = $inputParams['asesor'];
        }
        if ($tab === 'delegado' && $excludeField !== 'delegado' && !empty($inputParams['delegado'])) {
            $where[] = "r.delegado = :delegado";
            $params[':delegado'] = $inputParams['delegado'];
        }

        // Sub-filtros fijos
        if ($excludeField !== 'curso'       && !empty($inputParams['curso']))       { $where[] = "r.curso = :curso";             $params[':curso']       = $inputParams['curso']; }
        if ($excludeField !== 'pais'        && !empty($inputParams['pais']))        { $where[] = "r.pais = :pais";               $params[':pais']        = $inputParams['pais']; }
        if ($excludeField !== 'ciudad'      && !empty($inputParams['ciudad']))      { $where[] = "r.ciudad = :ciudad";           $params[':ciudad']      = $inputParams['ciudad']; }
        if ($excludeField !== 'metodo_pago' && !empty($inputParams['metodo_pago'])) { $where[] = "r.metodo_pago = :metodo_pago"; $params[':metodo_pago'] = $inputParams['metodo_pago']; }
        if ($excludeField !== 'web'         && !empty($inputParams['web']))         { $where[] = "r.web = :web";                 $params[':web']         = $inputParams['web']; }

        // Sub-filtros dinámicos
        foreach ($camposFiltroNames as $nc) {
            if ($excludeField !== 'dyn_' . $nc && isset($dynInput[$nc])) {
                $pathKey = ':dyn_' . $nc;
                $where[] = "JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, '$." . $nc . "')) = " . $pathKey;
                $params[$pathKey] = $dynInput[$nc];
            }
        }

        // Fechas
        if (!empty($inputParams['fecha_desde'])) { $where[] = "r.fecha >= :fecha_desde"; $params[':fecha_desde'] = $inputParams['fecha_desde']; }
        if (!empty($inputParams['fecha_hasta'])) { $where[] = "r.fecha <= :fecha_hasta"; $params[':fecha_hasta'] = $inputParams['fecha_hasta']; }

        $clause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
        return ['clause' => $clause, 'params' => $params];
    }

    // =====================================================
    // MODO: OBTENER FILTROS DINÁMICOS
    // =====================================================
    if ($type === 'filtros') {
        $filtros = [];

        // Campos fijos
        $camposFijos = ['formulario_id', 'asesor', 'delegado', 'curso', 'pais', 'ciudad', 'metodo_pago', 'web'];
        foreach ($camposFijos as $campo) {
            $wb          = buildWhereExcluding($campo, $tab, $inputParams, $dynInput, $camposFiltroNames);
            $whereClause = $wb['clause'];
            $params      = $wb['params'];
            $sql         = "SELECT DISTINCT `$campo` FROM registros r " . $whereClause;
            if ($whereClause === '') $sql .= " WHERE `$campo` IS NOT NULL AND `$campo` != ''";
            else                     $sql .= " AND `$campo` IS NOT NULL AND `$campo` != ''";
            $sql .= " ORDER BY `$campo` ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $filtros[$campo] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        // Campos dinámicos con mostrar_filtro_estadisticas = 1
        foreach ($camposFiltroNames as $nc) {
            $wb  = buildWhereExcluding('dyn_' . $nc, $tab, $inputParams, $dynInput, $camposFiltroNames);
            $bw  = $wb['clause'];
            $bp  = $wb['params'];
            $and = $bw ? ' AND' : ' WHERE';
            $path = '$.' . $nc;
            $sql  = "SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, :path_s)) as val
                     FROM registros r
                     $bw
                     $and JSON_EXTRACT(r.campos_extra, :path_s2) IS NOT NULL
                       AND JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, :path_s3)) != ''
                     ORDER BY val ASC";
            $bp[':path_s']  = $path;
            $bp[':path_s2'] = $path;
            $bp[':path_s3'] = $path;
            $stmtF = $db->prepare($sql);
            $stmtF->execute($bp);
            $filtros['dyn_' . $nc] = $stmtF->fetchAll(PDO::FETCH_COLUMN);
        }

        echo json_encode([
            'success'          => true,
            'filtros'          => $filtros,
            'campos_dinamicos' => $camposFiltroStats  // para que el JS sepa qué selects renderizar
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // =====================================================
    // MODO: OBTENER ESTADÍSTICAS
    // =====================================================
    $where  = [];
    $params = [];

    // Filtro base por tab
    if ($tab === 'general' && !empty($inputParams['formulario_id'])) { $where[] = "r.formulario_id = :formulario_id"; $params[':formulario_id'] = $inputParams['formulario_id']; }
    if ($tab === 'asesor'  && !empty($inputParams['asesor']))        { $where[] = "r.asesor = :asesor";               $params[':asesor']        = $inputParams['asesor']; }
    if ($tab === 'delegado'&& !empty($inputParams['delegado']))      { $where[] = "r.delegado = :delegado";           $params[':delegado']      = $inputParams['delegado']; }

    // Sub-filtros fijos
    if (!empty($inputParams['curso']))       { $where[] = "r.curso = :curso";             $params[':curso']       = $inputParams['curso']; }
    if (!empty($inputParams['pais']))        { $where[] = "r.pais = :pais";               $params[':pais']        = $inputParams['pais']; }
    if (!empty($inputParams['ciudad']))      { $where[] = "r.ciudad = :ciudad";           $params[':ciudad']      = $inputParams['ciudad']; }
    if (!empty($inputParams['metodo_pago'])) { $where[] = "r.metodo_pago = :metodo_pago"; $params[':metodo_pago'] = $inputParams['metodo_pago']; }
    if (!empty($inputParams['web']))         { $where[] = "r.web = :web";                 $params[':web']         = $inputParams['web']; }

    // Sub-filtros dinámicos activos
    foreach ($camposFiltroNames as $nc) {
        if (isset($dynInput[$nc])) {
            $pathKey = ':dyn_' . $nc;
            $where[] = "JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, '$." . $nc . "')) = " . $pathKey;
            $params[$pathKey] = $dynInput[$nc];
        }
    }

    // Fechas
    if (!empty($inputParams['fecha_desde'])) { $where[] = "r.fecha >= :fecha_desde"; $params[':fecha_desde'] = $inputParams['fecha_desde']; }
    if (!empty($inputParams['fecha_hasta'])) { $where[] = "r.fecha <= :fecha_hasta"; $params[':fecha_hasta'] = $inputParams['fecha_hasta']; }

    $whereSQL = count($where) > 0 ? (' WHERE ' . implode(' AND ', $where)) : '';

    // =====================================================
    // 1. RESUMEN GENERAL
    // =====================================================
    $sql = "SELECT
                COUNT(*) as total,
                COUNT(CASE WHEN r.fecha = CURDATE() THEN 1 END) as hoy,
                COUNT(CASE WHEN r.fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as semana,
                COUNT(CASE WHEN r.fecha >= DATE_FORMAT(CURDATE(), '%Y-%m-01') THEN 1 END) as mes,
                COUNT(DISTINCT r.asesor) as asesores,
                COUNT(DISTINCT r.delegado) as delegados,
                COUNT(DISTINCT r.curso) as cursos,
                COUNT(DISTINCT r.pais) as paises
            FROM registros r $whereSQL";
    $stmt = $db->prepare($sql); $stmt->execute($params);
    $resumen = $stmt->fetch();

    // =====================================================
    // 2. TENDENCIA
    // =====================================================
    $whereDia  = $where; $whereDia[]  = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    $sqlDia = "SELECT DATE_FORMAT(r.fecha,'%Y-%m-%d') as dia, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereDia) . " GROUP BY dia ORDER BY dia ASC";
    $stmt = $db->prepare($sqlDia); $stmt->execute($params); $porDia = $stmt->fetchAll();

    $whereSem  = $where; $whereSem[]  = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)";
    $sqlSemana = "SELECT YEARWEEK(r.fecha,1) as semana_num, MIN(DATE_FORMAT(r.fecha,'%Y-%m-%d')) as inicio_semana, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereSem) . " GROUP BY semana_num ORDER BY semana_num ASC";
    $stmt = $db->prepare($sqlSemana); $stmt->execute($params); $porSemana = $stmt->fetchAll();

    $whereMes  = $where; $whereMes[]  = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
    $sqlMes = "SELECT DATE_FORMAT(r.fecha,'%Y-%m') as mes_num, DATE_FORMAT(r.fecha,'%M %Y') as mes_nombre, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereMes) . " GROUP BY mes_num ORDER BY mes_num ASC";
    $stmt = $db->prepare($sqlMes); $stmt->execute($params); $porMes = $stmt->fetchAll();

    $whereBim  = $where; $whereBim[]  = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
    $sqlBim = "SELECT CONCAT(YEAR(r.fecha),'-B',LPAD(CEIL(MONTH(r.fecha)/2),2,'0')) as periodo, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereBim) . " GROUP BY YEAR(r.fecha), CEIL(MONTH(r.fecha)/2) ORDER BY YEAR(r.fecha) ASC, CEIL(MONTH(r.fecha)/2) ASC";
    $stmt = $db->prepare($sqlBim); $stmt->execute($params); $porBimestre = $stmt->fetchAll();

    $whereTrim = $where; $whereTrim[] = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)";
    $sqlTrim = "SELECT CONCAT(YEAR(r.fecha),'-T',QUARTER(r.fecha)) as periodo, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereTrim) . " GROUP BY YEAR(r.fecha), QUARTER(r.fecha) ORDER BY YEAR(r.fecha) ASC, QUARTER(r.fecha) ASC";
    $stmt = $db->prepare($sqlTrim); $stmt->execute($params); $porTrimestre = $stmt->fetchAll();

    $whereSemS = $where; $whereSemS[] = "r.fecha >= DATE_SUB(CURDATE(), INTERVAL 36 MONTH)";
    $sqlSemS = "SELECT CONCAT(YEAR(r.fecha),'-S',IF(MONTH(r.fecha)<=6,1,2)) as periodo, COUNT(*) as total FROM registros r WHERE " . implode(' AND ', $whereSemS) . " GROUP BY YEAR(r.fecha), IF(MONTH(r.fecha)<=6,1,2) ORDER BY YEAR(r.fecha) ASC, IF(MONTH(r.fecha)<=6,1,2) ASC";
    $stmt = $db->prepare($sqlSemS); $stmt->execute($params); $porSemestre = $stmt->fetchAll();

    $sqlAnio = "SELECT YEAR(r.fecha) as periodo, COUNT(*) as total FROM registros r $whereSQL GROUP BY YEAR(r.fecha) ORDER BY YEAR(r.fecha) ASC";
    $stmt = $db->prepare($sqlAnio); $stmt->execute($params); $porAnio = $stmt->fetchAll();

    // =====================================================
    // 3. GRUPOS (Top 15)
    // =====================================================
    $sqlAsesor   = "SELECT IFNULL(r.asesor,'Sin Asesor') as nombre, COUNT(*) as total FROM registros r $whereSQL GROUP BY r.asesor ORDER BY total DESC LIMIT 15";
    $stmt = $db->prepare($sqlAsesor);   $stmt->execute($params); $porAsesor   = $stmt->fetchAll();

    $sqlDelegado = "SELECT IFNULL(r.delegado,'Sin Delegado') as nombre, COUNT(*) as total FROM registros r $whereSQL GROUP BY r.delegado ORDER BY total DESC LIMIT 15";
    $stmt = $db->prepare($sqlDelegado); $stmt->execute($params); $porDelegado = $stmt->fetchAll();

    $sqlCurso    = "SELECT IFNULL(r.curso,'Sin Curso') as nombre, COUNT(*) as total FROM registros r $whereSQL GROUP BY r.curso ORDER BY total DESC LIMIT 15";
    $stmt = $db->prepare($sqlCurso);    $stmt->execute($params); $porCurso    = $stmt->fetchAll();

    $sqlPais     = "SELECT IFNULL(r.pais,'Sin País') as nombre, COUNT(*) as total FROM registros r $whereSQL GROUP BY r.pais ORDER BY total DESC LIMIT 15";
    $stmt = $db->prepare($sqlPais);     $stmt->execute($params); $porPais     = $stmt->fetchAll();

    $sqlMetodo   = "SELECT IFNULL(r.metodo_pago,'Sin Método') as nombre, COUNT(*) as total FROM registros r $whereSQL GROUP BY r.metodo_pago ORDER BY total DESC";
    $stmt = $db->prepare($sqlMetodo);   $stmt->execute($params); $porMetodoPago = $stmt->fetchAll();

    // =====================================================
    // 4. GRÁFICOS DINÁMICOS (mostrar_estadisticas = 1)
    // =====================================================
    $porCamposDinamicos = [];
    foreach ($camposGraficoStats as $cf) {
        $nc   = $cf['nombre_campo'];
        $path = '$.' . $nc;
        $sqlDyn = "SELECT JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, :path_g)) as nombre, COUNT(*) as total
                   FROM registros r
                   $whereSQL
                   " . ($whereSQL ? 'AND' : 'WHERE') . " JSON_EXTRACT(r.campos_extra, :path_g2) IS NOT NULL
                     AND JSON_UNQUOTE(JSON_EXTRACT(r.campos_extra, :path_g3)) != ''
                   GROUP BY nombre ORDER BY total DESC LIMIT 15";
        $pDyn = array_merge($params, [':path_g' => $path, ':path_g2' => $path, ':path_g3' => $path]);
        $stmtDyn = $db->prepare($sqlDyn);
        $stmtDyn->execute($pDyn);
        $porCamposDinamicos[] = [
            'nombre_campo'   => $nc,
            'nombre_mostrar' => $cf['nombre_mostrar'],
            'datos'          => $stmtDyn->fetchAll()
        ];
    }

    // =====================================================
    // 5. POR HORA DEL DÍA
    // =====================================================
    $whereHora    = $where; $whereHora[] = "r.hora IS NOT NULL";
    $whereHoraSQL = ' WHERE ' . implode(' AND ', $whereHora);
    $sqlHora      = "SELECT HOUR(r.hora) as hora_num, COUNT(*) as total FROM registros r $whereHoraSQL GROUP BY hora_num ORDER BY hora_num ASC";
    $stmt = $db->prepare($sqlHora); $stmt->execute($params); $porHora = $stmt->fetchAll();

    // =====================================================
    // RESPUESTA
    // =====================================================
    echo json_encode([
        'success'              => true,
        'tab'                  => $tab,
        'resumen'              => $resumen,
        'por_dia'              => $porDia,
        'por_semana'           => $porSemana,
        'por_mes'              => $porMes,
        'por_bimestre'         => $porBimestre,
        'por_trimestre'        => $porTrimestre,
        'por_semestre'         => $porSemestre,
        'por_anio'             => $porAnio,
        'por_asesor'           => $porAsesor,
        'por_delegado'         => $porDelegado,
        'por_curso'            => $porCurso,
        'por_pais'             => $porPais,
        'por_metodo_pago'      => $porMetodoPago,
        'por_hora'             => $porHora,
        'por_campos_dinamicos' => $porCamposDinamicos  // ── NUEVO: gráficos dinámicos
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log("Error estadisticas: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener estadísticas']);
}
