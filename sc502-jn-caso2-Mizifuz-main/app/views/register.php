<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Talleres | Registro</title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/register.js"></script>
</head>
<body class="portal-screen">
    <section class="auth-shell reverse-shell">
        <div class="auth-card alt-card">
            <div class="auth-heading">
                <span class="eyebrow">Nuevo usuario</span>
                <h2>Crear cuenta</h2>
                <p>Regístrate para enviar solicitudes de inscripción a los talleres disponibles.</p>
            </div>

            <div id="mensaje" class="mensaje-box" style="display:none;"></div>

            <form id="formRegister" class="auth-form">
                <div class="field-row">
                    <label for="username">Usuario</label>
                    <input type="text" name="username" id="username" placeholder="Crea tu usuario">
                </div>

                <div class="field-row">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Crea tu contraseña">
                </div>

                <button type="submit" class="btn btn-main btn-block">Registrarme</button>
                <a href="index.php?page=login" class="btn btn-ghost btn-block">Ya tengo una cuenta</a>
            </form>
        </div>

        <div class="hero-panel secondary-hero">
            <div class="hero-badge">Registro rápido</div>
            <h1>Empieza a solicitar talleres</h1>
            <p>
                El sistema muestra únicamente talleres con cupo y evita solicitudes duplicadas por usuario.
            </p>
            <div class="hero-points compact-points">
                <article>
                    <strong>Roles</strong>
                    <span>Usuario y administrador</span>
                </article>
                <article>
                    <strong>Seguridad</strong>
                    <span>Contraseñas cifradas</span>
                </article>
                <article>
                    <strong>JSON</strong>
                    <span>Comunicación clara con el servidor</span>
                </article>
            </div>
        </div>
    </section>
</body>
</html>
