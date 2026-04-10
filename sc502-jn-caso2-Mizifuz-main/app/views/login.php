<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Talleres | Acceso</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/auth.js"></script>
</head>
<body class="portal-screen">
    <section class="auth-shell split-shell">
        <div class="hero-panel">
            <div class="hero-badge">Ambiente Web Cliente Servidor</div>
            <h1>Campus de talleres académicos</h1>
            <p>
                Solicita tu inscripción en línea y recibe respuesta del administrador sin recargar la página.
            </p>
            <div class="hero-points">
                <article>
                    <strong>2 pasos</strong>
                    <span>Solicitud y aprobación</span>
                </article>
                <article>
                    <strong>AJAX</strong>
                    <span>Todo dinámico y en tiempo real</span>
                </article>
                <article>
                    <strong>MVC</strong>
                    <span>Estructura limpia y ordenada</span>
                </article>
            </div>
        </div>

        <div class="auth-card alt-card">
            <div class="auth-heading">
                <span class="eyebrow">Acceso al sistema</span>
                <h2>Bienvenido</h2>
                <p>Ingresa con tu usuario y contraseña para continuar.</p>
            </div>

            <div id="mensaje" class="mensaje-box" style="display:none;"></div>

            <form id="formLogin" class="auth-form">
                <div class="field-row">
                    <label for="username">Usuario</label>
                    <input type="text" name="username" id="username" placeholder="Ejemplo: usuario1">
                </div>

                <div class="field-row">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña">
                </div>

                <button type="submit" class="btn btn-main btn-block">Entrar al sistema</button>
                <a href="index.php?page=registro" class="btn btn-ghost btn-block">Crear una cuenta</a>
            </form>
        </div>
    </section>
</body>
</html>
