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

    function renderEstado(taller) {
        if (Number(taller.ya_solicitado) === 1) {
            return '<span class="status-pill status-warn">Ya solicitado</span>';
        }
        return '<span class="status-pill status-ok">Disponible</span>';
    }

    function renderBoton(taller) {
        if (Number(taller.ya_solicitado) === 1) {
            return '<button class="btn btn-muted" disabled>Registrado</button>';
        }
        return `<button class="btn btn-success btnSolicitar" data-id="${taller.id}">Solicitar</button>`;
    }

    function actualizarStats(data) {
        const total = data ? data.length : 0;
        const solicitados = data ? data.filter(taller => Number(taller.ya_solicitado) === 1).length : 0;
        $("#statDisponibles").text(total);
        $("#statSolicitados").text(solicitados);
    }

    function cargarTalleres() {
        $.ajax({
            url: "index.php?option=talleres_json",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let html = "";
                actualizarStats(data);

                if (!data || data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="7" class="loader">No hay talleres con cupo disponible en este momento.</td>
                        </tr>
                    `;
                } else {
                    data.forEach(function (taller) {
                        html += `
                            <tr>
                                <td>#${taller.id}</td>
                                <td>
                                    <strong>${taller.nombre}</strong>
                                </td>
                                <td>${taller.descripcion ?? "Sin descripción"}</td>
                                <td>${taller.cupo_maximo}</td>
                                <td>${taller.cupo_disponible}</td>
                                <td>${renderEstado(taller)}</td>
                                <td>${renderBoton(taller)}</td>
                            </tr>
                        `;
                    });
                }

                $("#talleres-body").html(html);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                $("#talleres-body").html(`
                    <tr>
                        <td colspan="7" class="loader">Error al cargar los talleres.</td>
                    </tr>
                `);
            }
        });
    }

    $(document).on("click", ".btnSolicitar", function () {
        const boton = $(this);
        const tallerId = boton.data("id");

        boton.prop("disabled", true).text("Enviando...");

        $.ajax({
            url: "index.php",
            type: "POST",
            dataType: "json",
            data: {
                option: "solicitar",
                taller_id: tallerId
            },
            success: function (response) {
                if (response.success) {
                    mostrarMensaje(response.message, "ok");
                } else {
                    mostrarMensaje(response.error, "error");
                }

                cargarTalleres();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                mostrarMensaje("Error al enviar la solicitud.", "error");
                boton.prop("disabled", false).text("Solicitar");
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

    cargarTalleres();
});
