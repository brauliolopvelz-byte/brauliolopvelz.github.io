<?php
// ============================================================
//  conexion.php — Configuración de la base de datos
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Cambia por tu usuario
define('DB_PASS', '');            // Cambia por tu contraseña
define('DB_NAME', 'agencia_viajes');

function conectar() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("<div style='color:red;padding:20px;font-family:sans-serif;'>
            ❌ Error de conexión: " . $conn->connect_error . "
            <br><small>Verifica que MySQL esté activo y los datos en conexion.php sean correctos.</small>
        </div>");
    }

    $conn->set_charset("utf8mb4");
    return $conn;
}

// ── Crear base de datos y tabla si no existen ───────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if (!$conn->connect_error) {
    $conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db(DB_NAME);
    $conn->query("
        CREATE TABLE IF NOT EXISTS usuarios (
            id          INT AUTO_INCREMENT PRIMARY KEY,
            nombre      VARCHAR(100) NOT NULL,
            apellido    VARCHAR(100) NOT NULL,
            email       VARCHAR(150) NOT NULL UNIQUE,
            telefono    VARCHAR(20),
            destino     VARCHAR(150),
            fecha_viaje DATE,
            num_personas INT DEFAULT 1,
            comentarios TEXT,
            creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    $conn->close();
}
?>
