<?php
// ============================================================
//  index.php — Página principal de Wanderlust Travel Agency
// ============================================================
session_start();
require_once 'conexion.php';
require_once 'buscar.php';   // Carga obtenerTodos() y buscarUsuarios()

// Recuperar mensaje de sesión (de guardar/editar/eliminar)
$flash = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);

// Cargar usuarios para la tabla inicial
$usuarios = obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wanderlust Travel — Agencia de Viajes</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ── Variables ────────────────────────────────────────────── */
:root {
    --teal:    #0d7c74;
    --teal-l:  #15b8ac;
    --teal-d:  #084f4a;
    --gold:    #c9a84c;
    --gold-l:  #e4c46a;
    --dark:    #0b1520;
    --mid:     #152232;
    --card:    #1a2d40;
    --border:  rgba(255,255,255,.09);
    --text:    #d8e8f2;
    --muted:   #6e8fa5;
    --radius:  14px;
    --danger:  #e05050;
}

/* ── Reset & base ─────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'DM Sans', sans-serif;
    background: var(--dark);
    color: var(--text);
    min-height: 100vh;
    line-height: 1.6;
}

/* ── Hero / Header ────────────────────────────────────────── */
.hero {
    background: linear-gradient(160deg, var(--teal-d) 0%, var(--dark) 60%);
    padding: 50px 20px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230d7c74' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
}
.logo {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.2rem);
    color: var(--white, #fff);
    letter-spacing: -.01em;
    position: relative;
}
.logo span { color: var(--gold); }
.tagline {
    color: var(--muted);
    font-size: .95rem;
    margin-top: 6px;
    letter-spacing: .12em;
    text-transform: uppercase;
    position: relative;
}
.nav-links {
    display: flex; gap: 12px; justify-content: center;
    margin-top: 24px; position: relative;
}
.nav-link {
    padding: 8px 20px;
    border-radius: 50px;
    font-size: .88rem; font-weight: 500;
    text-decoration: none; transition: all .2s;
}
.nav-link.active {
    background: var(--teal); color: #fff;
}
.nav-link:not(.active) {
    border: 1px solid var(--border); color: var(--muted);
}
.nav-link:not(.active):hover { border-color: var(--teal-l); color: var(--teal-l); }

/* ── Main layout ──────────────────────────────────────────── */
main { max-width: 1200px; margin: 0 auto; padding: 36px 20px 60px; }

/* ── Flash message ────────────────────────────────────────── */
.flash {
    padding: 14px 18px; border-radius: 10px;
    margin-bottom: 28px; font-size: .95rem;
    animation: slideDown .35s ease;
}
@keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:none} }
.flash.ok    { background: rgba(13,124,116,.2); border: 1px solid var(--teal); color: #7ee8de; }
.flash.error { background: rgba(224,80,80,.15); border: 1px solid var(--danger); color: #f5a0a0; }

/* ── Section titles ───────────────────────────────────────── */
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.55rem; color: #fff;
    margin-bottom: 6px;
}
.section-sub { color: var(--muted); font-size: .88rem; margin-bottom: 22px; }

/* ── Card ─────────────────────────────────────────────────── */
.card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 32px;
    margin-bottom: 36px;
    box-shadow: 0 8px 40px rgba(0,0,0,.35);
}

/* ── Form grid ────────────────────────────────────────────── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 18px;
}
.field { display: flex; flex-direction: column; gap: 7px; }
.field.span2 { grid-column: span 2; }
label {
    font-size: .78rem; color: var(--muted);
    text-transform: uppercase; letter-spacing: .07em; font-weight: 500;
}
input, select, textarea {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 9px;
    padding: 11px 14px;
    color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: .95rem;
    transition: border-color .2s, box-shadow .2s;
    width: 100%;
}
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--teal-l);
    box-shadow: 0 0 0 3px rgba(21,184,172,.15);
}
select option { background: var(--card); }
textarea { resize: vertical; min-height: 85px; }

.btn-submit {
    margin-top: 24px;
    padding: 13px 32px;
    background: linear-gradient(135deg, var(--teal), var(--teal-l));
    border: none; border-radius: 9px;
    color: #fff; font-family: 'DM Sans', sans-serif;
    font-size: 1rem; font-weight: 600; cursor: pointer;
    transition: opacity .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 20px rgba(13,124,116,.4);
}
.btn-submit:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 6px 24px rgba(13,124,116,.5); }

/* ── Buscador ─────────────────────────────────────────────── */
.search-bar {
    display: flex; gap: 10px; margin-bottom: 20px;
    flex-wrap: wrap;
}
.search-bar input {
    flex: 1; min-width: 180px;
}
.search-bar select { width: 160px; }
.btn-buscar {
    padding: 11px 22px;
    background: var(--teal);
    border: none; border-radius: 9px;
    color: #fff; font-family: 'DM Sans', sans-serif;
    font-size: .95rem; cursor: pointer;
    transition: background .2s;
}
.btn-buscar:hover { background: var(--teal-l); }

/* ── Contador ─────────────────────────────────────────────── */
.counter {
    font-size: .85rem; color: var(--muted);
    margin-bottom: 14px;
}
.counter strong { color: var(--gold); }

/* ── Table wrapper ────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
table {
    width: 100%; border-collapse: collapse;
    font-size: .9rem;
}
thead th {
    background: rgba(13,124,116,.2);
    color: var(--teal-l);
    padding: 12px 14px; text-align: left;
    font-weight: 600; text-transform: uppercase;
    font-size: .75rem; letter-spacing: .06em;
    white-space: nowrap;
}
tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
tbody tr:hover { background: rgba(255,255,255,.03); }
td {
    padding: 12px 14px;
    color: var(--text);
    vertical-align: middle;
}
td .badge {
    display: inline-block;
    padding: 3px 10px; border-radius: 50px;
    font-size: .78rem; font-weight: 500;
    background: rgba(13,124,116,.2); color: var(--teal-l);
    white-space: nowrap;
}
.no-data {
    text-align: center; padding: 40px;
    color: var(--muted); font-style: italic;
}

/* ── Action buttons ───────────────────────────────────────── */
.actions { display: flex; gap: 8px; }
.btn-edit, .btn-del {
    padding: 6px 14px; border-radius: 7px;
    font-family: 'DM Sans', sans-serif; font-size: .82rem;
    font-weight: 500; cursor: pointer;
    text-decoration: none; border: none; display: inline-flex;
    align-items: center; gap: 5px; transition: all .2s;
    white-space: nowrap;
}
.btn-edit {
    background: rgba(201,168,76,.15); color: var(--gold-l);
    border: 1px solid rgba(201,168,76,.3);
}
.btn-edit:hover { background: rgba(201,168,76,.28); }
.btn-del {
    background: rgba(224,80,80,.15); color: #f08080;
    border: 1px solid rgba(224,80,80,.3);
}
.btn-del:hover { background: rgba(224,80,80,.3); }

/* ── Footer ───────────────────────────────────────────────── */
footer {
    text-align: center; padding: 24px;
    color: var(--muted); font-size: .82rem;
    border-top: 1px solid var(--border);
}
footer span { color: var(--gold); }

/* ── Responsive ───────────────────────────────────────────── */
@media(max-width:640px){
    .card { padding: 20px; }
    .field.span2 { grid-column: span 1; }
}
</style>
</head>
<body>

<!-- ──────────── HERO ──────────── -->
<header class="hero">
    <div class="logo">✈ Wanderlust <span>Travel</span></div>
    <p class="tagline">Agencia de Viajes — Sistema de Registro</p>
    <nav class="nav-links">
        <a href="#registro" class="nav-link active">Nuevo registro</a>
        <a href="#usuarios" class="nav-link">Ver viajeros</a>
    </nav>
</header>

<main>

    <!-- Flash message -->
    <?php if ($flash): ?>
    <div class="flash <?= $flash['tipo'] ?>"><?= $flash['texto'] ?></div>
    <?php endif; ?>

    <!-- ──────────── FORMULARIO DE REGISTRO ──────────── -->
    <section id="registro" class="card">
        <h2 class="section-title">🌍 Registrar nuevo viajero</h2>
        <p class="section-sub">Completa el formulario con los datos del cliente para iniciar su reservación.</p>

        <form method="POST" action="guardar.php" novalidate>
            <div class="form-grid">
                <div class="field">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: María" required maxlength="100">
                </div>
                <div class="field">
                    <label for="apellido">Apellido *</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ej: González" required maxlength="100">
                </div>
                <div class="field">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required maxlength="150">
                </div>
                <div class="field">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="+52 961 000 0000" maxlength="20">
                </div>
                <div class="field">
                    <label for="destino">Destino deseado</label>
                    <select id="destino" name="destino">
                        <option value="">— Selecciona destino —</option>
                        <optgroup label="México">
                            <option>Cancún, México</option>
                            <option>Los Cabos, México</option>
                            <option>Ciudad de México</option>
                            <option>Oaxaca, México</option>
                            <option>Chiapas, México</option>
                        </optgroup>
                        <optgroup label="Europa">
                            <option>París, Francia</option>
                            <option>Roma, Italia</option>
                            <option>Barcelona, España</option>
                            <option>Londres, UK</option>
                        </optgroup>
                        <optgroup label="América">
                            <option>Nueva York, USA</option>
                            <option>Miami, USA</option>
                            <option>Río de Janeiro, Brasil</option>
                            <option>Buenos Aires, Argentina</option>
                        </optgroup>
                        <optgroup label="Asia / Otros">
                            <option>Tokio, Japón</option>
                            <option>Bali, Indonesia</option>
                            <option>Otro</option>
                        </optgroup>
                    </select>
                </div>
                <div class="field">
                    <label for="fecha_viaje">Fecha de viaje</label>
                    <input type="date" id="fecha_viaje" name="fecha_viaje">
                </div>
                <div class="field">
                    <label for="num_personas">Número de personas</label>
                    <input type="number" id="num_personas" name="num_personas" min="1" max="50" value="1">
                </div>
                <div class="field span2">
                    <label for="comentarios">Comentarios / solicitudes especiales</label>
                    <textarea id="comentarios" name="comentarios" placeholder="Dietas especiales, habitaciones, presupuesto, etc."></textarea>
                </div>
            </div>
            <button type="submit" class="btn-submit">✈ Registrar viajero</button>
        </form>
    </section>

    <!-- ──────────── TABLA DE USUARIOS ──────────── -->
    <section id="usuarios" class="card">
        <h2 class="section-title">🧳 Viajeros registrados</h2>
        <p class="section-sub">Busca, edita o elimina los registros existentes.</p>

        <!-- Buscador -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="🔍  Buscar viajero..." oninput="buscarUsuarios()">
            <select id="searchCampo" onchange="buscarUsuarios()">
                <option value="todos">Todos los campos</option>
                <option value="nombre">Nombre</option>
                <option value="apellido">Apellido</option>
                <option value="email">Correo</option>
                <option value="destino">Destino</option>
            </select>
        </div>

        <p class="counter">Mostrando <strong id="conteo"><?= count($usuarios) ?></strong> registro(s)</p>

        <div class="table-wrap">
            <table id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Destino</th>
                        <th>Fecha viaje</th>
                        <th>Personas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyUsuarios">
                    <?php if (empty($usuarios)): ?>
                    <tr><td colspan="8" class="no-data">No hay viajeros registrados aún.</td></tr>
                    <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><strong><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['telefono'] ?: '—') ?></td>
                        <td><span class="badge"><?= htmlspecialchars($u['destino'] ?: 'Sin destino') ?></span></td>
                        <td><?= $u['fecha_viaje'] ? date('d/m/Y', strtotime($u['fecha_viaje'])) : '—' ?></td>
                        <td><?= $u['num_personas'] ?></td>
                        <td>
                            <div class="actions">
                                <a href="editar.php?id=<?= $u['id'] ?>" class="btn-edit">✏️ Editar</a>
                                <a href="eliminar.php?id=<?= $u['id'] ?>"
                                   class="btn-del"
                                   onclick="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($u['nombre'] . ' ' . $u['apellido'])) ?>?')">🗑️ Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer>
    &copy; <?= date('Y') ?> <span>Wanderlust Travel Agency</span> — Todos los derechos reservados.
</footer>

<script>
// ── Búsqueda en tiempo real vía AJAX ──────────────────────────
let timer;
function buscarUsuarios() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const q     = document.getElementById('searchInput').value.trim();
        const campo = document.getElementById('searchCampo').value;

        fetch(`buscar.php?ajax=1&q=${encodeURIComponent(q)}&campo=${encodeURIComponent(campo)}`)
            .then(r => r.json())
            .then(data => renderTabla(data))
            .catch(() => {});
    }, 300);
}

function renderTabla(usuarios) {
    const tbody = document.getElementById('tbodyUsuarios');
    document.getElementById('conteo').textContent = usuarios.length;

    if (usuarios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="no-data">No se encontraron resultados.</td></tr>';
        return;
    }

    tbody.innerHTML = usuarios.map(u => {
        const nombre = (u.nombre + ' ' + u.apellido).replace(/</g,'&lt;');
        const email  = (u.email || '').replace(/</g,'&lt;');
        const tel    = (u.telefono || '—').replace(/</g,'&lt;');
        const dest   = (u.destino  || 'Sin destino').replace(/</g,'&lt;');
        const fecha  = u.fecha_viaje
            ? u.fecha_viaje.split('-').reverse().join('/')
            : '—';

        return `<tr>
            <td>${u.id}</td>
            <td><strong>${nombre}</strong></td>
            <td>${email}</td>
            <td>${tel}</td>
            <td><span class="badge">${dest}</span></td>
            <td>${fecha}</td>
            <td>${u.num_personas}</td>
            <td>
                <div class="actions">
                    <a href="editar.php?id=${u.id}" class="btn-edit">✏️ Editar</a>
                    <a href="eliminar.php?id=${u.id}" class="btn-del"
                       onclick="return confirm('¿Eliminar a ${nombre}?')">🗑️ Eliminar</a>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ── Auto-scroll al flash de sesión ───────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.querySelector('.flash');
    if (flash) {
        flash.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => flash.style.opacity = '0', 5000);
        setTimeout(() => flash.remove(), 5400);
    }
});
</script>
</body>
</html>
