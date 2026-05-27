<?php
// ============================================================
//  editar.php — Muestra formulario y procesa la edición
// ============================================================
session_start();
require_once 'conexion.php';

$conn = conectar();
$msg  = '';

// ── PROCESAR ACTUALIZACIÓN ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = intval($_POST['id']);
    $nombre      = trim(htmlspecialchars($_POST['nombre']      ?? ''));
    $apellido    = trim(htmlspecialchars($_POST['apellido']    ?? ''));
    $email       = trim(htmlspecialchars($_POST['email']       ?? ''));
    $telefono    = trim(htmlspecialchars($_POST['telefono']    ?? ''));
    $destino     = trim(htmlspecialchars($_POST['destino']     ?? ''));
    $fecha_viaje = $_POST['fecha_viaje'] ?? null;
    $num_personas = intval($_POST['num_personas'] ?? 1);
    $comentarios = trim(htmlspecialchars($_POST['comentarios'] ?? ''));

    if (empty($nombre) || empty($apellido) || empty($email)) {
        $msg = ['tipo' => 'error', 'texto' => '⚠️ Nombre, apellido y correo son obligatorios.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = ['tipo' => 'error', 'texto' => '⚠️ Correo electrónico no válido.'];
    } else {
        // Verificar email duplicado (excluyendo el propio registro)
        $chk = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $chk->bind_param("si", $email, $id);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows > 0) {
            $msg = ['tipo' => 'error', 'texto' => '⚠️ Ese correo ya está registrado por otro usuario.'];
            $chk->close();
        } else {
            $chk->close();
            $sql = "UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=?,
                    destino=?, fecha_viaje=?, num_personas=?, comentarios=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $fecha_db = !empty($fecha_viaje) ? $fecha_viaje : null;
            $stmt->bind_param("ssssssdsi", $nombre, $apellido, $email, $telefono,
                              $destino, $fecha_db, $num_personas, $comentarios, $id);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                $_SESSION['msg'] = ['tipo' => 'ok', 'texto' => '✅ Registro actualizado correctamente.'];
                header('Location: index.php');
                exit;
            } else {
                $msg = ['tipo' => 'error', 'texto' => '❌ Error al actualizar: ' . $conn->error];
            }
            $stmt->close();
        }
    }
}

// ── CARGAR DATOS DEL USUARIO ─────────────────────────────────
$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$u = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$u) {
    $_SESSION['msg'] = ['tipo' => 'error', 'texto' => '❌ Usuario no encontrado.'];
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Usuario — Wanderlust Travel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --teal:    #0d7c74;
    --teal-l:  #12a99e;
    --gold:    #c9a84c;
    --dark:    #0f1923;
    --mid:     #1e2f3e;
    --light:   #f0f4f8;
    --white:   #ffffff;
    --radius:  12px;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DM Sans', sans-serif;
    background: var(--dark);
    color: var(--white);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 16px;
}
.card {
    background: var(--mid);
    border-radius: var(--radius);
    border: 1px solid rgba(255,255,255,.08);
    padding: 40px;
    width: 100%;
    max-width: 620px;
    box-shadow: 0 20px 60px rgba(0,0,0,.5);
}
h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: var(--gold);
    margin-bottom: 6px;
}
p.sub { color: #8fa3b4; font-size: .9rem; margin-bottom: 28px; }
.row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
.field.full { grid-column: span 2; }
label { font-size: .82rem; color: #8fa3b4; text-transform: uppercase; letter-spacing: .06em; }
input, select, textarea {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 8px;
    padding: 11px 14px;
    color: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    transition: border-color .2s, box-shadow .2s;
}
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--teal-l);
    box-shadow: 0 0 0 3px rgba(18,169,158,.18);
}
textarea { resize: vertical; min-height: 80px; }
select option { background: var(--mid); }
.btns { display: flex; gap: 12px; margin-top: 8px; }
.btn-save {
    flex: 1;
    padding: 13px;
    background: linear-gradient(135deg, var(--teal), var(--teal-l));
    border: none; border-radius: 8px;
    color: var(--white); font-family: 'DM Sans', sans-serif;
    font-size: 1rem; font-weight: 500; cursor: pointer;
    transition: opacity .2s, transform .1s;
}
.btn-save:hover { opacity: .9; transform: translateY(-1px); }
.btn-cancel {
    padding: 13px 22px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px;
    color: #a0b4c5; font-family: 'DM Sans', sans-serif;
    font-size: 1rem; cursor: pointer;
    text-decoration: none; display: flex; align-items: center;
    transition: background .2s;
}
.btn-cancel:hover { background: rgba(255,255,255,.12); }
.alert {
    padding: 12px 16px; border-radius: 8px;
    margin-bottom: 22px; font-size: .9rem;
}
.alert.ok    { background: rgba(13,124,116,.25); border: 1px solid var(--teal); color: #7ee8de; }
.alert.error { background: rgba(200,50,50,.2);   border: 1px solid #c83232;    color: #f5a0a0; }
@media(max-width:520px){ .row{grid-template-columns:1fr;} .field.full{grid-column:span 1;} }
</style>
</head>
<body>
<div class="card">
    <h2>✏️ Editar Registro</h2>
    <p class="sub">Modifica los datos del viajero y guarda los cambios.</p>

    <?php if ($msg): ?>
    <div class="alert <?= $msg['tipo'] ?>"><?= $msg['texto'] ?></div>
    <?php endif; ?>

    <form method="POST" action="editar.php">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <div class="row">
            <div class="field">
                <label>Nombre *</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($u['nombre']) ?>" required>
            </div>
            <div class="field">
                <label>Apellido *</label>
                <input type="text" name="apellido" value="<?= htmlspecialchars($u['apellido']) ?>" required>
            </div>
            <div class="field">
                <label>Correo electrónico *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
            </div>
            <div class="field">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?= htmlspecialchars($u['telefono']) ?>">
            </div>
            <div class="field">
                <label>Destino</label>
                <select name="destino">
                    <?php
                    $destinos = ['Cancún, México','Los Cabos, México','Ciudad de México','Oaxaca, México',
                                 'París, Francia','Roma, Italia','Barcelona, España','Londres, UK',
                                 'Nueva York, USA','Miami, USA','Río de Janeiro, Brasil','Buenos Aires, Argentina',
                                 'Tokio, Japón','Bali, Indonesia','Otro'];
                    foreach ($destinos as $d):
                        $sel = ($u['destino'] === $d) ? 'selected' : '';
                    ?>
                    <option value="<?= $d ?>" <?= $sel ?>><?= $d ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Fecha de viaje</label>
                <input type="date" name="fecha_viaje" value="<?= htmlspecialchars($u['fecha_viaje'] ?? '') ?>">
            </div>
            <div class="field full">
                <label>Número de personas</label>
                <input type="number" name="num_personas" min="1" max="50" value="<?= $u['num_personas'] ?>">
            </div>
            <div class="field full">
                <label>Comentarios / solicitudes especiales</label>
                <textarea name="comentarios"><?= htmlspecialchars($u['comentarios'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="btns">
            <button type="submit" class="btn-save">💾 Guardar cambios</button>
            <a href="index.php" class="btn-cancel">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
