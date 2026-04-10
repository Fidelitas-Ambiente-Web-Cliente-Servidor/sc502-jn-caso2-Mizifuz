$(function () {
    function mostrarMensaje(texto, tipo = "ok") {
        $("#mensaje")
            .stop(true, true)
            .hide()
            .text(texto)
            .removeClass("mensaje-error mensaje-ok")
            .addClass(tipo === "ok" ? "mensaje-ok" : "mensaje-error")
            .fadeIn();

        setTimeout(() => {
            $("#mensaje").fadeOut();
        }, 3200);
    }

    function horaActual() {
        const ahora = new Date();
        return ahora.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    }

    function actualizarStats(data) {
        $("#statPendientes").text(data ? data.length : 0);
        $("#statHora").text(horaActual());
    }

    function cargarSolicitudes() {
        $.ajax({
            url: "index.php?option=solicitudes_json",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let html = "";
                actualizarStats(data);

                if (!data || data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="7" class="loader">No hay solicitudes pendientes por revisar.</td>
                        </tr>
                    `;
                } else {
                    data.forEach(function (solicitud) {
                        html += `
                            <tr>
                                <td>#${solicitud.id}</td>
                                <td>${solicitud.taller_nombre}</td>
                                <td>${solicitud.username}</td>
                                <td>${solicitud.usuario_id}</td>
                                <td>${solicitud.fecha_solicitud}</td>
                                <td><span class="status-pill status-pending">Pendiente</span></td>
                                <td>
                                    <div class="action-stack">
                                        <button class="btn btn-success btn-aprobar" data-id="${solicitud.id}">Aprobar</button>
                                        <button class="btn btn-danger-soft btn-rechazar" data-id="${solicitud.id}">Rechazar</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                $("#solicitudes-body").html(html);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                $("#solicitudes-body").html(`
                    <tr>
                        <td colspan="7" class="loader">Error al cargar las solicitudes.</td>
                    </tr>
                `);
            }
        });
    }

    $(document).on("click", ".btn-aprobar", function () {
        if (!confirm("¿Confirmas la aprobación de esta solicitud?")) return;

        const boton = $(this);
        const idSolicitud = boton.data("id");
        boton.prop("disabled", true).text("Aprobando...");

        $.ajax({
            url: "index.php",
            type: "POST",
            dataType: "json",
            data: {
                option: "aprobar",
                id_solicitud: idSolicitud
            },
            success: function (response) {
                if (response.success) {
                    mostrarMensaje(response.message, "ok");
                } else {
                    mostrarMensaje(response.error, "error");
                }
                cargarSolicitudes();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                mostrarMensaje("Error al aprobar la solicitud.", "error");
                boton.prop("disabled", false).text("Aprobar");
            }
        });
    });

    $(document).on("click", ".btn-rechazar", function () {
        if (!confirm("¿Confirmas el rechazo de esta solicitud?")) return;

        const boton = $(this);
        const idSolicitud = boton.data("id");
        boton.prop("disabled", true).text("Rechazando...");

        $.ajax({
            url: "index.php",
            type: "POST",
            dataType: "json",
            data: {
                option: "rechazar",
                id_solicitud: idSolicitud
            },
            success: function (response) {
                if (response.success) {
                    mostrarMensaje(response.message, "ok");
                } else {
                    mostrarMensaje(response.error, "error");
                }
                cargarSolicitudes();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                mostrarMensaje("Error al rechazar la solicitud.", "error");
                boton.prop("disabled", false).text("Rechazar");
            }
        });
    });

    $("#btnLogout").on("click", function () {
        $.ajax({
            url: "index.php",
            type: "POST",
            dataType: "json",
            data: { option: "logout" },
            success: function () {
                window.location.href = "index.php?page=login";
            }
        });
    });

    cargarSolicitudes();
});
