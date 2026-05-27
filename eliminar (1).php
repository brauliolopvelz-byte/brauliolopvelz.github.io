<?php
// ============================================================
//  eliminar.php — Elimina un usuario por ID
// ============================================================
session_start();
require_once 'conexion.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '❌ ID de usuario no válido.'];
    header('Location: index.php');
    exit;
}

$conn = conectar();

// Verificar que existe
$chk = $conn->prepare("SELECT nombre, apellido FROM usuarios WHERE id = ?");
$chk->bind_param("i", $id);
$chk->execute();
$chk->bind_result($nombre, $apellido);
if (!$chk->fetch()) {
    $chk->close();
    $conn->close();
    $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '❌ El usuario no existe o ya fue eliminado.'];
    header('Location: index.php');
    exit;
}
$chk->close();

// Eliminar
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['msg'] = ['tipo' => 'ok', 'texto' => "🗑️ Se eliminó el registro de <strong>$nombre $apellido</strong>."];
} else {
    $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '❌ Error al eliminar: ' . $conn->error];
}

$stmt->close();
$conn->close();
header('Location: index.php');
exit;
?>
