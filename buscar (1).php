<?php
// ============================================================
//  buscar.php — Devuelve filas filtradas (llamado desde index.php
//               o como endpoint AJAX que retorna HTML de tabla)
// ============================================================
require_once 'conexion.php';

/**
 * Busca usuarios según término y campo.
 *
 * @param string $termino  Texto a buscar
 * @param string $campo    Campo por el que filtrar (nombre|email|destino|todos)
 * @return array           Array de registros
 */
function buscarUsuarios(string $termino, string $campo = 'todos'): array {
    $conn = conectar();
    $like = '%' . $termino . '%';

    $campos_validos = ['nombre', 'apellido', 'email', 'telefono', 'destino'];

    if ($campo === 'todos') {
        $sql  = "SELECT * FROM usuarios WHERE
                    nombre      LIKE ? OR
                    apellido    LIKE ? OR
                    email       LIKE ? OR
                    telefono    LIKE ? OR
                    destino     LIKE ?
                 ORDER BY creado_en DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
    } elseif (in_array($campo, $campos_validos, true)) {
        $sql  = "SELECT * FROM usuarios WHERE `$campo` LIKE ? ORDER BY creado_en DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $like);
    } else {
        return [];
    }

    $stmt->execute();
    $result   = $stmt->get_result();
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $usuarios;
}

/**
 * Devuelve TODOS los usuarios ordenados por fecha.
 */
function obtenerTodos(): array {
    $conn   = conectar();
    $result = $conn->query("SELECT * FROM usuarios ORDER BY creado_en DESC");
    $data   = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $data;
}

// ── Si se llama directamente vía AJAX ────────────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    header('Content-Type: application/json; charset=utf-8');
    $termino  = trim($_GET['q']     ?? '');
    $campo    = trim($_GET['campo'] ?? 'todos');
    $usuarios = empty($termino) ? obtenerTodos() : buscarUsuarios($termino, $campo);
    echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
