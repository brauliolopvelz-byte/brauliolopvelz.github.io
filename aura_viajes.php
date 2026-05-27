<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aura Viajes | Panel de Control, Reportes y Mensajería</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <!-- LIBRERÍAS DE EXPORTACIÓN (PDF Y EXCEL) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --primary-blue: #185FA5;
      --dark-blue: #042C53;
      --accent-blue: #85B7EB;
      --bg-light: #f8fafc;
      --white: #ffffff;
      --text-main: #1e293b;
      --error: #e11d48;
      --success: #10b981;
      --warning: #f59e0b;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: var(--bg-light);
      color: var(--text-main);
      line-height: 1.6;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    /* NAVBAR / USER SECTION */
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 0;
    }
    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      background: var(--white);
      padding: 5px 15px;
      border-radius: 30px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      font-size: 13px;
      font-weight: 600;
    }
    .user-avatar {
      width: 30px;
      height: 30px;
      background: var(--accent-blue);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    /* ====== CARRUSEL TOTALMENTE RESTABLECIDO Y MEJORADO ====== */
    .hero-carousel {
      position: relative;
      width: 100%;
      height: 380px;
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .carousel-track-container {
      width: 100%;
      height: 100%;
      overflow: hidden;
    }
    .carousel-track {
      display: flex;
      height: 100%;
      width: 300%; /* Tres diapositivas */
      transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .carousel-slide {
      width: 33.333%;
      height: 100%;
      position: relative;
      background-size: cover;
      background-position: center;
    }
    .carousel-slide::after {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background: linear-gradient(rgba(4, 44, 83, 0.4), rgba(4, 44, 83, 0.7));
    }
    .hero-content {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      z-index: 10;
      text-align: center;
      color: white;
      width: 85%;
    }
    .hero-content h1 {
      font-size: clamp(24px, 5vw, 36px);
      font-weight: 700;
      margin-bottom: 0.8rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .hero-content p {
      font-size: clamp(14px, 2vw, 16px);
      font-weight: 300;
      opacity: 0.95;
    }
    .carousel-btn {
      position: absolute;
      top: 50%; transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      color: white; border: none;
      width: 45px; height: 45px;
      border-radius: 50%; cursor: pointer;
      z-index: 20; transition: all 0.3s ease;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .carousel-btn:hover { background: var(--primary-blue); transform: translateY(-50%) scale(1.05); }
    .prev { left: 20px; }
    .next { right: 20px; }
    
    .carousel-nav {
      position: absolute;
      bottom: 15px; left: 50%;
      transform: translateX(-50%);
      display: flex; gap: 8px; z-index: 20;
    }
    .carousel-indicator {
      width: 10px; height: 10px; border-radius: 50%;
      background: rgba(255,255,255,0.4); border: none; cursor: pointer;
      transition: background 0.3s;
    }
    .carousel-indicator.active { background: white; width: 24px; border-radius: 5px; }

    /* SECCIONES DINÁMICAS */
    .section { display: none; }
    .section.active { display: block; }

    .section-header-inline {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 2rem 0 1.5rem;
    }

    .section-title {
      font-size: 24px;
      font-weight: 700;
      border-left: 5px solid var(--primary-blue);
      padding-left: 15px;
      color: var(--dark-blue);
    }

    /* BOTONES DE ACCIÓN GLOBAL */
    .btn-print-screen {
      padding: 10px 18px;
      background: #64748b;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-print-screen:hover { background: var(--text-main); transform: translateY(-1px); }

    /* FORMULARIO Y TARJETAS */
    .form-card {
      background: var(--white);
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
      margin-bottom: 3rem;
    }
    .form-title { font-weight: 700; font-size: 20px; margin-bottom: 20px; color: var(--primary-blue); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    input, select, textarea {
      width: 100%; padding: 14px;
      border: 1px solid #e2e8f0; border-radius: 10px;
      background: #f1f5f9; font-family: inherit; font-size: 14px;
      transition: border-color 0.3s;
    }
    input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary-blue); background: var(--white); }
    textarea { grid-column: 1 / -1; min-height: 110px; resize: none; }
    .btn-row { grid-column: 1 / -1; display: flex; gap: 12px; margin-top: 10px; }
    
    .btn-primary {
      flex: 2; padding: 15px; background: var(--primary-blue);
      color: white; border: none; border-radius: 10px;
      font-weight: 700; cursor: pointer; transition: 0.3s; font-size: 15px;
    }
    .btn-secondary {
      flex: 1; padding: 15px; background: var(--accent-blue);
      color: white; border: none; border-radius: 10px;
      font-weight: 700; cursor: pointer; transition: 0.3s; font-size: 15px;
    }
    .btn-primary:hover { background: var(--dark-blue); }
    .btn-secondary:hover { background: #60a5fa; }
    
    /* ACCIONES DE EXPORTACIÓN */
    .btn-pdf { padding: 10px 16px; background: var(--error); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 13px; }
    .btn-pdf:hover { background: #b91c1c; }
    .btn-excel { padding: 10px 16px; background: var(--success); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 13px; }
    .btn-excel:hover { background: #047857; }

    /* DESTINOS */
    .destinations-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 2.5rem; }
    .dest-card { background: var(--white); border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04); transition: 0.3s; }
    .dest-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .dest-img { height: 180px; width: 100%; object-fit: cover; }
    .dest-info { padding: 1.25rem; }
    .dest-name { font-size: 18px; font-weight: 700; color: var(--dark-blue); }
    .dest-price { color: var(--primary-blue); font-weight: 600; font-size: 14px; margin-top: 2px; }

    /* TABLA ADMINISTRATIVA */
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 12px; }
    .table-title { font-weight: 700; font-size: 22px; color: var(--dark-blue); }
    .search-box { position: relative; }
    .search-icon { position: absolute; left: 12px; top: 11px; color: #94a3b8; font-size: 15px; }
    .search-box input { padding-left: 38px; width: 220px; background: white; padding-top: 10px; padding-bottom: 10px; }
    .back-btn { padding: 10px 20px; border-radius: 10px; border: none; background: #e2e8f0; cursor: pointer; font-weight: 600; transition: 0.3s; }
    .back-btn:hover { background: #cbd5e1; }
    
    .table-wrap { background: white; border-radius: 15px; overflow-x: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 750px; }
    th { background: #f8fafc; padding: 16px; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    td { padding: 16px; border-top: 1px solid #f1f5f9; font-size: 14px; vertical-align: middle; }
    
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; }
    .badge-europa { background: #dbeafe; color: #1e40af; }
    .badge-asia { background: #fef3c7; color: #92400e; }
    .badge-mexico { background: #dcfce7; color: #166534; }

    .actions { display: flex; gap: 6px; }
    .act-btn { padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s; }
    .act-btn.edit { background: var(--accent-blue); color: white; }
    .act-btn.edit:hover { background: var(--primary-blue); }
    .act-btn.del { background: #fee2e2; color: #b91c1c; }
    .act-btn.del:hover { background: #fca5a5; }
    .act-btn.email { background: #fef3c7; color: #d97706; }
    .act-btn.email:hover { background: #fde68a; }

    /* PREVIO DE CORREO */
    .email-preview-box {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      padding: 18px;
      border-radius: 12px;
      margin-top: 15px;
      font-size: 13px;
      color: #334155;
    }
    .email-header-line { margin-bottom: 6px; font-weight: 600; color: #475569; }

    /* CARGA SMSTP DINÁMICA DE COLA */
    .sending-overlay {
      display: none; position: absolute; top:0; left:0; width:100%; height:100%;
      background: rgba(255,255,255,0.95); border-radius: 20px;
      align-items: center; justify-content: center; flex-direction: column; z-index: 10;
    }
    .spinner {
      width: 45px; height: 45px; border: 4px solid #cbd5e1; border-top-color: var(--primary-blue);
      border-radius: 50%; animation: spin 0.8s infinite linear; margin-bottom: 15px;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* COMPONENTE MODAL */
    .modal-overlay { 
      position: fixed; top:0; left:0; width:100%; height:100%; 
      background: rgba(15, 23, 42, 0.6); display:none; align-items:center; justify-content:center; z-index: 100;
      backdrop-filter: blur(4px);
    }
    .modal-overlay.open { display: flex; }
    .modal { background: white; padding: 2.5rem; border-radius: 20px; width: 92%; max-width: 560px; position: relative; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .modal-actions { display: flex; gap: 12px; margin-top: 24px; }
    .modal-save { flex: 2; padding: 14px; background: var(--primary-blue); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight:600; font-size:14px;}
    .modal-cancel { flex: 1; padding: 14px; background: #e2e8f0; border: none; border-radius: 10px; cursor: pointer; font-weight:600; color:#475569; font-size:14px;}
    .modal-save:hover { background: var(--dark-blue); }
    .modal-cancel:hover { background: #cbd5e1; }

    /* TOAST DE SISTEMA */
    .toast { 
      position: fixed; bottom: 25px; right: 25px; background: var(--dark-blue); 
      color: white; padding: 14px 28px; border-radius: 12px; display: none; z-index: 1000;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); font-weight: 600; font-size: 13px;
    }

    .social-section { text-align: center; background: var(--dark-blue); color: white; padding: 3.5rem 1.5rem; border-radius: 20px; margin-bottom: 2rem; }
    .social-section h3 { font-size: 22px; margin-bottom: 8px; }
    .footer { text-align: center; padding: 2rem; font-size: 12px; color: #64748b; }

    /* ================= ESTILOS ESPECÍFICOS DE IMPRESIÓN (MEDIO DE INFORMACIÓN) ================= */
    @media print {
      body { background: white; color: black; }
      /* Escondemos elementos innecesarios e interfaces de control en el reporte impreso físico/PDF nativo */
      .hero-carousel, .top-bar, .back-btn, .btn-print-screen, .search-box, .btn-pdf, .btn-excel, .actions, .social-section, .footer, .btn-row, .destinations-grid {
        display: none !important;
      }
      .container { width: 100%; max-width: 100%; padding: 0; margin: 0; }
      .form-card, .table-wrap { box-shadow: none; border: 2px solid #94a3b8; background: white; border-radius: 12px; }
      .section { display: none !important; }
      .section.active { display: block !important; }
      .section-title { border-left: 6px solid #000; color: #000; font-size: 26px; padding-left: 10px; margin-bottom: 25px; }
      th { background: #e2e8f0 !important; color: #000 !important; font-weight: bold; border-bottom: 2px solid #000; }
      td { border-top: 1px solid #94a3b8; }
      .badge { border: 1px solid #000 !important; background: transparent !important; color: #000 !important; padding: 2px 6px; }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- ENCABEZADO -->
  <div class="top-bar">
    <div style="font-weight: 700; color: var(--primary-blue); letter-spacing: 1.5px; font-size: 18px;">AURA VIAJES</div>
    <div class="user-profile">
      <div class="user-avatar">AV</div>
      <span>Módulo de Operaciones</span>
    </div>
  </div>

  <!-- ====== CARRUSEL TOTALMENTE RESTABLECIDO Y OPERATIVO ====== -->
  <div class="hero-carousel">
    <div class="carousel-track-container">
      <div class="carousel-track" id="carouselTrack">
        <!-- Slide 1 -->
        <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80');"></div>
        <!-- Slide 2 -->
        <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80');"></div>
        <!-- Slide 3 -->
        <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1200&q=80');"></div>
      </div>
    </div>
    <div class="hero-content">
      <h1 id="carouselTitle">Planifica tu Próxima Aventura</h1>
      <p id="carouselDesc">Gestión integral de expediciones y canales directos de reporte corporativo.</p>
    </div>
    
    <!-- Botones de Navegación -->
    <button class="carousel-btn prev" onclick="moveCarousel(-1)">❮</button>
    <button class="carousel-btn next" onclick="moveCarousel(1)">❯</button>
    
    <!-- Indicadores Inferiores -->
    <div class="carousel-nav">
      <button class="carousel-indicator active" onclick="jumpToSlide(0)"></button>
      <button class="carousel-indicator" onclick="jumpToSlide(1)"></button>
      <button class="carousel-indicator" onclick="jumpToSlide(2)"></button>
    </div>
  </div>

  <!-- SECCIÓN FORMULARIO / VISTA DE CLIENTE -->
  <div id="formSection" class="section active">
    <div class="section-header-inline">
      <h2 class="section-title">Catálogo y Solicitudes</h2>
      <button class="btn-print-screen" onclick="window.print()">🖨️ Imprimir Pantalla actual</button>
    </div>
    
    <div class="destinations-grid">
      <div class="dest-card">
        <img src="https://images.unsplash.com/photo-1518105779142-d975f22f1b0a?auto=format&fit=crop&w=400&q=80" class="dest-img" alt="Guanajuato">
        <div class="dest-info">
          <div class="dest-name">Guanajuato, MX</div>
          <div class="dest-price">Desde $4,500 MXN</div>
        </div>
      </div>
      <div class="dest-card">
        <img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=400&q=80" class="dest-img" alt="París">
        <div class="dest-info">
          <div class="dest-name">París, Francia</div>
          <div class="dest-price">Desde $1,200 USD</div>
        </div>
      </div>
    </div>

    <div class="form-card">
      <p class="form-title">Alta de Solicitud de Viaje</p>
      <div class="form-grid">
        <input id="fNombre" type="text" placeholder="Nombre completo del titular">
        <input id="fEmail" type="email" placeholder="Correo electrónico de contacto">
        <select id="fDestino">
          <option value="">Seleccione un Destino Destacado</option>
          <option>Europa</option>
          <option>México Mágico</option>
          <option>Asia</option>
        </select>
        <input id="fFecha" type="date">
        <textarea id="fNota" placeholder="Especificaciones adicionales de la ruta o requerimientos del cliente..."></textarea>
        <div class="btn-row">
          <button class="btn-primary" onclick="enviarSolicitud()">Registrar e Ingresar</button>
          <button class="btn-secondary" onclick="mostrarTabla()">Panel Administrativo →</button>
        </div>
      </div>
    </div>
  </div>

  <!-- SECCIÓN TABLA ADMINISTRATIVA Y EXPORTACIÓN DE REPORTES -->
  <div id="tableSection" class="section">
    <div class="section-header-inline" style="margin-top:0;">
      <p class="table-title">Consola de Control e Informes</p>
      <button class="btn-print-screen" onclick="window.print()">🖨️ Imprimir Pantalla actual</button>
    </div>

    <div class="table-header">
      <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <div class="search-box">
          <span class="search-icon">⌕</span>
          <input id="searchInput" type="text" placeholder="Filtrar registros..." oninput="renderTable()">
        </div>
        <!-- ACCIONES DE EXPORTACIÓN DIRECTA -->
        <button class="btn-pdf" onclick="descargarPDF()">Exportar PDF 📄</button>
        <button class="btn-excel" onclick="descargarExcel()">Exportar Excel 📊</button>
      </div>
      <button class="back-btn" onclick="mostrarFormulario()">← Nueva Solicitud</button>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:23%">Cliente</th>
            <th style="width:25%">E-mail de Enlace</th>
            <th style="width:14%">Destino</th>
            <th style="width:13%">Salida</th>
            <th style="width:25%">Módulos de Comunicación</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
      <div id="emptyState" style="padding:25px; text-align:center; display:none; color:#64748b; font-weight:600;">No se encontraron registros activos en este criterio.</div>
    </div>
    <p style="margin-top:12px; font-size:12px; color:#64748b; font-weight: 600;" id="countLabel"></p>
  </div>

  <!-- SECCIÓN DE ENLACE DE INFORMACIÓN -->
  <div class="social-section">
    <h3>Canales Informativos Cliente - Empresa</h3>
    <p style="font-size:14px; opacity:0.85; margin-top:6px; max-width:650px; margin-left:auto; margin-right:auto;">
      Todos los reportes y correos emitidos desde esta consola funcionan como comprobantes de seguimiento recíproco para garantizar la veracidad de los itinerarios pactados.
    </p>
  </div>

  <div class="footer">
    <p>© 2026 Aura Viajes Corporativo · Conexión SMTP y Generación de Reportes Localizados</p>
  </div>
</div>

<!-- MODAL: EDICIÓN DE REGISTROS -->
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <p class="form-title">Modificar Ficha de Viaje</p>
    <div>
      <input id="eNombre" type="text" placeholder="Nombre completo" style="margin-bottom:12px;">
      <input id="eEmail" type="email" placeholder="Correo electrónico" style="margin-bottom:12px;">
      <select id="eDestino" style="margin-bottom:12px;">
        <option>Europa</option>
        <option>México Mágico</option>
        <option>Asia</option>
      </select>
      <input id="eFecha" type="date" style="margin-bottom:12px;">
      <textarea id="eNota" placeholder="Notas internas de la reservación"></textarea>
    </div>
    <div class="modal-actions">
      <button class="modal-cancel" onclick="cerrarModal()">Descartar</button>
      <button class="modal-save" onclick="guardarEdicion()">Guardar Cambios</button>
    </div>
  </div>
</div>

<!-- MODAL: CONTROL DE ENVÍOS DE CORREO ELECTRÓNICO (PROYECTO OFFLINE / RED LOCAL) -->
<div class="modal-overlay" id="emailModal">
  <div class="modal">
    <!-- Overlay de Procesamiento Cifrado SMTP -->
    <div class="sending-overlay" id="sendingOverlay">
      <div class="spinner"></div>
      <p style="font-weight:700; color:var(--dark-blue); font-size: 15px;">Despachando paquete a través de SMTP local...</p>
      <p style="font-size:12px; color:#64748b; margin-top:4px;">Procesando cola interna sin dependencia de red exterior.</p>
    </div>

    <p class="form-title" style="color:var(--warning)">✉️ Módulo de Redacción y Notificación Directa</p>
    <p style="font-size:12px; color:#64748b; margin-bottom:15px;">Establezca contacto formal inmediato. El sistema procesa la plantilla corporativa en el acto.</p>
    
    <div>
      <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:4px;">Asunto del Comunicado:</label>
      <input id="emAsunto" type="text" value="Notificación de Estatus e Itinerario Solicitado - Aura Viajes">
      
      <div class="email-preview-box">
        <div class="email-header-line">Para: <span id="emPara" style="font-weight:400; color:#0f172a;"></span></div>
        <div class="email-header-line">Remitente: <span style="font-weight:400; color:#0f172a;">operaciones@auraviajes.internal (Servidor Offline Autónomo)</span></div>
        <hr style="margin:12px 0; border:0; border-top:1px dashed #cbd5e1;">
        <p style="margin-bottom:8px;">Estimado/a <strong id="emCliente"></strong>,</p>
        <p style="margin-bottom:8px;">Nos ponemos en contacto directo con usted para informarle que su solicitud de viaje con destino a <strong id="emDestinoText"></strong> con fecha estimada <span id="emFechaText"></span> ha ingresado satisfactoriamente a nuestro sistema de auditoría comercial.</p>
        <p>Este mensaje sirve como acuse de información mutua. Nos comunicaremos para estructurar las tarifas finales.</p>
      </div>
    </div>

    <div class="modal-actions">
      <button class="modal-cancel" onclick="cerrarEmailModal()">Cerrar Asistente</button>
      <button class="modal-save" style="background:var(--warning);" onclick="procesarEnvioEmail()">Transmitir Correo ahora</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
  /* ====== CONTROL Y OPERACIÓN DEL CARRUSEL RESTABLECIDO ====== */
  let currentSlide = 0;
  const totalSlides = 3;
  const track = document.getElementById('carouselTrack');
  const indicators = document.querySelectorAll('.carousel-indicator');
  
  const slideData = [
    { title: "Planifica tu Próxima Aventura", desc: "Gestión integral de expediciones y canales directos de reporte corporativo." },
    { title: "Explora Destinos Exclusivos", desc: "Convenios internacionales con tarifas preferenciales para todos nuestros clientes." },
    { title: "Soporte Corporativo 24/7", desc: "Módulos automatizados de seguimiento, impresión de reportes y enlace continuo." }
  ];

  function updateCarousel() {
    track.style.transform = `translateX(-${currentSlide * 33.333}%)`;
    
    // Actualizar texto dinámico con efecto visual
    document.getElementById('carouselTitle').textContent = slideData[currentSlide].title;
    document.getElementById('carouselDesc').textContent = slideData[currentSlide].desc;
    
    // Actualizar indicadores
    indicators.forEach((ind, i) => {
      if(i === currentSlide) ind.classList.add('active');
      else ind.classList.remove('active');
    });
  }

  function moveCarousel(direction) {
    currentSlide += direction;
    if (currentSlide >= totalSlides) currentSlide = 0;
    if (currentSlide < 0) currentSlide = totalSlides - 1;
    updateCarousel();
  }

  function jumpToSlide(slideIndex) {
    currentSlide = slideIndex;
    updateCarousel();
  }

  // Rotación automática del carrusel cada 5 segundos
  let autoSlideInterval = setInterval(() => moveCarousel(1), 5000);

  // Detener rotación al interactuar
  document.querySelector('.hero-carousel').addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
  document.querySelector('.hero-carousel').addEventListener('mouseleave', () => {
    autoSlideInterval = setInterval(() => moveCarousel(1), 5000);
  });


  /* ====== LÓGICA Y CONTROL DE SOLICITUDES DE VIAJE ====== */
  let registros = [
    { id: 1, nombre: 'Ana García', email: 'ana.garcia@outlook.com', destino: 'Europa', fecha: '2026-06-15', nota: 'Plan familiar de verano' },
    { id: 2, nombre: 'Carlos López', email: 'carlos_lopez@gmail.com', destino: 'Asia', fecha: '2026-08-20', nota: 'Interés en Kyoto y Tokyo' },
    { id: 3, nombre: 'María Ruiz', email: 'mruiz@viajescorporativos.mx', destino: 'México Mágico', fecha: '2026-05-10', nota: 'Ruta gastronómica regional' },
  ];
  let nextId = 4;
  let editId = null;
  let targetEmailId = null;

  function badgeClass(d) {
    if (d === 'Europa') return 'badge-europa';
    if (d === 'Asia') return 'badge-asia';
    return 'badge-mexico';
  }

  function renderTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const tbody = document.getElementById('tableBody');
    const empty = document.getElementById('emptyState');
    const filtered = registros.filter(r => 
      r.nombre.toLowerCase().includes(q) || 
      r.email.toLowerCase().includes(q) || 
      r.destino.toLowerCase().includes(q)
    );
    
    tbody.innerHTML = '';
    if (filtered.length === 0) {
      empty.style.display = 'block';
    } else {
      empty.style.display = 'none';
      filtered.forEach(r => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${r.nombre}</strong></td>
          <td style="color:#475569; font-size:13px;">${r.email}</td>
          <td><span class="badge ${badgeClass(r.destino)}">${r.destino}</span></td>
          <td style="color:#64748b; font-size:13px;">${r.fecha || 'Pendiente'}</td>
          <td><div class="actions">
            <button class="act-btn email" onclick="abrirModuloEmail(${r.id})">✉️ Enviar Correo</button>
            <button class="act-btn edit" onclick="abrirEditar(${r.id})">Editar Ficha</button>
            <button class="act-btn del" onclick="eliminar(${r.id})">Eliminar</button>
          </div></td>`;
        tbody.appendChild(tr);
      });
    }
    document.getElementById('countLabel').textContent = `Total: ${filtered.length} registro(s) localizados en la auditoría interna.`;
  }

  function mostrarTabla() {
    document.getElementById('formSection').classList.remove('active');
    document.getElementById('tableSection').classList.add('active');
    renderTable();
  }

  function mostrarFormulario() {
    document.getElementById('tableSection').classList.remove('active');
    document.getElementById('formSection').classList.add('active');
  }

  function enviarSolicitud() {
    const nombre = document.getElementById('fNombre').value.trim();
    const email = document.getElementById('fEmail').value.trim();
    const destino = document.getElementById('fDestino').value;
    const fecha = document.getElementById('fFecha').value;
    const nota = document.getElementById('fNota').value.trim();

    if (!nombre || !email || !destino) { showToast('⚠️ Por favor complete Nombre, Correo y Destino'); return; }
    
    registros.push({ id: nextId++, nombre, email, destino, fecha, nota });
    ["fNombre", "fEmail", "fDestino", "fFecha", "fNota"].forEach(id => document.getElementById(id).value = "");
    
    showToast('🚀 Solicitud guardada en la base de datos.');
    setTimeout(mostrarTabla, 500);
  }

  function eliminar(id) {
    if(confirm('¿Está seguro de remover permanentemente este registro del panel informativo?')) {
      registros = registros.filter(r => r.id !== id);
      renderTable();
      showToast('Registro eliminado satisfactoriamente.');
    }
  }

  /* ====== CONTROL DEL MODAL DE EDICIÓN ====== */
  function abrirEditar(id) {
    const r = registros.find(x => x.id === id);
    if (!r) return;
    editId = id;
    document.getElementById('eNombre').value = r.nombre;
    document.getElementById('eEmail').value = r.email;
    document.getElementById('eDestino').value = r.destino;
    document.getElementById('eFecha').value = r.fecha;
    document.getElementById('eNota').value = r.nota;
    document.getElementById('editModal').classList.add('open');
  }

  function cerrarModal() {
    document.getElementById('editModal').classList.remove('open');
    editId = null;
  }

  function guardarEdicion() {
    const idx = registros.findIndex(r => r.id === editId);
    if (idx !== -1) {
      registros[idx].nombre = document.getElementById('eNombre').value.trim();
      registros[idx].email = document.getElementById('eEmail').value.trim();
      registros[idx].destino = document.getElementById('eDestino').value;
      registros[idx].fecha = document.getElementById('eFecha').value;
      registros[idx].nota = document.getElementById('eNota').value.trim();
      cerrarModal();
      renderTable();
      showToast('✅ Ficha de registro actualizada.');
    }
  }

  /* ====== MÓDULO DE ENVÍO DE CORREO ELECTRÓNICO (PROYECTO FUERA DE RED / LOCAL) ====== */
  function abrirModuloEmail(id) {
    const r = registros.find(x => x.id === id);
    if (!r) return;
    targetEmailId = id;
    
    document.getElementById('emPara').textContent = `${r.nombre} <${r.email}>`;
    document.getElementById('emCliente').textContent = r.nombre;
    document.getElementById('emDestinoText').textContent = r.destino;
    document.getElementById('emFechaText').textContent = r.fecha || '(Fecha no estipulada)';
    
    document.getElementById('sendingOverlay').style.display = 'none';
    document.getElementById('emailModal').classList.add('open');
  }

  function cerrarEmailModal() {
    document.getElementById('emailModal').classList.remove('open');
    targetEmailId = null;
  }

  function procesarEnvioEmail() {
    const overlay = document.getElementById('sendingOverlay');
    overlay.style.display = 'flex'; // Despliega animación de carga simulada de red

    // Simulación de respuesta de socket SMTP interno (2.2 segundos)
    setTimeout(() => {
      overlay.style.display = 'none';
      cerrarEmailModal();
      
      const r = registros.find(x => x.id === targetEmailId);
      alert(`[AURA SMTP MODULE] Mensaje despachado con éxito.

Para: ${r.email}
Asunto: ${document.getElementById('emAsunto').value}
Servidor: Local Loopback Encrypted

El correo electrónico ha quedado archivado en la cola del spooler de salida.`);
      showToast('✉️ Correo enviado al destinatario.');
    }, 2200);
  }

  /* ====== EXPORTACIÓN A EXCEL (MEDIO INFORMATIVO CLIENTE-EMPRESA) ====== */
  function descargarExcel() {
    if (registros.length === 0) { showToast('No existen registros para exportar.'); return; }
    
    // Mapeo estructurado para un entendimiento profesional entre cliente y corporativo
    const estructuraInformes = registros.map(r => ({
      'Folio Interno': r.id,
      'Nombre del Pasajero': r.nombre,
      'Correo de Vinculación': r.email,
      'Destino Solicitado': r.destino,
      'Fecha Programada': r.fecha || 'Sin definir',
      'Observaciones del Viaje': r.nota || 'Sin comentarios adicionales'
    }));

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(estructuraInformes);
    
    XLSX.utils.book_append_sheet(wb, ws, "Control de Solicitudes");
    XLSX.writeFile(wb, "Reporte_Soporte_AuraViajes.xlsx");
    showToast('📊 Reporte de Excel generado.');
  }

  /* ====== EXPORTACIÓN A PDF CON DISEÑO EMPRESARIAL INTEGRADO ====== */
  function descargarPDF() {
    if (registros.length === 0) { showToast('No existen registros para exportar.'); return; }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Banner Superior - Identidad Institucional
    doc.setFillColor(4, 44, 83); 
    doc.rect(0, 0, 210, 36, 'F');
    
    doc.setTextColor(255, 255, 255);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(22);
    doc.text("AURA VIAJES S.A.", 15, 22);
    
    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.text("Canal Corporativo Informativo Local", 148, 22);

    // Encabezado del Documento de Información Mutua
    doc.setTextColor(30, 41, 59);
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("INFORME DE CONTROL Y SEGUIMIENTO DE CLIENTES", 15, 48);
    
    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.setTextColor(100, 116, 139);
    doc.text(`Fecha de Emisión: ${new Date().toLocaleDateString('es-MX')}`, 154, 48);

    let currentY = 56;
    
    // Configuración de Tabla
    doc.setFillColor(24, 95, 165); 
    doc.rect(15, currentY, 180, 8, 'F');
    
    doc.setTextColor(255, 255, 255);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(9);
    doc.text("Titular del Trámite", 18, currentY + 5.5);
    doc.text("E-mail de Contacto", 68, currentY + 5.5);
    doc.text("Destino", 136, currentY + 5.5);
    doc.text("Fecha Salida", 168, currentY + 5.5);
    
    currentY += 8;
    doc.setFont("helvetica", "normal");
    doc.setTextColor(30, 41, 59);

    registros.forEach((r, index) => {
      // Filas intercaladas para legibilidad óptima
      if (index % 2 === 0) {
        doc.setFillColor(241, 245, 249);
        doc.rect(15, currentY, 180, 9, 'F');
      }

      doc.setDrawColor(226, 232, 240);
      doc.line(15, currentY + 9, 195, currentY + 9);

      doc.text(r.nombre.substring(0, 25), 18, currentY + 6);
      doc.text(r.email.substring(0, 32), 68, currentY + 6);
      doc.text(r.destino, 136, currentY + 6);
      doc.text(r.fecha || 'Pendiente', 168, currentY + 6);
      
      currentY += 9;
      if (currentY > 265) { doc.addPage(); currentY = 20; }
    });

    // Pie legal e informativo de vinculación comercial
    doc.setFontSize(8);
    doc.setTextColor(148, 163, 184);
    doc.text("Este documento es un medio oficial de comunicación e información entre el cliente y Aura Viajes.", 15, 286);

    doc.save("Reporte_Informativo_AuraViajes.pdf");
    showToast('📄 Reporte PDF descargado con éxito.');
  }

  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 2500);
  }
</script>

</body>
</html>