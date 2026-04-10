<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Talleres | Oferta disponible</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
</head>
<body class="app-body">
    <header class="topbar">
        <div class="brand-block">
            <span class="brand-mini">Campus</span>
            <h1>Talleres académicos</h1>
        </div>
        <div class="topbar-actions">
            <nav class="menu-inline">
                <a class="menu-link active" href="index.php?page=talleres">Oferta</a>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <a class="menu-link" href="index.php?page=admin">Solicitudes</a>
                <?php endif; ?>
            </nav>
            <div class="user-chip">
                <span class="user-label">Sesión</span>
                <strong><?= htmlspecialchars($_SESSION['user'] ?? 'Usuario') ?></strong>
            </div>
            <button id="btnLogout" class="btn btn-danger">Salir</button>
        </div>
    </header>

    <main class="dashboard-wrap">
        <section class="page-intro page-intro-user">
            <div>
                <span class="eyebrow">Panel de usuario</span>
                <h2>Explora y solicita talleres</h2>
                <p>Solo verás talleres con cupos disponibles. Si ya pediste uno, el sistema lo marcará automáticamente.</p>
            </div>
            <div class="mini-stats">
                <article>
                    <span>Talleres visibles</span>
                    <strong id="statDisponibles">0</strong>
                </article>
                <article>
                    <span>Solicitados por ti</span>
                    <strong id="statSolicitados">0</strong>
                </article>
            </div>
        </section>

        <div id="mensaje" class="mensaje-box floating-message" style="display:none;"></div>

        <section class="content-card">
            <div class="section-head">
                <div>
                    <h3>Catálogo activo</h3>
                    <p>Actualización dinámica con AJAX.</p>
                </div>
                <span class="section-tag">Cupos en tiempo real</span>
            </div>

            <div class="table-container modern-table">
                <table id="tabla-talleres">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Taller</th>
                            <th>Descripción</th>
                            <th>Cupo máximo</th>
                            <th>Cupo disponible</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="talleres-body">
                        <tr>
                            <td colspan="7" class="loader">Cargando oferta disponible...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="public/js/taller.js"></script>
</body>
</html>
