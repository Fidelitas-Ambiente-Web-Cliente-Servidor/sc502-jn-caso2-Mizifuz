$(function () {
    const formRegister = $("#formRegister");
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

    formRegister.on("submit", function (event) {
        event.preventDefault();

        const username = $("#username").val().trim();
        const password = $("#password").val().trim();
        const boton = $(this).find("button[type='submit']");

        if (username === "" || password === "") {
            mostrarMensaje("Completa todos los campos para registrarte.");
            return;
        }

        boton.prop("disabled", true).text("Registrando...");

        $.ajax({
            url: urlBase,
            type: "POST",
            dataType: "json",
            data: {
                username: username,
                password: password,
                option: "register"
            },
            success: function (response) {
                if (response.response === "00") {
                    mostrarMensaje("Cuenta creada correctamente. Serás redirigido al acceso.", "ok");
                    setTimeout(function () {
                        window.location.href = "index.php?page=login";
                    }, 1200);
                } else {
                    mostrarMensaje(response.message);
                }
            },
            error: function () {
                mostrarMensaje("No fue posible completar el registro.");
            },
            complete: function () {
                boton.prop("disabled", false).text("Registrarme");
            }
        });
    });
});
