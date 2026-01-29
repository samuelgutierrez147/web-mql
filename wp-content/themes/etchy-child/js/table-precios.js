jQuery(document).ready(function ($) {

    const $anchor = $('#generar-presupuesto');
    if (!$anchor.length) return;

    // Creamos contenedores debajo SIN tocar el contenido de #generar-presupuesto
    if (!$('#tabla-presupuesto').length) {
        $anchor.after(`
            <div id="tabla-presupuesto"></div>
            <div id="precio-actual" style="margin-top:12px;font-size:18px;font-weight:700;"></div>
        `);
    }

    let currentXhr = null;
    let loadingTimer = null;
    let debounceT = null;

    const money = (n) => new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(n);

    function clearLoading() {
        if (loadingTimer) {
            clearInterval(loadingTimer);
            loadingTimer = null;
        }
    }

    function setPrecioLoading() {
        clearLoading();
        let dots = 0;
        $('#precio-actual').html('Consultando precio<span id="precio-dots"></span>');
        loadingTimer = setInterval(() => {
            dots = (dots + 1) % 4;
            $('#precio-dots').text('.'.repeat(dots));
        }, 350);
    }

    function setPrecioError(msg) {
        clearLoading();
        $('#precio-actual').html(`<span style="font-weight:600;">${msg || 'No se pudo calcular el precio.'}</span>`);
    }

    // Convierte respuesta a array aunque venga como objeto con claves numéricas
    function normalizeItems(resp) {
        if (Array.isArray(resp)) return resp;
        if (resp && typeof resp === 'object') return Object.values(resp);
        return [];
    }

    function setPrecioFromTabla(items) {
        clearLoading();

        const rows = normalizeItems(items)
            .map(it => {
                const price = typeof it.price === 'string' ? parseFloat(it.price.replace(',', '.')) : parseFloat(it.price);
                const qty = typeof it.quantity === 'string' ? parseFloat(it.quantity.replace(',', '.')) : parseFloat(it.quantity);
                return { price, qty };
            })
            .filter(r => Number.isFinite(r.price) && Number.isFinite(r.qty) && r.qty > 0);

        if (!rows.length) {
            $('#precio-actual').html('');
            return;
        }

        const minTotal = Math.min(...rows.map(r => r.price));
        const minPPU = Math.min(...rows.map(r => r.price / r.qty));

        $('#precio-actual').html(`Desde ${money(minTotal)} <span style="font-weight:500;font-size:14px;">(PPU desde ${money(minPPU)}/Ud.)</span>`);
    }

    $("form.cart input, form.cart select").on("change", function () {

        clearTimeout(debounceT);
        debounceT = setTimeout(() => {

            let allFieldsFilled = true;

            $("form.cart .yith-wapo-addon:not(.hidden):visible input, form.cart .yith-wapo-addon:not(.hidden):visible select").each(function () {
                let element = $(this);
                let groupName = element.attr('name');
                let parent = element.closest('.yith-wapo-option');

                if (element.attr('type') === 'radio') {
                    let optionsSelected = $(`input[type="radio"][name="${groupName}"]`)
                        .closest('.yith-wapo-option')
                        .hasClass('selected');
                    if (!optionsSelected) allFieldsFilled = false;

                } else if (
                    (element.attr('required') && element.attr('type') === 'checkbox' && !parent.hasClass('selected')) ||
                    (element.attr('required') && (element.val() === '' || element.val() === 'Required'))
                ) {
                    allFieldsFilled = false;
                }
            });

            if (!allFieldsFilled) {
                if (currentXhr) currentXhr.abort();
                $("#tabla-presupuesto").html('');
                $("#precio-actual").html('');
                clearLoading();
                return;
            }

            var formdata = $('form.cart').serialize();

            if (currentXhr) currentXhr.abort();

            setPrecioLoading();

            Swal.fire({
                title: 'Consultando precio',
                text: 'Espere mientras se procesan los datos.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            currentXhr = $.ajax({
                url: ajaxData.ajaxUrl + "?action=tabla_precios_controller",
                type: 'POST',
                data: formdata,
                dataType: 'json',
                success: function (response) {
                    Swal.close();
                    currentXhr = null;

                    try {
                        let dataJson = response;

                        // --- detectar error_message en ambos formatos ---
                        let errorMessage = '';
                        if (dataJson && dataJson.error_message && dataJson.error_message[0]) {
                            errorMessage = dataJson.error_message[0];
                        } else if (dataJson && dataJson[0] && dataJson[0].error_message && dataJson[0].error_message[0]) {
                            errorMessage = dataJson[0].error_message[0];
                        }

                        if (errorMessage) {
                            setPrecioError('Hubo un problema al calcular el precio.');
                            $("#tabla-presupuesto").html('');
                            return;
                        }

                        const items = normalizeItems(dataJson)
                            .filter(it => it && typeof it === 'object' && it.quantity !== undefined && it.price !== undefined);

                        if (!items.length) {
                            clearLoading();
                            $("#tabla-presupuesto").html('');
                            $("#precio-actual").html('');
                            return;
                        }

                        // Pintar tabla
                        let tabla = `<table class="wp-list-table widefat striped">
                            <thead>
                                <tr>
                                    <th class="manage-column">Cantidad</th>
                                    <th class="manage-column">Precio Total</th>
                                    <th class="manage-column">Precio por Unidad (PPU)</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        items.forEach(function (item) {
                            const price = typeof item.price === 'string' ? parseFloat(item.price.replace(',', '.')) : parseFloat(item.price);
                            const qty = typeof item.quantity === 'string' ? parseFloat(item.quantity.replace(',', '.')) : parseFloat(item.quantity);

                            if (!Number.isFinite(price) || !Number.isFinite(qty) || qty <= 0) return;

                            const ppu = (price / qty).toFixed(2);
                            tabla += `<tr>
                                        <td>${item.quantity}</td>
                                        <td>${price.toFixed(2)} €</td>
                                        <td>${ppu} €/Ud.</td>
                                    </tr>`;
                        });

                        tabla += `</tbody></table>`;
                        $("#tabla-presupuesto").html(tabla);

                        // Pintar precio (resumen)
                        setPrecioFromTabla(items);

                        // Activar compra
                        $('button[name="add-to-cart"]').prop('disabled', false).css({
                            'opacity': '1',
                            'pointer-events': 'auto'
                        });

                    } catch (e) {
                        console.error('Error procesando respuesta:', e, response);
                        setPrecioError('Respuesta inválida al calcular el precio.');
                        $("#tabla-presupuesto").html('');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    currentXhr = null;

                    if (status === 'abort') return;

                    console.error("Error en la solicitud AJAX:", status, error, xhr && xhr.responseText);
                    setPrecioError('Hubo un problema al procesar la solicitud.');
                }
            });

        }, 250);
    });

});

function obtenerCamposRequeridosIncompletos(formulario) {
    var camposIncompletos = [];

    // Buscar elementos input, select y textarea que tengan el atributo required
    jQuery(formulario).find('input[required], select[required], textarea[required]').each(function() {
        var $campo = jQuery(this);

        // Verificar si el campo no está lleno o seleccionado
        if (($campo.is('input[type="checkbox"]') || $campo.is('input[type="radio"]')) && !$campo.is(':checked')) {
            // Para checkbox o radio, verificar si no está seleccionado
            camposIncompletos.push($campo.attr('name') || $campo.attr('id'));
        } else if ($campo.val() === null || $campo.val().trim() === "") {
            // Para otros elementos, verificar si el valor es vacío
            camposIncompletos.push($campo.attr('name') || $campo.attr('id'));
        }
    });

    return camposIncompletos;
}