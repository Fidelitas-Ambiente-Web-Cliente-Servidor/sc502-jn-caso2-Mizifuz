$(function () {
    const formLogin = $("#formLogin");
    const urlBase = "index.php";

    function mostrarMensaje(texto, tipo = "error") {
        $("#mensaje")
            .stop(true, true)
            .hide()
            .text(texto)
            .removeClass("mensaje-error mensaje-ok")
            .addClass(tipo === "ok" ? "mensaje-ok" : "mensaje-error")
            .fadeIn();
    }

    formLogin.on("submit", function (event) {
        event.preventDefault();

        const username = $("#username").val().trim();
        const password = $("#password").val().trim();
        const boton = $(this).find("button[type='submit']");

        if (username === "" || password === "") {
            mostrarMensaje("Completa usuario y contraseña para ingresar.");
            return;
        }

        boton.prop("disabled", true).text("Validando...");

        $.ajax({
            url: urlBase,
            type: "POST",
            dataType: "json",
            data: {
                username: username,
                password: password,
                option: "login"
            },
            success: function (data) {
                if (data.response === "00") {
                    mostrarMensaje(data.message, "ok");
                    setTimeout(function () {
                        window.location = data.rol === "admin"
                            ? "index.php?page=admin"
                            : "index.php?page=talleres";
                    }, 500);
                } else {
                    mostrarMensaje(data.message);
                }
            },
            error: function () {
                mostrarMensaje("No se pudo conectar con el servidor.");
            },
            complete: function () {
                boton.prop("disabled", false).text("Entrar al sistema");
            }
        });
    });
});
