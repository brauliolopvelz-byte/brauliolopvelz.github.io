<?php
// ============================================================
//  guardar.php — Registra un nuevo usuario en la BD
// ============================================================
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitizar entradas
    $nombre      = trim(htmlspecialchars($_POST['nombre']      ?? ''));
    $apellido    = trim(htmlspecialchars($_POST['apellido']    ?? ''));
    $email       = trim(htmlspecialchars($_POST['email']       ?? ''));
    $telefono    = trim(htmlspecialchars($_POST['telefono']    ?? ''));
    $destino     = trim(htmlspecialchars($_POST['destino']     ?? ''));
    $fecha_viaje = $_POST['fecha_viaje'] ?? null;
    $num_personas = intval($_POST['num_personas'] ?? 1);
    $comentarios = trim(htmlspecialchars($_POST['comentarios'] ?? ''));

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($email)) {
        $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '⚠️ Nombre, apellido y correo son obligatorios.'];
        header('Location: index.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '⚠️ El correo electrónico no es válido.'];
        header('Location: index.php');
        exit;
    }

    $conn = conectar();

    // Verificar email duplicado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '⚠️ Ya existe un registro con ese correo electrónico.'];
        header('Location: index.php');
        exit;
    }
    $stmt->close();

    // Insertar registro
    $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, destino, fecha_viaje, num_personas, comentarios)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $fecha_db = !empty($fecha_viaje) ? $fecha_viaje : null;
    $stmt->bind_param("ssssssds", $nombre, $apellido, $email, $telefono, $destino, $fecha_db, $num_personas, $comentarios);

    if ($stmt->execute()) {
        $_SESSION['msg'] = ['tipo' => 'ok', 'texto' => '✅ ¡Registro exitoso! Bienvenido, ' . $nombre . '.'];
    } else {
        $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '❌ Error al guardar: ' . $conn->error];
    }

    $stmt->close();
    $conn->close();
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>
