jQuery(document).ready(function ($) {
    function obtenerParametroURL(nombre) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.has(nombre) ? urlParams.get(nombre).trim() : null;
    }

    let orderId = obtenerParametroURL("order_id");
    let codPed = obtenerParametroURL("cod_ped");
    if (orderId) {
        $("#order_id").val(orderId); // Asegurar que el campo oculto en Contact Form 7 también lo tenga
    } else {
        $("#uploadTitle").text("Subir archivos PDF"); // Si no hay order_id, mostrar un título genérico
    }

    if (codPed) {
        $("#uploadTitle").text("Subir archivos PDF - Pedido #" + codPed);
        $("#cod_ped").val(codPed); // Asegurar que el campo oculto en Contact Form 7 también lo tenga
    }

    $(".wpcf7-form").on("submit", function (event) {
        event.preventDefault();

        let form = this; // Obtener el formulario correcto
        let formData = new FormData(form); // ✅ FormData del formulario
        let archivosSeleccionados = [];

        // Recorrer todos los inputs de archivos
        $(".custom-file-input").each(function () {
            let fileInput = $(this)[0]; // Obtener el input como objeto DOM
            if (fileInput.files.length > 0) {
                archivosSeleccionados.push(fileInput.files[0]); // Tomar el primer archivo de cada input
                formData.append(fileInput.name, fileInput.files[0]); // Añadir al FormData
            }
        });

        // Validar que haya al menos 2 archivos subidos
        if (archivosSeleccionados.length < 2) {
            Swal.fire("Error", "Debes subir al menos 2 archivos PDF.", "error");
            return;
        }

        // Validar que haya un archivo "interior" y uno "cubierta"
        let tieneInterior = false, tieneCubierta = false;
        archivosSeleccionados.forEach((file) => {
            let nombreArchivo = file.name.toLowerCase();
            if (nombreArchivo.includes("interior")) tieneInterior = true;
            if (nombreArchivo.includes("cubierta")) tieneCubierta = true;
        });

        if (!tieneInterior || !tieneCubierta) {
            Swal.fire("Error", "Debes subir al menos un archivo con 'interior' y otro con 'cubierta'.", "error");
            return;
        }

        // Agregar el campo de acción para AJAX
        formData.append("action", "procesar_subida_archivos");

        // Enviar solicitud AJAX
        $.ajax({
            url: ajaxurl.ajax_url, // URL de admin-ajax.php
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                Swal.fire({
                    title: "Subiendo archivos...",
                    text: "Por favor, espera mientras se suben los archivos.",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (response) {
                Swal.close();

                if (response.success) {
                    Swal.fire("Éxito", "Archivos subidos correctamente. Redirigiendo a la página de pedidos...", "success");

                    form.reset();

                    // Redirigir después de 2 segundos
                    setTimeout(function () {
                        let pedidosURL = window.location.origin + "/mi-cuenta/orders/";

                        // Si hay order_id, redirigir a ese pedido específico
                        let orderId = obtenerParametroURL("order_id");
                        if (orderId) {
                            pedidosURL = window.location.origin + "/mi-cuenta/view-order/" + orderId + "/";
                        }

                        window.location.href = pedidosURL;
                    }, 2000);
                } else {
                    Swal.fire("Error", response.message || "Hubo un problema al subir los archivos.", "error");
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                Swal.fire("Error", "Hubo un problema al conectar con el servidor. Intenta de nuevo.", "error");
            }
        });
    });
});