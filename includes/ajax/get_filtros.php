<?php
/**
 * AJAX: Obtener valores únicos para filtros dependientes
 * Soporta vista_tipo: asesor, delegado, o vacío (dashboard)
 * Siempre devuelve estadísticas
 * Incluye filtros dinámicos respetando mostrar_filtro / mostrar_filtro_asesor / mostrar_filtro_delegado
 */
define('SISTEMA_REGISTROS', true);
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');
iniciarSesionSegura();
if (!estaAutenticado()) { echo json_encode(['success' => false, 'message' => 'No autenticado']); exit; }

try {
    $db = Database::getInstance()->getConnection();

    $vistaTipo    = isset($_GET['vista_tipo']) ? trim($_GET['vista_tipo']) : '';
    $formularioId = isset($_GET['formulario_id']) ? trim($_GET['formulario_id']) : '';
    $asesor       = isset($_GET['asesor']) ? trim($_GET['asesor']) : '';
    $delegado     = isset($_GET['delegado']) ? trim($_GET['delegado']) : '';
    $curso        = isset($_GET['curso']) ? trim($_GET['curso']) : '';
    $pais         = isset($_GET['pais']) ? trim($_GET['pais']) : '';
    $ciudad       = isset($_GET['ciudad']) ? trim($_GET['ciudad']) : '';
    $moneda       = isset($_GET['moneda']) ? trim($_GET['moneda']) : '';
    $metodoPago   = isset($_GET['metodo_pago']) ? trim($_GET['metodo_pago']) : '';
    $web          = isset($_GET['web']) ? trim($_GET['web']) : '';
    $categoria    = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
    $fechaDesde   = isset($_GET['fecha_desde']) ? trim($_GET['fecha_desde']) : '';
    $fechaHasta   = isset($_GET['fecha_hasta']) ? trim($_GET['fecha_hasta']) : '';
    $horaDesde    = isset($_GET['hora_desde']) ? trim($_GET['hora_desde']) : '';
    $horaHasta    = isset($_GET['hora_hasta']) ? trim($_GET['hora_hasta']) : '';
    $search       = isset($_GET['search']) ? trim($_GET['search']) : '';

    $inputParams = compact('search', 'formularioId', 'asesor', 'delegado', 'curso', 'pais', 'ciudad', 'moneda', 'metodoPago', 'web', 'categoria', 'fechaDesde', 'fechaHasta', 'horaDesde', 'horaHasta');

    function buildWhereExcluding($exclude, $ip, $vt) {
        $where = []; $params = [];

        if ($vt === 'asesor')       $where[] = "asesor IS NOT NULL AND asesor != ''";
        elseif ($vt === 'delegado') $where[] = "delegado IS NOT NULL AND delegado != ''";

        if ($exclude !== 'search' && $ip['search'] !== '') {
            $where[] = "(nombre LIKE :s1 OR apellidos LIKE :s2 OR telefono LIKE :s3 OR correo LIKE :s4 OR asesor LIKE :s5 OR delegado LIKE :s6 OR curso LIKE :s7 OR pais LIKE :s8 OR ciudad LIKE :s9 OR categoria LIKE :s10 OR formulario_id LIKE :s11)";
            $sp = '%' . $ip['search'] . '%';
            for ($i = 1; $i <= 11; $i++) $params[':s' . $i] = $sp;
        }
        $map = ['formularioId' => 'formulario_id', 'asesor' => 'asesor', 'delegado' => 'delegado', 'curso' => 'curso', 'pais' => 'pais', 'ciudad' => 'ciudad', 'moneda' => 'moneda', 'metodoPago' => 'metodo_pago', 'web' => 'web', 'categoria' => 'categoria'];
        foreach ($map as $key => $col) {
            if ($exclude !== $col && $ip[$key] !== '') {
                $where[] = "$col = :$col";
                $params[":$col"] = $ip[$key];
            }
        }
        if ($exclude !== 'fecha' && $ip['fechaDesde'] !== '') { $where[] = "fecha >= :fd"; $params[':fd'] = $ip['fechaDesde']; }
        if ($exclude !== 'fecha' && $ip['fechaHasta'] !== '') { $where[] = "fecha <= :fh"; $params[':fh'] = $ip['fechaHasta']; }
        if ($exclude !== 'hora'  && $ip['horaDesde']  !== '') { $where[] = "hora >= :hd";  $params[':hd'] = $ip['horaDesde']; }
        if ($exclude !== 'hora'  && $ip['horaHasta']  !== '') { $where[] = "hora <= :hh";  $params[':hh'] = $ip['horaHasta']; }

        $clause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
        return ['clause' => $clause, 'params' => $params];
    }

    // ── Filtros de columnas fijas ──
    $campos = ['formulario_id','asesor','delegado','curso','pais','ciudad','moneda','metodo_pago','web','categoria'];
    $filtros = [];

    foreach ($campos as $col) {
        $w = buildWhereExcluding($col, $inputParams, $vistaTipo);
        $sql = "SELECT DISTINCT `$col` FROM registros " . $w['clause'];
        if ($w['clause'] === '') $sql .= " WHERE `$col` IS NOT NULL AND `$col` != ''";
        else $sql .= " AND `$col` IS NOT NULL AND `$col` != ''";
        $sql .= " ORDER BY `$col` ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($w['params']);
        $filtros[$col] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── Filtros dinámicos según vista_tipo ──
    // Determinar qué columna de visibilidad usar para FILTRO y para LISTA
    if ($vistaTipo === 'asesor') {
        $colFiltro = 'mostrar_filtro_asesor';
        $colLista  = 'mostrar_lista_asesor';
    } elseif ($vistaTipo === 'delegado') {
        $colFiltro = 'mostrar_filtro_delegado';
        $colLista  = 'mostrar_lista_delegado';
    } else {
        $colFiltro = 'mostrar_filtro';
        $colLista  = 'mostrar_lista';
    }

    // ── MODIFICADO: SELECT incluye las columnas de visibilidad de lista y filtro ──
    $stmtDyn = $db->query(
        "SELECT nombre_campo, nombre_mostrar,
                mostrar_lista, mostrar_lista_asesor, mostrar_lista_delegado,
                mostrar_filtro, mostrar_filtro_asesor, mostrar_filtro_delegado,
                mostrar_filtro_estadisticas, mostrar_estadisticas
         FROM campos_dinamicos
         WHERE ($colFiltro = 1 OR $colLista = 1) AND activo = 1
         ORDER BY orden ASC"
    );
    $camposDinamicos = $stmtDyn->fetchAll(PDO::FETCH_ASSOC);
    $nombresCampos   = array_column($camposDinamicos, 'nombre_campo');

    // Solo carga valores de filtro para campos que tienen filtro habilitado en esta vista
    $camposConFiltro = array_column(
        array_filter($camposDinamicos, function($c) use ($colFiltro) {
            return !empty($c[$colFiltro]);
        }),
        'nombre_campo'
    );

    foreach ($camposConFiltro as $nc) {
        $w   = buildWhereExcluding('none', $inputParams, $vistaTipo);
        $bw  = $w['clause'];
        $bp  = $w['params'];
        $and = $bw ? ' AND' : ' WHERE';

        $path = '$.' . $nc;
        $sql  = "SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(campos_extra, :path)) as val
                 FROM registros
                 $bw
                 $and JSON_EXTRACT(campos_extra, :path2) IS NOT NULL
                   AND JSON_UNQUOTE(JSON_EXTRACT(campos_extra, :path3)) != ''
                 ORDER BY val ASC";

        $bp[':path']  = $path;
        $bp[':path2'] = $path;
        $bp[':path3'] = $path;

        $stmtF = $db->prepare($sql);
        $stmtF->execute($bp);
        $filtros['dyn_' . $nc] = $stmtF->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── Estadísticas ──
    $baseWhere = buildWhereExcluding('none', $inputParams, $vistaTipo);
    $bw = $baseWhere['clause'];
    $bp = $baseWhere['params'];
    $and = $bw ? " AND" : " WHERE";

    $stmtTotal = $db->prepare("SELECT COUNT(*) as c FROM registros $bw");
    $stmtTotal->execute($bp);
    $total = (int)$stmtTotal->fetch()['c'];

    $hoy = date('Y-m-d');
    $bpHoy = $bp; $bpHoy[':stat_hoy'] = $hoy;
    $stmtHoy = $db->prepare("SELECT COUNT(*) as c FROM registros $bw $and fecha = :stat_hoy");
    $stmtHoy->execute($bpHoy);
    $totalHoy = (int)$stmtHoy->fetch()['c'];

    $lunes = date('Y-m-d', strtotime('monday this week'));
    $bpSem = $bp; $bpSem[':stat_lunes'] = $lunes;
    $stmtSemana = $db->prepare("SELECT COUNT(*) as c FROM registros $bw $and fecha >= :stat_lunes");
    $stmtSemana->execute($bpSem);
    $totalSemana = (int)$stmtSemana->fetch()['c'];

    $mes = date('Y-m-01');
    $bpMes = $bp; $bpMes[':stat_mes'] = $mes;
    $stmtMes = $db->prepare("SELECT COUNT(*) as c FROM registros $bw $and fecha >= :stat_mes");
    $stmtMes->execute($bpMes);
    $totalMes = (int)$stmtMes->fetch()['c'];

    $stmtAse = $db->prepare("SELECT COUNT(DISTINCT asesor) as c FROM registros $bw $and asesor IS NOT NULL AND asesor != ''");
    $stmtAse->execute($bp);
    $totalAsesores = (int)$stmtAse->fetch()['c'];

    $stmtDel = $db->prepare("SELECT COUNT(DISTINCT delegado) as c FROM registros $bw $and delegado IS NOT NULL AND delegado != ''");
    $stmtDel->execute($bp);
    $totalDelegados = (int)$stmtDel->fetch()['c'];

    $stmtCur = $db->prepare("SELECT COUNT(DISTINCT curso) as c FROM registros $bw $and curso IS NOT NULL AND curso != ''");
    $stmtCur->execute($bp);
    $totalCursos = (int)$stmtCur->fetch()['c'];

    $stmtPai = $db->prepare("SELECT COUNT(DISTINCT pais) as c FROM registros $bw $and pais IS NOT NULL AND pais != ''");
    $stmtPai->execute($bp);
    $totalPaises = (int)$stmtPai->fetch()['c'];

    $stats = [
        'total'     => $total,
        'hoy'       => $totalHoy,
        'semana'    => $totalSemana,
        'mes'       => $totalMes,
        'asesores'  => $totalAsesores,
        'delegados' => $totalDelegados,
        'cursos'    => $totalCursos,
        'paises'    => $totalPaises
    ];

    echo json_encode([
        'success'          => true,
        'filtros'          => $filtros,
        'stats'            => $stats,
        'campos_dinamicos' => $camposDinamicos, // [{nombre_campo, nombre_mostrar, mostrar_lista, mostrar_lista_asesor, ...}]
    ]);

} catch (PDOException $e) {
    error_log("Error get_filtros: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener filtros']);
}
