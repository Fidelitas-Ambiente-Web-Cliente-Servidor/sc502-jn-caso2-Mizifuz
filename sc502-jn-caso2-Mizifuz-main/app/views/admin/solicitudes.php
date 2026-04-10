<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Talleres | Revisión administrativa</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
</head>
<body class="app-body admin-theme">
    <header class="topbar">
        <div class="brand-block">
            <span class="brand-mini">Administrador</span>
            <h1>Centro de solicitudes</h1>
        </div>
        <div class="topbar-actions">
            <nav class="menu-inline">
                <a class="menu-link" href="index.php?page=talleres">Oferta</a>
                <a class="menu-link active" href="index.php?page=admin">Solicitudes</a>
            </nav>
            <div class="user-chip admin-chip">
                <span class="user-label">Administrador</span>
                <strong><?= htmlspecialchars($_SESSION['user'] ?? 'Administrador') ?></strong>
            </div>
            <button id="btnLogout" class="btn btn-danger">Salir</button>
        </div>
    </header>

    <main class="dashboard-wrap">
        <section class="page-intro admin-intro">
            <div>
                <span class="eyebrow">Panel administrativo</span>
                <h2>Solicitudes pendientes por revisar</h2>
                <p>Aprueba o rechaza sin recargar. Al aprobar, el cupo del taller se vuelve a validar antes de actualizarse.</p>
            </div>
            <div class="mini-stats">
                <article>
                    <span>Pendientes</span>
                    <strong id="statPendientes">0</strong>
                </article>
                <article>
                    <span>Última carga</span>
                    <strong id="statHora">--:--</strong>
                </article>
            </div>
        </section>

        <div id="mensaje" class="mensaje-box floating-message" style="display:none;"></div>

        <section class="content-card">
            <div class="section-head">
                <div>
                    <h3>Bandeja de revisión</h3>
                    <p>Listado de solicitudes con datos del taller y del solicitante.</p>
                </div>
                <span class="section-tag section-tag-warn">Decisión requerida</span>
            </div>

            <div class="table-container modern-table">
                <table id="tabla-solicitudes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Taller</th>
                            <th>Solicitante</th>
                            <th>Usuario ID</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="solicitudes-body">
                        <tr>
                            <td colspan="7" class="loader">Cargando solicitudes pendientes...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="public/js/solicitud.js"></script>
</body>
</html>
