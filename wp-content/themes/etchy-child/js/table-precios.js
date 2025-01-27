jQuery(document).ready(function ($) {
    //DESACTIVAMOS Y ATENUAMOS BOTÓN COMPRA
    /*$('button[name="add-to-cart"]').prop('disabled', true).css({
        'opacity': '0.5',
        'pointer-events': 'none'
    });*/
    $("form.cart input, form.cart select").on("change", function () {
        let allFieldsFilled = true;

        // Verificamos todos los campos de formulario (inputs, selects, y textareas)
        $("form.cart .yith-wapo-addon:not(.hidden):visible input, form.cart .yith-wapo-addon:not(.hidden):visible select").each(function () {
            let element = $(this);
            let groupName = element.attr('name');
            let parent = element.closest('.yith-wapo-option');

            if (element.attr('type') === 'radio') {
                let optionsSelected = $(`input[type="radio"][name="${groupName}"]`).closest('.yith-wapo-option').hasClass('selected');
                if (!optionsSelected){
                    allFieldsFilled = false;
                }
            } else if (
                (element.attr('required') && element.attr('type') === 'checkbox' && !parent.hasClass('selected')) ||
                (element.attr('required') && (element.val() === '' || element.val() === 'Required'))
            ) {
                allFieldsFilled = false;
            }
        });

        if (!allFieldsFilled) {
            $("#generar-presupuesto").html('');
            return;
        }

        var formdata = $('form.cart').serialize();

        Swal.fire({
            title: 'Consultando precio',
            text: 'Espere mientras se procesan los datos.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: ajaxData.ajaxUrl + "?action=tabla_precios_controller",
            type: 'POST',
            data: formdata,
            dataType: 'json',
            success: function (response) {
                Swal.close();
                var dataJson = response;
                var errorMessage = '';

                if (dataJson && 'error_message' in dataJson)
                    errorMessage = dataJson.error_message[0];

                if (dataJson[0] !== undefined && dataJson[0].error_message !== undefined && dataJson[0].error_message[0] !== undefined)
                    errorMessage = dataJson[0].error_message[0];

                if (errorMessage !== '') {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al procesar la solicitud.',
                        icon: 'error'
                    });
                    console.log('Error: '+errorMessage);
                    $("#generar-presupuesto").html('');
                    return;
                }

                var tabla = `<table class="wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <th class="manage-column">Cantidad</th>
                            <th class="manage-column">Precio Total</th>
                            <th class="manage-column">Precio por Unidad (PPU)</th>
                        </tr>
                    </thead>
                    <tbody>`;
                dataJson.forEach(function(item) {
                    var ppu = (item.price / item.quantity).toFixed(2);
                    tabla += `<tr>
                                <td>${item.quantity}</td>
                                <td>${item.price} €</td>
                                <td>${ppu} €/Ud.</td>
                            </tr>`;
                });

                tabla += `</tbody></table>`;
                $("#generar-presupuesto").html(tabla);
                $('button[name="add-to-cart"]').prop('disabled', false).css({
                    'opacity': '1',
                    'pointer-events': 'auto'
                });
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al procesar la solicitud.',
                    icon: 'error'
                });
                console.error("Error en la solicitud AJAX:", error);
                //$("#generar-presupuesto").html('');
            }
        });
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