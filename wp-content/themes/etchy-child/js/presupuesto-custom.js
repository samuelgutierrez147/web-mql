const encuadernaciones = [
    'RUSTICA_PUR',
    'GRAPA',
    'ESPIRAL',
    'WIREO',
    'TAPA_DURA_PUR',
    'TAPA_DURA_ESPIRAL',
    'TAPA_DURA_WIRE_O',
    'RUSTICA_COSIDA',
    'TAPA_DURA_COSI'
];

var configMultiplos = [
    ['RUSTICA_PUR', 16, 2, 1000],
    ['GRAPA', 8, 4, 64],
    ['ESPIRAL', 16, 2, 800],
    ['WIREO', 16, 2, 600],
    ['TAPA_DURA_PUR', 32, 2, 1000],
    ['TAPA_DURA_ESPIRAL', 16, 2, 670],
    ['TAPA_DURA_WIRE_O', 16, 2, 500],
    ['TAPA_DURA_COSI', 32, 2, 1000],
    ['RUSTICA_COSIDA', 16, 2, 1000]
];

jQuery(function ($) {

    // ======================================================
    // Helpers
    // ======================================================

    function getEncuFromPathName(encuadernacionSlug) {
        var slugSinEncuadernacion = String(encuadernacionSlug || "")
            .replace("encuadernacion-", "")
            .replace(/-/g, "_")
            .toUpperCase();

        let encuadernacion = (typeof encuadernaciones !== "undefined" && Array.isArray(encuadernaciones))
            ? encuadernaciones.find(function (item) {
                return String(item).toUpperCase().includes(slugSinEncuadernacion);
            })
            : slugSinEncuadernacion;

        // Mantengo tu validación
        if (typeof validacionPaginas === "function") {
            validacionPaginas(slugSinEncuadernacion);
        }

        if (slugSinEncuadernacion === "TAPA_DURA_LOMO_REDONDO" || slugSinEncuadernacion === "TAPA_DURA_LOMO_CUADRADO") {
            encuadernacion = "TAPA_DURA_COSI";
        }

        return encuadernacion || slugSinEncuadernacion;
    }

    function applyGuardasRule(encuValue) {
        if (!encuValue || encuValue === "default") return;

        validacionPaginas(encuValue);

        const $check_guardas = $('input[type="checkbox"][value="Guardas"]');
        const $div_guardas = $check_guardas.closest('[id^="yith-wapo-addon"]');

        // Mostrar el addon Guardas
        $div_guardas.css("display", "");
        $div_guardas.find(".label_price span.required").remove();

        // TAPA_DURA_* => obligatorio
        if (String(encuValue).startsWith("TAPA_DURA_")) {
            $check_guardas.attr("required", "required");
            $check_guardas.removeAttr("disabled");
            $div_guardas.find(".label_price").append('<span class="required">*</span>');
        } else {
            $check_guardas.removeAttr("required");
            $check_guardas.attr("disabled", "disabled");
            $div_guardas.find(".label_price span.required").remove();
        }
    }

    function validacionPaginas(encu) {
        let input_paginas = jQuery('input[id$="e_paginas"]'),
            minPaginas = 0,
            multiploPaginas = 0,
            maxPaginas = 0;

        jQuery.each(configMultiplos, function (keyConfig, config) {
            if (config[0] === encu) {
                minPaginas = config[1];
                multiploPaginas = config[2];
                maxPaginas = config[3];
            }
        });

        input_paginas.each(function () {
            jQuery(this).attr("max", maxPaginas);
            jQuery(this).attr("min", minPaginas);
            jQuery(this).attr({"step": multiploPaginas});

            let div_input = jQuery(this).closest('.yith-wapo-addon');
            div_input.attr("data-numbers-max", maxPaginas);
            div_input.attr("data-numbers-min", minPaginas);
            div_input.attr("data-numbers-step", multiploPaginas);
        });
    }

    // ======================================================
    // INICIO lógica Encuadernación
    // ======================================================

    const pathname = window.location.pathname || "";
    const lastSegment = pathname.split("/").filter(Boolean).pop() || "";

    const $encuAddon = $('[data-id="e_encu"]'); // addon completo (sea label o select)

    if ($encuAddon.length) {
        if (lastSegment !== "personalizado") {

            const encu = getEncuFromPathName(lastSegment);

            // Ocultar elementos del producto
            const hidden_elements_product = ["Interior 2", "Sobrecubiertas", "Faja", "Marcapáginas", "Desplegable"];

            $.each(hidden_elements_product, function (_, value) {
                let $inputs = $('input[type="checkbox"][value="' + value + '"]');
                if (!$inputs.length) $inputs = $('input[type="radio"][value="' + value + '"]');

                if ($inputs.length) {
                    $inputs.closest('[id^="yith-wapo-addon"]').addClass("no-visible");
                }
            });

            // Detectar si es SELECT
            const $select = $encuAddon.find('select#e_encu, select[name="yith_wapo[][e_encu]"]');

            if ($select.length) {
                // SELECT
                $select.val(encu);

                // disparar varios por compatibilidad
                $select.trigger("change");
                $select.trigger("input");
                $select.triggerHandler("change");

                // si quieres “ocultar” el addon en no-personalizado:
                $encuAddon.addClass("no-visible");
            } else {
                // CHECKBOX/RADIO (label)
                let $input = $encuAddon.find('input[type="checkbox"][value="' + encu + '"]');
                if (!$input.length) $input = $encuAddon.find('input[type="radio"][value="' + encu + '"]');

                if ($input.length) {
                    $input.closest('[id^="yith-wapo-option"]').addClass("selected");
                    $input.trigger("click");
                }

                $encuAddon.addClass("no-visible");
            }

            // OCULTAR GUARDAS si corresponde
            if (encu === "RUSTICA_PUR" || encu === "RUSTICA_COSIDA") {
                const $check_guardas = $('input[type="checkbox"][value="Guardas"]');
                const $div_guardas = $check_guardas.closest('[id^="yith-wapo-addon"]');

                $div_guardas.addClass("no-visible");
                $check_guardas.removeAttr("required");
            }

        } else {
            // /personalizado

            // Inputs (checkbox/radio) del addon 7
            $(document).on('click change', 'input[id^="yith-wapo-7-"][type="checkbox"], input[id^="yith-wapo-7-"][type="radio"]', function () {
                applyGuardasRule(this.value);
            });

            // Select del addon 7
            $(document).on("change", 'select#e_encu, select[name="yith_wapo[][e_encu]"]', function () {
                applyGuardasRule(this.value);
            });
        }
    }

    // ======================================================
    // RESTO DE TU CÓDIGO (sin tocar)
    // ======================================================

    $("input[name*='e_tipo_impresion']").on("change", function () {
        var name = $(this).attr("name");
        var regex = /\[\d+e_tipo_impresion\]/;
        var match = name.match(regex);

        if (match) {
            var number = match[0].split("e_tipo_impresion")[0].slice(1);

            if (number) {
                var relatedInputs = $("input[name*='e_tipo_papel'][name*='" + number + "']");

                relatedInputs.each(function () {
                    $(this).prop("checked", false);
                    $(this).removeAttr("checked");
                    $(this).closest(".yith-wapo-option").removeClass("selected");
                });
            }
        }
    });

    $('[data-id="8e_encu_wr_color_otro"], [data-id="8e_encu_es_color_otro"]').css('display', 'none').find('input').prop('disabled', true);

    $('#8e_encu_wr_color, #8e_encu_es_color').on('change', function () {
        let selectedId = $(this).attr('id');
        let relatedField = $('[data-id="' + selectedId + '_otro"]');

        if ($(this).val().toLowerCase() === 'otro') {
            relatedField.show();
        } else {
            relatedField.css('display', 'none');
        }
    });

    $(document).on('input', 'input#titulo', function () {
        let addon = $(this).closest('.yith-wapo-addon');
        let title = addon.find('.wapo-addon-title');
        let option = addon.find('.yith-wapo-option');

        addon.find('.required-error').remove();
        title.removeClass('wapo-error');
        option.removeClass('required-color');

        if ($(this).val().trim() === '') {
            addon.append('<div class="required-error"><small class="required-message" style="color: red;">Esta opción es obligatoria.</small></div>');
            title.addClass('wapo-error');
            option.addClass('required-color');
        }
    });

    $('#isbn').on('change', function () {
        let isbn = $(this).val();
        let $messageContainer = $('#isbn-message');

        if ($messageContainer.length === 0) {
            $messageContainer = $('<div id="isbn-message" style="margin-top: 10px; font-weight: bold;"></div>');
            $(this).after($messageContainer);
        }

        if (isbn.trim() === '') {
            $messageContainer.text('El campo ISBN no puede estar vacío.').css('color', 'red');
        } else if (validateISBN13(isbn)) {
            $messageContainer.text('El ISBN-13 es válido.').css('color', 'green');
        } else {
            $messageContainer.text('El ISBN-13 no es válido.').css('color', 'red');
        }
    });

    $(document).on('change', '.yith-wapo-option-value[type="checkbox"]', function () {
        if (!this.checked) return;

        const id = this.id;
        const valor = this.value;

        const prefijo = id.split('_')[0];

        const mensajes = {
            '2e': {
                'Barniz': {
                    titulo: "BARNIZ UVI (RESERVA) - CUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el porcentaje de la reserva UVI está dentro del estándar (reserva del 25 %). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Estampado': {
                    titulo: "ESTAMPADO - CUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del estampado está dentro del estándar (70 x 70 mm). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Troquelado': {
                    titulo: "TROQUELADO - CUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del troquel y la dificultad del mismo está dentro del estándar. Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Golpe seco': {
                    titulo: "GOLPE SECO - CUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del cliché está dentro del estándar (50 x 50 mm). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                }
            },
            '4e': {
                'Barniz': {
                    titulo: "BARNIZ UVI (RESERVA) - SOBRECUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el porcentaje de la reserva UVI está dentro del estándar (reserva del 25 %). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Estampado': {
                    titulo: "ESTAMPADO - SOBRECUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del estampado está dentro del estándar (70 x 70 mm). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Troquelado': {
                    titulo: "TROQUELADO - SOBRECUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del troquel y la dificultad del mismo está dentro del estándar. Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                },
                'Golpe seco': {
                    titulo: "GOLPE SECO - SOBRECUBIERTA",
                    texto: "Su pedido queda pendiente de revisar si el tamaño del cliché está dentro del estándar (50 x 50 mm). Mandaremos un e-mail de confirmación una vez revisado por nuestro departamento de validación y así poder aceptar la oferta."
                }
            }
        };

        const grupo = mensajes[prefijo];
        const mensaje = grupo ? grupo[valor] : null;

        if (mensaje) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: `<strong>${mensaje.titulo}</strong>`,
                html: `<p>${mensaje.texto}</p>`,
                showConfirmButton: true,
                width: '800px'
            });
        }
    });

    function validateISBN13(isbn) {
        isbn = isbn.replace(/[\s-]/g, '');
        if (!/^\d{13}$/.test(isbn)) return false;

        let sum = 0;
        for (let i = 0; i < 12; i++) {
            let digit = parseInt(isbn.charAt(i));
            sum += (i % 2 === 0) ? digit : digit * 3;
        }
        let checksum = (10 - (sum % 10)) % 10;
        return checksum === parseInt(isbn.charAt(12));
    }

});

jQuery(function ($) {

    // Encuadernación REAL (debe seguir siendo LABEL para que la lógica condicional funcione)
    const $addon = $('[data-id="e_encu"].yith-wapo-addon-type-label');
    if (!$addon.length) return;

    const proxyId = 'e_encu_proxy_select';
    if ($('#' + proxyId).length) return; // evitar duplicados

    const $options = $addon.find('[id^="yith-wapo-option"]');
    if (!$options.length) return;

    // Crear SELECT proxy
    const $select = $('<select/>', { id: proxyId, class: 'yith-wapo-encu-proxy' });
    $select.append($('<option/>', { value: '', text: 'Seleccionar una opción' }));

    // Rellenar opciones leyendo el LABEL real
    $options.each(function () {
        const $opt = $(this);
        const $input = $opt.find('input.yith-wapo-option-value').first();
        if (!$input.length) return;

        const val = $input.val();
        const text = ($opt.find('.label_price label').first().text() || val).trim();

        const $o = $('<option/>', { value: val, text: text });

        // Si está seleccionado en label, reflejar en el select
        if ($input.is(':checked') || $opt.hasClass('selected')) {
            $o.prop('selected', true);
        }

        $select.append($o);
    });

    // Insertar el SELECT al principio del addon
    $addon.find('.options-container').first()
        .prepend($('<div class="encu-proxy-wrap"/>').append($select));

    // Ocultar visualmente las tarjetas del label, pero mantener inputs para la lógica
    $addon.addClass('encu-proxy-enabled');

    // Cuando cambie el select, “clickeamos” el input label equivalente (esto dispara YITH + lógica)
    function selectLabelValue(val) {
        if (!val) return;

        const $input = $addon.find('input.yith-wapo-option-value[value="' + val + '"]').first();
        if (!$input.length) return;

        // Evitar toggle raro si ya está seleccionado
        const $wrapOpt = $input.closest('[id^="yith-wapo-option"]');
        const alreadySelected = $input.is(':checked') || $wrapOpt.hasClass('selected');
        if (alreadySelected) return;

        $input.trigger('click'); // clave: esto es lo que hace que YITH dispare su lógica condicional
    }

    $select.on('change', function () {
        selectLabelValue(this.value);
    });

    // Si alguien clickea el label (por JS o por cualquier motivo), sincronizamos el select
    $addon.on('click change', 'input.yith-wapo-option-value', function () {
        const v = $(this).val();
        if (v) $select.val(v);
    });

});


/*jQuery(document).ready(function ($) {
    $('form.cart').on('submit', function (e) {
        // Prevenir el envío del formulario para realizar la validación
        e.preventDefault();

        // Seleccionar todos los campos 'required' del formulario
        var requiredFields = $(this).find('[required]');
        var emptyFields = [];

        // Iterar sobre los campos y verificar cuáles están vacíos
        requiredFields.each(function () {
            if (!$(this).val()) {
                emptyFields.push(this); // Añadir el campo vacío a la lista
            }
        });

        if (emptyFields.length > 0) {
            console.log('Los siguientes campos required están vacíos:');
            console.log(emptyFields); // Mostrar los elementos en la consola

            // Mostrar los nombres de los campos o sus IDs en la consola
            emptyFields.forEach(function (field) {
                console.log(`Campo vacío: ${field.name || field.id}`);
            });

            alert('Por favor, rellene todos los campos obligatorios.');
        } else {
            console.log('Todos los campos required están completos.');
            // Puedes eliminar el e.preventDefault() si los campos están completos
            e.target.submit();
        }
    });
});*/

/*
document.addEventListener('DOMContentLoaded', function () {
    // Selección de elementos del DOM
    const presupuestoInput = document.querySelector('#yith-wapo-980-0');
    const cantidadEntregaInput = document.querySelector('#yith-wapo-76-1');
    const entregaCheckbox = document.querySelector('#yith-wapo-75-1');
    const addToCartButton = document.querySelector('.single_add_to_cart_button');
    const selectMasEntregasCheckbox = document.querySelector('#yith-wapo-91-0');
    const yithWapo92Input = document.querySelector('#yith-wapo-92-2');
    const yithWapo93Input = document.querySelector('#yith-wapo-93-2');
    const yithWapo94Input = document.querySelector('#yith-wapo-94-2');
    const yithWapo95Input = document.querySelector('#yith-wapo-95-2');
    const tipoPruebaSelect = document.querySelector('#yith-wapo-3');
    const cantidadInputs = document.querySelectorAll('[id^="yith-wapo-980-"]');

    // Creación de elementos de error para los nuevos campos
    const nuevosCampos = [
        {input: yithWapo92Input, error: document.createElement('small')},
        {input: yithWapo93Input, error: document.createElement('small')},
        {input: yithWapo94Input, error: document.createElement('small')},
        {input: yithWapo95Input, error: document.createElement('small')},
        {input: cantidadEntregaInput, error: document.createElement('small')}
    ];

    // Configuración de los elementos de error
    nuevosCampos.forEach(({input, error}) => {
        if (input) {
            error.classList.add('yith-wapo-numbers-error-message');
            error.style.color = '#ed1c24';
            if (input.parentElement) {
                input.parentElement.appendChild(error);
            }
        }
    });

    // Array de campos visibles
    const camposVisibles = nuevosCampos.map(({input}) => input).filter(Boolean);

    // Creación del mensaje de error general
    const mensajeError = document.createElement('div');
    mensajeError.id = 'mensaje-error';
    mensajeError.style.color = '#ed1c24';
    mensajeError.style.display = 'none';
    document.body.appendChild(mensajeError);

    // Función para verificar si un elemento es visible
    function isVisible(element) {
        return element.offsetParent !== null;
    }

    // Función principal de validación de campos
    function validarCampos() {
        if (!presupuestoInput || !cantidadEntregaInput || !entregaCheckbox || !addToCartButton) {
            return; // Evitar errores si los elementos no están presentes
        }

        const cantidadPresupuesto = parseInt(presupuestoInput.value, 10) || 0;

        let totalCantidadEntrega = 0;
        let startValue = 0;

        if (tipoPruebaSelect && tipoPruebaSelect.value === '2') {
            startValue = 1;
        }

        totalCantidadEntrega += startValue;

        let totalCantidades = 0;
        let cantidadValida = true;
        let mensaje = '';

        cantidadInputs.forEach(input => {
            if (input.value.trim() !== '') {
                totalCantidades += parseInt(input.value, 10) || 0;
            }
        });

        let filledCantidadInputs = Array.from(cantidadInputs).filter(input => input.value.trim() !== '');
        if (filledCantidadInputs.length > 1) {
            if (cantidadEntregaInput.value.trim() !== '' && parseInt(cantidadEntregaInput.value, 10) !== totalCantidades) {
                cantidadValida = false;
                mensaje = `La cantidad de entrega debe ser igual a la suma de las cantidades: ${totalCantidades}.`;
                mostrarError(cantidadEntregaInput.parentElement.querySelector('.yith-wapo-numbers-error-message'), mensaje);
            }
        } else {
            ocultarError(cantidadEntregaInput.parentElement.querySelector('.yith-wapo-numbers-error-message'));
        }

        if (entregaCheckbox.checked && cantidadValida) {
            camposVisibles.forEach((input) => {
                if (isVisible(input) && input.value.trim() !== '') {
                    if (input.id.startsWith('yith-wapo-9') && !selectMasEntregasCheckbox.checked) {
                        return;
                    }
                    totalCantidadEntrega += parseInt(input.value, 10) || 0;
                }
            });

            let valid = true;

            if (totalCantidadEntrega !== cantidadPresupuesto) {
                mensaje = 'La suma de las cantidades de ejemplares no coincide con la cantidad del presupuesto.';

                const diferencia = cantidadPresupuesto - totalCantidadEntrega;

                camposVisibles.forEach((input) => {
                    const error = input.parentElement.querySelector('.yith-wapo-numbers-error-message');
                    if (error) {
                        if (input.value.trim() === '') {
                            if (input.id === 'yith-wapo-76-1') {
                                mostrarError(error, 'Debe ingresar una cantidad.');
                            } else {
                                ocultarError(error);
                            }
                        } else if (diferencia > 0) {
                            mostrarError(error, `Faltan ${Math.abs(diferencia)} copias para cumplir con el presupuesto.`);
                        } else if (diferencia < 0) {
                            mostrarError(error, `Sobran ${Math.abs(diferencia)} copias para cumplir con el presupuesto.`);
                        } else {
                            ocultarError(error);
                        }
                    }
                });


                mostrarError(mensajeError, mensaje);

                valid = false;
            } else {
                camposVisibles.forEach((input) => {
                    const error = input.parentElement.querySelector('.yith-wapo-numbers-error-message');
                    if (error) {
                        ocultarError(error);
                    }
                });


                ocultarError(mensajeError);
            }

            addToCartButton.disabled = !valid;
        } else {
            camposVisibles.forEach((input) => {
                const error = input.parentElement.querySelector('.yith-wapo-numbers-error-message');
                if (error) {
                    ocultarError(error);
                }
            });

            ocultarError(mensajeError);
        }

        addToCartButton.disabled = !cantidadValida;
    }

    // Funciones auxiliares para mostrar y ocultar errores
    function mostrarError(element, mensaje) {
        if (element) {
            element.style.display = 'block';
            element.textContent = mensaje;
        }
    }

    function ocultarError(element) {
        if (element) {
            element.style.display = 'none';
            element.textContent = '';
        }
    }

    // Asignación de eventos a los elementos
    if (presupuestoInput) presupuestoInput.addEventListener('input', validarCampos);
    if (cantidadEntregaInput) cantidadEntregaInput.addEventListener('input', validarCampos);
    if (entregaCheckbox) entregaCheckbox.addEventListener('change', validarCampos);
    nuevosCampos.forEach(({input}) => {
        if (input) input.addEventListener('input', validarCampos);
    });
    if (selectMasEntregasCheckbox) selectMasEntregasCheckbox.addEventListener('change', function () {
        if (!this.checked) {
            if (yithWapo92Input) yithWapo92Input.value = '';
            if (yithWapo93Input) yithWapo93Input.value = '';
            if (yithWapo94Input) yithWapo94Input.value = '';
            if (yithWapo95Input) yithWapo95Input.value = '';
            validarCampos();
        }
    });
    if (tipoPruebaSelect) tipoPruebaSelect.addEventListener('change', validarCampos);
    cantidadInputs.forEach(input => {
        if (input) input.addEventListener('input', validarCampos);
    });

    // Inicialización del estado del botón
    validarCampos();
});


function setupInput(id) {
    const inputElement = document.getElementById(id);
    const minValue = parseInt(inputElement.getAttribute('min'));
    const defaultValue = minValue;

    // Prevenir la eliminación completa del valor
    inputElement.addEventListener('keydown', function (event) {
        if (event.key === 'Delete' || event.key === 'Backspace') {
            return; // Permite borrar el valor actual
        }
    });

    // Validar y ajustar el valor al perder el foco
    inputElement.addEventListener('blur', function () {
        const currentValue = this.value;

        if (currentValue === '') {
            this.value = minValue; // Establece el valor mínimo si está vacío
            return;
        }

        const numericValue = parseInt(currentValue);

        if (isNaN(numericValue) || numericValue < minValue) {
            this.value = minValue; // Establece el valor mínimo si es inválido
        }
    });
}

// Configurar inputs específicos
setupInput('yith-wapo-975-0');
setupInput('yith-wapo-975-1');
setupInput('yith-wapo-978-0');
setupInput('yith-wapo-977-0');


// Este script elimina los placeholders de los campos de entrada numéricos dentro del addon 980
document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar todos los inputs de tipo número con placeholder "0" dentro del addon 980
    const inputElements = document.querySelectorAll('#yith-wapo-addon-980 input[type="number"][placeholder="0"]');

    // Iterar sobre cada input y eliminar su placeholder
    inputElements.forEach(input => {
        input.placeholder = '';
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Definir elementos y sus correspondientes IDs de popup
    var elements = [
        {id: 'yith-wapo-73-0', popupID: 2299},
        {id: 'yith-wapo-73-1', popupID: 2300},
        {id: 'yith-wapo-73-2', popupID: 2301},
        // ... Añadir más elementos según sea necesario
    ];

    elements.forEach(function (element) {
        var checkboxElement = document.getElementById(element.id);
        if (!checkboxElement) return;

        // Encontrar el elemento label-container-display
        var labelContainerDisplayElement = checkboxElement.nextElementSibling;

        // Crear y añadir el icono de ojo
        var eyeIcon = document.createElement('i');
        eyeIcon.className = 'fas fa-eye eye-icon';
        eyeIcon.style.cursor = 'pointer';
        labelContainerDisplayElement.appendChild(eyeIcon);

        // Agregar evento click al icono de ojo para mostrar el popup
        eyeIcon.addEventListener('click', function () {
            elementorProFrontend.modules.popup.showPopup({id: element.popupID});
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form.cart');
    var container = document.getElementById('yith-wapo-container');
    var errorMessageShown = false;

    if (form && container) {
        // Crear el elemento de mensaje de error
        var errorMessage = document.createElement('div');
        errorMessage.id = 'error-message';
        errorMessage.style.display = 'none';
        errorMessage.style.color = '#AF2323';
        errorMessage.style.textAlign = 'center';
        errorMessage.style.width = '100%';
        errorMessage.style.paddingTop = '10px';
        errorMessage.style.paddingBottom = '10px';
        errorMessage.style.marginBottom = '50px';
        errorMessage.style.borderRadius = '10px';
        errorMessage.style.fontFamily = '"Red Hat Display", sans-serif';
        errorMessage.style.fontSize = '20px';
        errorMessage.style.fontWeight = '700';
        errorMessage.style.textTransform = 'uppercase';
        errorMessage.style.lineHeight = '31px';
        errorMessage.style.border = '2px solid #AF2323';
        errorMessage.textContent = 'Por favor, rellena todos los campos obligatorios.';

        // Insertar el mensaje de error al principio del contenedor
        container.insertBefore(errorMessage, container.firstChild);

        var addToCartButton = document.querySelector('button.single_add_to_cart_button');
        if (addToCartButton) {
            addToCartButton.addEventListener('click', function (event) {
                var fields = form.querySelectorAll('[required]');
                var isValid = true;

                // Verificar si todos los campos requeridos están llenos
                fields.forEach(function (field) {
                    if (!field.value.trim()) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    // Mostrar mensaje de error si hay campos vacíos
                    errorMessageShown = true;
                    errorMessage.style.display = 'block';
                } else {
                    // Ocultar mensaje de error y enviar el formulario si todo está correcto
                    errorMessageShown = false;
                    errorMessage.style.display = 'none';
                    form.submit();
                }
            });
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Función para la página con ID 15
    if (document.body.classList.contains("page-id-15")) {
        window.onload = function () {
            var h3Element = document.querySelector('#customer_details > div.col-2 > div.woocommerce-additional-fields > h3');
            var spanElement = document.createElement('span');
            spanElement.textContent = h3Element.textContent;
            h3Element.parentNode.replaceChild(spanElement, h3Element);
        }
    }

    // Función para productos específicos
    const productIds = ["1658", "2171", "2182", "2183", "2184", "2185", "2186", "2187", "2188", "2189", "2190"];
    if (productIds.some(id => document.body.classList.contains(`postid-${id}`))) {
        // Configurar alt de imágenes
        const imgs = document.querySelectorAll('img');
        imgs.forEach(img => {
            const src = img.src;
            const filename = src.split('/').pop();
            const alt = filename.split('.').shift();
            img.alt = alt;
        });

        // Reemplazar h3 vacíos
        const emptyH3Elements = document.querySelectorAll('h3.wapo-addon-title.toggle-closed > span:empty');
        emptyH3Elements.forEach(emptySpanElement => {
            const h3Element = emptySpanElement.closest('h3');
            const newSpanElement = document.createElement('span');
            newSpanElement.innerHTML = h3Element.innerHTML;
            h3Element.parentNode.replaceChild(newSpanElement, h3Element);
        });

        // Configurar labels
        const labels = document.querySelectorAll('label');
        labels.forEach(label => {
            const id = label.getAttribute('for');
            const inputElement = document.getElementById(id);
            if (inputElement) {
                label.setAttribute('for', id);
                const span = document.createElement('span');
                span.className = 'no-visible';
                span.innerHTML = 'label-configurador';
                label.appendChild(span);
            }
        });

        // Añadir atributo 'for' a labels internos
        const divLabelsWithFor = document.querySelectorAll('div.label[for]');
        divLabelsWithFor.forEach(divLabelWithFor => {
            const forValue = divLabelWithFor.getAttribute('for');
            const imageContainerLabel = divLabelWithFor.querySelector('label.image-container');
            if (!imageContainerLabel) {
                const innerLabels = divLabelWithFor.querySelectorAll('div.label_price label');
                innerLabels.forEach(innerLabel => {
                    innerLabel.setAttribute('for', forValue);
                });
            }
        });
    }

    // Configuradores pop-up
    var popupElements = [
        {id: 'yith-wapo-option-79-0', popupID: 2235},
        {id: 'yith-wapo-option-79-1', popupID: 2242},
        {id: 'yith-wapo-option-79-2', popupID: 2243},
        {id: 'yith-wapo-option-82-0', popupID: 2266},
        {id: 'yith-wapo-option-82-1', popupID: 2267},
        {id: 'yith-wapo-option-82-2', popupID: 2268},
        {id: 'yith-wapo-option-973-0', popupID: 2625},
        {id: 'yith-wapo-option-52-0', popupID: 2631}
    ];

    popupElements.forEach(function (element) {
        var triggerElement = document.getElementById(element.id);
        if (!triggerElement) return;

        var previousState = triggerElement.classList.contains('selected');

        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.attributeName === 'class') {
                    var currentState = triggerElement.classList.contains('selected');
                    if (currentState && !previousState) {
                        elementorProFrontend.modules.popup.showPopup({id: element.popupID});
                    }
                    previousState = currentState;
                }
            });
        });

        observer.observe(triggerElement, {attributes: true});
    });

    // Validación del campo de título
    var tituloInputCompleto = document.getElementById('yith-wapo-addon-2');
    var tituloInput = document.getElementById('yith-wapo-2-0');
    var tituloAddonTitle = document.querySelector('.yith-wapo-addon-type-text .addon-header .wapo-addon-title');
    var mensajeTitulo = document.querySelector('#yith-wapo-addon-2 > div.options-container.default-closed > div.min-error');

    if (tituloInput && tituloAddonTitle) {
        tituloInput.addEventListener('input', function () {
            var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
            if (tituloInput.value.trim() !== '') {
                tituloAddonTitle.classList.remove('wapo-error');
                mensajeTitulo.style.display = 'none';
                tituloInputCompleto.classList.remove('required-min');
                if (requiredError) {
                    requiredError.parentNode.removeChild(requiredError);
                }
            } else {
                tituloAddonTitle.classList.add('wapo-error');
                mensajeTitulo.style.display = 'inline-block';
                mensajeTitulo.querySelector('.min-error-message').textContent = 'Por favor, selecciona una opción';
                tituloInputCompleto.classList.add('required-min');
            }
        });
    }

    // Función para validar campos de checkbox
    function setupCheckboxValidationNew(checkboxIds, addonId, borderColorSelector) {
        var checkboxes = checkboxIds.map(id => document.getElementById(id)).filter(Boolean);
        var borderColor = document.querySelector(borderColorSelector);
        var minErrorContainer = document.querySelector(`#${addonId} .min-error`);

        if (checkboxes.length && borderColor && minErrorContainer) {
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var isChecked = checkboxes.some(cb => cb.checked);
                    if (isChecked) {
                        borderColor.classList.remove('error-yith-obligatorio-caja');
                        minErrorContainer.style.display = 'none';
                        minErrorContainer.querySelector('.min-error-message').innerText = '';
                    } else {
                        borderColor.classList.add('error-yith-obligatorio-caja');
                        minErrorContainer.style.display = 'block';
                        minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                    }
                });
            });
        }
    }

    // Configurar validaciones para orientación, formato y encuadernación
    setupCheckboxValidationNew(['yith-wapo-1-0', 'yith-wapo-1-1'], 'yith-wapo-addon-1', "#yith-wapo-option-1-0 > div > div > div.label-container-display");
    setupCheckboxValidationNew(['yith-wapo-5-0', 'yith-wapo-5-1', 'yith-wapo-5-2', 'yith-wapo-5-3', 'yith-wapo-5-4', 'yith-wapo-5-5', 'yith-wapo-5-6', 'yith-wapo-5-7'], 'yith-wapo-addon-5', "#yith-wapo-option-5-0 > div > div > div.label-container-display");
    setupCheckboxValidationNew(['yith-wapo-7-0', 'yith-wapo-7-1', 'yith-wapo-7-2', 'yith-wapo-7-3', 'yith-wapo-7-4', 'yith-wapo-7-5', 'yith-wapo-7-6', 'yith-wapo-7-7', 'yith-wapo-7-8'], 'yith-wapo-addon-7', "#yith-wapo-option-7-0 > div > div > div.label-container-display");

});


document.addEventListener('DOMContentLoaded', function () {
    // Definir todos los grupos de checkboxes
    var checkboxGroups = [
        {
            checkbox: document.getElementById('yith-wapo-15-0'),
            borderColor: "#yith-wapo-option-15-0 > div > div > div.label-container-display",
            minError: '#yith-wapo-addon-15 .min-error'
        },
        {
            checkbox: document.getElementById('yith-wapo-17-0'),
            borderColor: "#yith-wapo-option-17-0 > div > div > div.label-container-display",
            minError: '#yith-wapo-addon-17 .min-error'
        },
        {
            checkbox: document.getElementById('yith-wapo-18-0'),
            borderColor: "#yith-wapo-option-18-0 > div > div > div.label-container-display",
            minError: '#yith-wapo-addon-18 .min-error'
        },
        {
            checkbox: document.getElementById('yith-wapo-23-0'),
            borderColor: "#yith-wapo-option-23-0 > div > div > div.label-container-display",
            minError: '#yith-wapo-addon-23 .min-error'
        },
        {
            checkbox: document.getElementById('yith-wapo-71-0'),
            borderColor: "#yith-wapo-option-71-0 > div > div > div.label-container-display",
            minError: '#yith-wapo-addon-71 .min-error'
        }
    ];

    // Función para manejar el comportamiento de los checkboxes
    checkboxGroups.forEach(function (group) {
        if (!group.checkbox) return;

        var borderColor = document.querySelector(group.borderColor);
        var minErrorContainer = document.querySelector(group.minError);

        // Ocultar el mensaje de error inicialmente
        if (minErrorContainer) {
            minErrorContainer.style.display = 'none';
        }

        // Añadir el evento change al checkbox
        group.checkbox.addEventListener('change', function () {
            var isChecked = group.checkbox.checked;

            if (isChecked) {
                // Si está seleccionado, remover la clase de error y ocultar el mensaje de error
                borderColor.classList.remove('error-yith-obligatorio-caja');
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Si no está seleccionado, añadir la clase de error y mostrar el mensaje de error
                borderColor.classList.add('error-yith-obligatorio-caja');
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPOS PAPELES OPCIONES VALIDACION
    var checkboxIds = [
        'yith-wapo-31-0',
        'yith-wapo-31-1',
        'yith-wapo-31-2',
        'yith-wapo-31-3',
        'yith-wapo-31-4',
        'yith-wapo-31-5',
        'yith-wapo-31-6',
        'yith-wapo-31-7',
        'yith-wapo-31-8',
        'yith-wapo-31-9',
        'yith-wapo-31-10',
        'yith-wapo-31-12',
        'yith-wapo-31-13',
        'yith-wapo-31-14',
        'yith-wapo-31-15',
        'yith-wapo-31-16',
        'yith-wapo-31-17',
        'yith-wapo-31-18',
        'yith-wapo-31-19',
        'yith-wapo-31-20',
        'yith-wapo-31-21',
        'yith-wapo-31-22',
        'yith-wapo-31-23',
        'yith-wapo-31-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-31 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-31 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-89-0',
        'yith-wapo-89-1',
        'yith-wapo-89-2',
        'yith-wapo-89-3',
        'yith-wapo-89-4',
        'yith-wapo-89-5',
        'yith-wapo-89-6',
        'yith-wapo-89-7',
        'yith-wapo-89-8',
        'yith-wapo-89-9',
        'yith-wapo-89-10',
        'yith-wapo-89-12',
        'yith-wapo-89-13',
        'yith-wapo-89-14',
        'yith-wapo-89-15',
        'yith-wapo-89-16',
        'yith-wapo-89-17',
        'yith-wapo-89-18',
        'yith-wapo-89-19',
        'yith-wapo-89-20',
        'yith-wapo-89-21',
        'yith-wapo-89-22',
        'yith-wapo-89-23',
        'yith-wapo-89-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-89 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-89 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-37-0',
        'yith-wapo-37-1',
        'yith-wapo-37-2',
        'yith-wapo-37-3',
        'yith-wapo-37-4',
        'yith-wapo-37-5',
        'yith-wapo-37-6',
        'yith-wapo-37-7',
        'yith-wapo-37-8',
        'yith-wapo-37-9',
        'yith-wapo-37-10',
        'yith-wapo-37-12',
        'yith-wapo-37-13',
        'yith-wapo-37-14',
        'yith-wapo-37-15',
        'yith-wapo-37-16',
        'yith-wapo-37-17',
        'yith-wapo-37-18',
        'yith-wapo-37-19',
        'yith-wapo-37-20',
        'yith-wapo-37-21',
        'yith-wapo-37-22',
        'yith-wapo-37-23',
        'yith-wapo-37-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-37 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-37 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-90-0',
        'yith-wapo-90-1',
        'yith-wapo-90-2',
        'yith-wapo-90-3',
        'yith-wapo-90-4',
        'yith-wapo-90-5',
        'yith-wapo-90-6',
        'yith-wapo-90-7',
        'yith-wapo-90-8',
        'yith-wapo-90-9',
        'yith-wapo-90-10',
        'yith-wapo-90-12',
        'yith-wapo-90-13',
        'yith-wapo-90-14',
        'yith-wapo-90-15',
        'yith-wapo-90-16',
        'yith-wapo-90-17',
        'yith-wapo-90-18',
        'yith-wapo-90-19',
        'yith-wapo-90-20',
        'yith-wapo-90-21',
        'yith-wapo-90-22',
        'yith-wapo-90-23',
        'yith-wapo-90-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-90 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-90 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-24-0',
        'yith-wapo-24-1',
        'yith-wapo-24-2',
        'yith-wapo-24-3',
        'yith-wapo-24-4',
        'yith-wapo-24-5',
        'yith-wapo-24-6',
        'yith-wapo-24-7',
        'yith-wapo-24-8',
        'yith-wapo-24-9',
        'yith-wapo-24-10',
        'yith-wapo-24-12',
        'yith-wapo-24-13',
        'yith-wapo-24-14',
        'yith-wapo-24-15',
        'yith-wapo-24-16',
        'yith-wapo-24-17',
        'yith-wapo-24-18',
        'yith-wapo-24-19',
        'yith-wapo-24-20',
        'yith-wapo-24-21',
        'yith-wapo-24-22',
        'yith-wapo-24-23',
        'yith-wapo-24-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-24 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-24 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-42-0',
        'yith-wapo-42-1',
        'yith-wapo-42-2',
        'yith-wapo-42-3',
        'yith-wapo-42-4',
        'yith-wapo-42-5',
        'yith-wapo-42-6',
        'yith-wapo-42-7',
        'yith-wapo-42-8',
        'yith-wapo-42-9',
        'yith-wapo-42-10',
        'yith-wapo-42-12',
        'yith-wapo-42-13',
        'yith-wapo-42-14',
        'yith-wapo-42-15',
        'yith-wapo-42-16',
        'yith-wapo-42-17',
        'yith-wapo-42-18',
        'yith-wapo-42-19',
        'yith-wapo-42-20',
        'yith-wapo-42-21',
        'yith-wapo-42-22',
        'yith-wapo-42-23',
        'yith-wapo-42-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-42 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-42 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-47-0',
        'yith-wapo-47-1',
        'yith-wapo-47-2',
        'yith-wapo-47-3',
        'yith-wapo-47-4',
        'yith-wapo-47-5',
        'yith-wapo-47-6',
        'yith-wapo-47-7',
        'yith-wapo-47-8',
        'yith-wapo-47-9',
        'yith-wapo-47-10',
        'yith-wapo-47-12',
        'yith-wapo-47-13',
        'yith-wapo-47-14',
        'yith-wapo-47-15',
        'yith-wapo-47-16',
        'yith-wapo-47-17',
        'yith-wapo-47-18',
        'yith-wapo-47-19',
        'yith-wapo-47-20',
        'yith-wapo-47-21',
        'yith-wapo-47-22',
        'yith-wapo-47-23',
        'yith-wapo-47-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-47 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-47 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-53-0',
        'yith-wapo-53-1',
        'yith-wapo-53-2',
        'yith-wapo-53-3',
        'yith-wapo-53-4',
        'yith-wapo-53-5',
        'yith-wapo-53-6',
        'yith-wapo-53-7',
        'yith-wapo-53-8',
        'yith-wapo-53-9',
        'yith-wapo-53-10',
        'yith-wapo-53-12',
        'yith-wapo-53-13',
        'yith-wapo-53-14',
        'yith-wapo-53-15',
        'yith-wapo-53-16',
        'yith-wapo-53-17',
        'yith-wapo-53-18',
        'yith-wapo-53-19',
        'yith-wapo-53-20',
        'yith-wapo-53-21',
        'yith-wapo-53-22',
        'yith-wapo-53-23',
        'yith-wapo-53-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-53 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-53 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-60-0',
        'yith-wapo-60-1',
        'yith-wapo-60-2',
        'yith-wapo-60-3',
        'yith-wapo-60-4',
        'yith-wapo-60-5',
        'yith-wapo-60-6',
        'yith-wapo-60-7',
        'yith-wapo-60-8',
        'yith-wapo-60-9',
        'yith-wapo-60-10',
        'yith-wapo-60-12',
        'yith-wapo-60-13',
        'yith-wapo-60-14',
        'yith-wapo-60-15',
        'yith-wapo-60-16',
        'yith-wapo-60-17',
        'yith-wapo-60-18',
        'yith-wapo-60-19',
        'yith-wapo-60-20',
        'yith-wapo-60-21',
        'yith-wapo-60-22',
        'yith-wapo-60-23',
        'yith-wapo-60-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-60 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-60 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-67-0',
        'yith-wapo-67-1',
        'yith-wapo-67-2',
        'yith-wapo-67-3',
        'yith-wapo-67-4',
        'yith-wapo-67-5',
        'yith-wapo-67-6',
        'yith-wapo-67-7',
        'yith-wapo-67-8',
        'yith-wapo-67-9',
        'yith-wapo-67-10',
        'yith-wapo-67-12',
        'yith-wapo-67-13',
        'yith-wapo-67-14',
        'yith-wapo-67-15',
        'yith-wapo-67-16',
        'yith-wapo-67-17',
        'yith-wapo-67-18',
        'yith-wapo-67-19',
        'yith-wapo-67-20',
        'yith-wapo-67-21',
        'yith-wapo-67-22',
        'yith-wapo-67-23',
        'yith-wapo-67-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-67 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-67 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxIds = [
        'yith-wapo-99-0',
        'yith-wapo-99-1',
        'yith-wapo-99-2',
        'yith-wapo-99-3',
        'yith-wapo-99-4',
        'yith-wapo-99-5',
        'yith-wapo-99-6',
        'yith-wapo-99-7',
        'yith-wapo-99-8',
        'yith-wapo-99-9',
        'yith-wapo-99-10',
        'yith-wapo-99-12',
        'yith-wapo-99-13',
        'yith-wapo-99-14',
        'yith-wapo-99-15',
        'yith-wapo-99-16',
        'yith-wapo-99-17',
        'yith-wapo-99-18',
        'yith-wapo-99-19',
        'yith-wapo-99-20',
        'yith-wapo-99-21',
        'yith-wapo-99-22',
        'yith-wapo-99-23',
        'yith-wapo-99-24'
    ];

    var checkboxes = checkboxIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (checkbox) {
        return checkbox !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-99 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-99 .min-error');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');
                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');
                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPOS TIPO PAPEL VALIDACION
    var checkboxes = [
        document.getElementById('yith-wapo-30-0'),
        document.getElementById('yith-wapo-30-1')
    ];

    var minErrorContainer = document.querySelector('#yith-wapo-addon-30 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-36-0'),
        document.getElementById('yith-wapo-36-1')
    ];

    var minErrorContainer = document.querySelector('#yith-wapo-addon-36 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-68-0'),
        document.getElementById('yith-wapo-68-1')
    ];

    var minErrorContainer = document.querySelector('#yith-wapo-addon-68 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPOS TINTAS OPCIONES VALIDACION
    var checkboxes = [
        document.getElementById('yith-wapo-32-0'),
        document.getElementById('yith-wapo-32-1'),
        document.getElementById('yith-wapo-32-2'),
        document.getElementById('yith-wapo-32-3'),
        document.getElementById('yith-wapo-32-4'),
        document.getElementById('yith-wapo-32-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-32 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-32 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-38-0'),
        document.getElementById('yith-wapo-38-1'),
        document.getElementById('yith-wapo-38-2'),
        document.getElementById('yith-wapo-38-3'),
        document.getElementById('yith-wapo-38-4'),
        document.getElementById('yith-wapo-38-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-38 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-38 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-25-0'),
        document.getElementById('yith-wapo-25-1'),
        document.getElementById('yith-wapo-25-2'),
        document.getElementById('yith-wapo-25-3'),
        document.getElementById('yith-wapo-25-4'),
        document.getElementById('yith-wapo-25-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-25 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-25 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-43-0'),
        document.getElementById('yith-wapo-43-1'),
        document.getElementById('yith-wapo-43-2'),
        document.getElementById('yith-wapo-43-3'),
        document.getElementById('yith-wapo-43-4'),
        document.getElementById('yith-wapo-43-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-43 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-43 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-48-0'),
        document.getElementById('yith-wapo-48-1'),
        document.getElementById('yith-wapo-48-2'),
        document.getElementById('yith-wapo-48-3'),
        document.getElementById('yith-wapo-48-4'),
        document.getElementById('yith-wapo-48-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-48 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-48 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-54-0'),
        document.getElementById('yith-wapo-54-1'),
        document.getElementById('yith-wapo-54-2'),
        document.getElementById('yith-wapo-54-3'),
        document.getElementById('yith-wapo-54-4'),
        document.getElementById('yith-wapo-54-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-54 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-54 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-61-0'),
        document.getElementById('yith-wapo-61-1'),
        document.getElementById('yith-wapo-61-2'),
        document.getElementById('yith-wapo-61-3'),
        document.getElementById('yith-wapo-61-4'),
        document.getElementById('yith-wapo-61-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-61 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-61 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = [
        document.getElementById('yith-wapo-64-0'),
        document.getElementById('yith-wapo-64-1'),
        document.getElementById('yith-wapo-64-2'),
        document.getElementById('yith-wapo-64-3'),
        document.getElementById('yith-wapo-64-4'),
        document.getElementById('yith-wapo-64-5')
    ];

    var addonTitle = document.querySelector('#yith-wapo-addon-64 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-64 .min-error');

    checkboxes.forEach(function (checkbox) {
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isChecked = checkboxes.some(function (cb) {
                return cb.checked;
            });

            if (isChecked) {
                // Checkbox está marcado
                addonTitle.classList.remove('error-yith-obligatorio');

                // Ocultar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'none';
                    minErrorContainer.querySelector('.min-error-message').innerText = '';
                }
            } else {
                // Checkbox no está marcado
                addonTitle.classList.add('error-yith-obligatorio');

                // Mostrar el mensaje de error
                if (minErrorContainer) {
                    minErrorContainer.style.display = 'block';
                    minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, selecciona una opción';
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO PAGINAS VALIDACION
    var numberField = document.getElementById('yith-wapo-977-0');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-977 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var numberField = document.getElementById('yith-wapo-978-0');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-978 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO SOLAPAS VALIDACION
    var inputField = document.getElementById('yith-wapo-979-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-979 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO ENCUADERNADO VALIDACION
    var selectField = document.getElementById('yith-wapo-69');
    var addonTitleSelect = document.querySelector('#yith-wapo-addon-69 .addon-header .wapo-addon-title');

    selectField.addEventListener('change', function () {
        var requiredErrorSelect = document.querySelector('.yith-wapo-addon-type-select .required-error');
        if (selectField.value !== 'default') {
            // Remover clases de error
            addonTitleSelect.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorSelect) {
                requiredErrorSelect.parentNode.removeChild(requiredErrorSelect);
            }
        } else {
            // Si el campo vuelve a estar en la opción por defecto, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleSelect.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-select .required-error')) {
                var newRequiredErrorSelect = document.createElement('div');
                newRequiredErrorSelect.classList.add('required-error');
                newRequiredErrorSelect.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                selectField.parentElement.appendChild(newRequiredErrorSelect);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var selectField = document.getElementById('yith-wapo-83');
    var addonTitleSelect = document.querySelector('#yith-wapo-addon-83 .addon-header .wapo-addon-title');

    selectField.addEventListener('change', function () {
        var requiredErrorSelect = document.querySelector('.yith-wapo-addon-type-select .required-error');
        if (selectField.value !== 'default') {
            // Remover clases de error
            addonTitleSelect.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorSelect) {
                requiredErrorSelect.parentNode.removeChild(requiredErrorSelect);
            }
        } else {
            // Si el campo vuelve a estar en la opción por defecto, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleSelect.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-select .required-error')) {
                var newRequiredErrorSelect = document.createElement('div');
                newRequiredErrorSelect.classList.add('required-error');
                newRequiredErrorSelect.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                selectField.parentElement.appendChild(newRequiredErrorSelect);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var selectField = document.getElementById('yith-wapo-85');
    var addonTitleSelect = document.querySelector('#yith-wapo-addon-85 .addon-header .wapo-addon-title');

    selectField.addEventListener('change', function () {
        var requiredErrorSelect = document.querySelector('.yith-wapo-addon-type-select .required-error');
        if (selectField.value !== 'default') {
            // Remover clases de error
            addonTitleSelect.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorSelect) {
                requiredErrorSelect.parentNode.removeChild(requiredErrorSelect);
            }
        } else {
            // Si el campo vuelve a estar en la opción por defecto, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleSelect.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-select .required-error')) {
                var newRequiredErrorSelect = document.createElement('div');
                newRequiredErrorSelect.classList.add('required-error');
                newRequiredErrorSelect.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                selectField.parentElement.appendChild(newRequiredErrorSelect);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO COLORES VALIDACION
    var inputField = document.getElementById('yith-wapo-84-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-84 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-86-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-86 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-87-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-87 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-88-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-88 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO CONJUNTOS VALORACION
    var inputField = document.getElementById('yith-wapo-74-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-74 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO CANIDADES VALIDACION
    var numberField = document.getElementById('yith-wapo-980-0');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-980 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var numberField = document.getElementById('yith-wapo-980-1');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-980 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var numberField = document.getElementById('yith-wapo-980-2');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-980 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var numberField = document.getElementById('yith-wapo-980-3');
    var addonTitleNumber = document.querySelector('#yith-wapo-addon-980 .addon-header .wapo-addon-title');

    numberField.addEventListener('input', function () {
        var requiredErrorNumber = document.querySelector('.yith-wapo-addon-type-number .required-error');
        if (numberField.value.trim() !== '' && numberField.value.trim() !== 0) {
            // Remover clases de error
            addonTitleNumber.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredErrorNumber) {
                requiredErrorNumber.parentNode.removeChild(requiredErrorNumber);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitleNumber.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-number .required-error')) {
                var newRequiredErrorNumber = document.createElement('div');
                newRequiredErrorNumber.classList.add('required-error');
                newRequiredErrorNumber.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                numberField.parentElement.appendChild(newRequiredErrorNumber);
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // CAMPO ENTREGAS VALORACION
    var inputField = document.getElementById('yith-wapo-982-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-982 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-982-1');
    var addonTitle = document.querySelector('#yith-wapo-addon-982 .addon-header .wapo-addon-title');

    // Verificar si el inputField y addonTitle existen antes de agregar el event listener
    if (inputField && addonTitle) {
        inputField.addEventListener('input', function () {
            var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');

            if (inputField.value.trim() !== '') {
                // Remover clases de error
                addonTitle.classList.remove('error-yith-obligatorio');

                // Eliminar el elemento <div class="required-error"> si existe
                if (requiredError) {
                    requiredError.parentNode.removeChild(requiredError);
                }
            } else {
                // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
                addonTitle.classList.add('error-yith-obligatorio');

                // Volver a crear y agregar el <div class="required-error"> si no existe
                if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                    var newRequiredError = document.createElement('div');
                    newRequiredError.classList.add('required-error');
                    newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                    inputField.parentElement.appendChild(newRequiredError);
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-76-0');
    var addonTitle = document.querySelector('#yith-wapo-addon-76 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-76-1');
    var addonTitle = document.querySelector('#yith-wapo-addon-76 .addon-header .wapo-addon-title');

    inputField.addEventListener('input', function () {
        var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');
        if (inputField.value.trim() !== '') {
            // Remover clases de error
            addonTitle.classList.remove('error-yith-obligatorio');
            // Eliminar el elemento <div class="required-error"> si existe
            if (requiredError) {
                requiredError.parentNode.removeChild(requiredError);
            }
        } else {
            // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
            addonTitle.classList.add('error-yith-obligatorio');
            // Volver a crear y agregar el <div class="required-error"> si no existe
            if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                var newRequiredError = document.createElement('div');
                newRequiredError.classList.add('required-error');
                newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                inputField.parentElement.appendChild(newRequiredError);
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var inputField = document.getElementById('yith-wapo-76-1');
    var addonTitle = document.querySelector('#yith-wapo-addon-76 .addon-header .wapo-addon-title');

    // Verificar si inputField y addonTitle existen antes de agregar el event listener
    if (inputField && addonTitle) {
        inputField.addEventListener('input', function () {
            var requiredError = document.querySelector('.yith-wapo-addon-type-text .required-error');

            if (inputField.value.trim() !== '') {
                // Remover clases de error
                addonTitle.classList.remove('error-yith-obligatorio');

                // Eliminar el elemento <div class="required-error"> si existe
                if (requiredError) {
                    requiredError.parentNode.removeChild(requiredError);
                }
            } else {
                // Si el campo vuelve a estar vacío, se pueden agregar nuevamente las clases de error (opcional)
                addonTitle.classList.add('error-yith-obligatorio');

                // Volver a crear y agregar el <div class="required-error"> si no existe
                if (!document.querySelector('.yith-wapo-addon-type-text .required-error')) {
                    var newRequiredError = document.createElement('div');
                    newRequiredError.classList.add('required-error');
                    newRequiredError.innerHTML = '<small class="required-message">Esta opción es obligatoria.</small>';
                    if (inputField.parentElement) {
                        inputField.parentElement.appendChild(newRequiredError);
                    }
                }
            }
        });
    }
});


// Función que se encargará de actualizar el texto del label, agregar data-min y gestionar el mensaje de error
function updateLabelAndMin() {
    const guardasLabel = document.querySelector('div.label[for="yith-wapo-18-0"] .label_price label');
    const guardasDiv = document.querySelector('#yith-wapo-addon-18'); // Selecciona correctamente el div

    if (guardasLabel && guardasDiv) {
        // Eliminar el asterisco del texto del label si existe
        guardasLabel.textContent = guardasLabel.textContent.replace('*', '').trim();

        // Encuentra la opción seleccionada después de que el cambio de clase haya ocurrido
        const selectedOption = document.querySelector('.yith-wapo-option.selected');

        // Verificar si alguna de las opciones seleccionadas es obligatoria
        if (selectedOption &&
            (selectedOption.id === 'yith-wapo-option-7-4' ||
                selectedOption.id === 'yith-wapo-option-7-5' ||
                selectedOption.id === 'yith-wapo-option-7-6' ||
                selectedOption.id === 'yith-wapo-option-7-7')) {

            // Agregar el asterisco y establecer data-min="1"
            guardasLabel.textContent += ' *';
            guardasDiv.setAttribute('data-min', '1');
            console.log('Asterisco añadido, data-min="1" asignado.');

            // Mostrar el mensaje de error si existe
            let minErrorDiv = guardasDiv.querySelector('.min-error');
            if (!minErrorDiv) {
                // Si no existe el mensaje de error, crearlo y añadirlo
                minErrorDiv = document.createElement('div');
                minErrorDiv.classList.add('min-error');
                minErrorDiv.style.display = 'none'; // Iniciar oculto

                const minErrorMessage = document.createElement('span');
                minErrorMessage.classList.add('min-error-message');
                minErrorMessage.textContent = 'Por favor, selecciona una opción';

                minErrorDiv.appendChild(minErrorMessage);

                // Añadir el div del mensaje de error después de las opciones
                const optionsContainer = guardasDiv.querySelector('.options-container');
                if (optionsContainer) {
                    optionsContainer.appendChild(minErrorDiv);
                }
            }

            // Verificar si la clase de error está presente para mostrar el mensaje
            const labelContainerDisplay = guardasDiv.querySelector('.label-container-display');
            if (labelContainerDisplay.classList.contains('error-yith-obligatorio-caja')) {
                minErrorDiv.style.display = 'block'; // Mostrar el error si la clase está presente
            } else {
                minErrorDiv.style.display = 'none'; // Ocultar el error si la clase no está
            }

        } else {
            // Eliminar data-min si no es obligatorio
            guardasDiv.removeAttribute('data-min');
            console.log('Asterisco eliminado, data-min eliminado.');

            // Quitar la clase 'error-yith-obligatorio-caja' si ya no es obligatorio
            const labelContainerDisplay = guardasDiv.querySelector('.label-container-display');
            if (labelContainerDisplay && labelContainerDisplay.classList.contains('error-yith-obligatorio-caja')) {
                labelContainerDisplay.classList.remove('error-yith-obligatorio-caja');
            }

            // Ocultar el mensaje de error si existe
            const minErrorDiv = guardasDiv.querySelector('.min-error');
            if (minErrorDiv) {
                minErrorDiv.style.display = 'none'; // Asegurarse de ocultar el mensaje de error
            }
        }
    } else {
        console.error('No se encontró el label "GUARDAS *" o el div con id "yith-wapo-addon-18".');
    }
}

// Agregar evento click a cada opción
document.querySelectorAll('.yith-wapo-option').forEach(option => {
    option.addEventListener('click', function () {
        // Delay para asegurarse que la clase "selected" esté actualizada
        setTimeout(updateLabelAndMin, 0);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    // Definir los IDs de los inputs de texto
    var inputIds = [
        'yith-wapo-76-0',
        'yith-wapo-76-1'
    ];

    // Obtener los elementos de input basados en los IDs
    var inputs = inputIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (input) {
        return input !== null;
    });

    // Obtener el título del addon y el contenedor de error
    var addonTitle = document.querySelector('#yith-wapo-addon-76 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-76 .min-error');

    // Función para verificar si al menos un campo de texto está lleno
    function checkInputs() {
        var isFilled = inputs.some(function (input) {
            return input.value.trim() !== ''; // Verifica si el campo tiene algún valor
        });

        if (isFilled) {
            // Al menos un input está lleno
            addonTitle.classList.remove('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'none';
                minErrorContainer.querySelector('.min-error-message').innerText = '';
            }
        } else {
            // Ningún input está lleno
            addonTitle.classList.add('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'block';
                minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, completa al menos un campo';
            }
        }
    }

    // Añadir el listener a cada input para ejecutar la función de verificación al cambiar
    inputs.forEach(function (input) {
        input.addEventListener('input', checkInputs);
    });

    // Ejecutar la verificación inicial para el estado correcto al cargar la página
    checkInputs();
});

document.addEventListener('DOMContentLoaded', function () {
    // Definir los IDs de los inputs de texto dentro del addon-982
    var inputIds = [
        'yith-wapo-982-0'
    ];

    // Obtener los elementos de input basados en los IDs
    var inputs = inputIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (input) {
        return input !== null;
    });

    // Obtener el título del addon y el contenedor de error
    var addonTitle = document.querySelector('#yith-wapo-addon-982 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-982 .min-error');

    // Función para verificar si al menos un campo de texto está lleno
    function checkInputs() {
        var isFilled = inputs.some(function (input) {
            return input.value.trim() !== ''; // Verifica si el campo tiene algún valor
        });

        if (isFilled) {
            // Al menos un input está lleno
            addonTitle.classList.remove('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'none';
                minErrorContainer.querySelector('.min-error-message').innerText = '';
            }
        } else {
            // Ningún input está lleno
            addonTitle.classList.add('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'block';
                minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, completa este campo';
            }
        }
    }

    // Añadir el listener a cada input para ejecutar la función de verificación al cambiar
    inputs.forEach(function (input) {
        input.addEventListener('input', checkInputs);
    });

    // Ejecutar la verificación inicial para el estado correcto al cargar la página
    checkInputs();
});

document.addEventListener('DOMContentLoaded', function () {
    // Definir los IDs de los inputs de texto dentro del addon-92
    var inputIds = [
        'yith-wapo-92-0',
        'yith-wapo-92-1'
    ];

    // Obtener los elementos de input basados en los IDs
    var inputs = inputIds.map(function (id) {
        return document.getElementById(id);
    }).filter(function (input) {
        return input !== null;
    });

    var addonTitle = document.querySelector('#yith-wapo-addon-92 .addon-header .wapo-addon-title');
    var minErrorContainer = document.querySelector('#yith-wapo-addon-92 .min-error');


    function checkInputs() {
        var filledCount = inputs.filter(function (input) {
            return input.value.trim() !== '';
        }).length;

        if (filledCount >= 2) {

            addonTitle.classList.remove('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'none';
                minErrorContainer.querySelector('.min-error-message').innerText = '';
            }
        } else {
            addonTitle.classList.add('error-yith-obligatorio');
            if (minErrorContainer) {
                minErrorContainer.style.display = 'block';
                minErrorContainer.querySelector('.min-error-message').innerText = 'Por favor, completa al menos dos campos';
            }
        }
    }


    inputs.forEach(function (input) {
        input.addEventListener('input', checkInputs);
    });


    checkInputs();
});


document.addEventListener('DOMContentLoaded', () => {
    const spans = [
        {id: 'interior', parentSelector: '#yith-wapo-option-15-0', checked: false},
        {id: 'interior-2', parentSelector: '#yith-wapo-option-16-0', checked: false},
        {id: 'cubierta', parentSelector: '#yith-wapo-option-17-0', checked: false},
        {id: 'guardas', parentSelector: '#yith-wapo-option-18-0', checked: false},
        {id: 'sobrecubierta', parentSelector: '#yith-wapo-option-19-0', checked: false},
        {id: 'faja', parentSelector: '#yith-wapo-option-20-0', checked: false},
        {id: 'marcapaginas', parentSelector: '#yith-wapo-option-21-0', checked: false},
        {id: 'desplegable', parentSelector: '#yith-wapo-option-22-0', checked: false},
        {id: 'encuadernacion', parentSelector: '#yith-wapo-option-23-0', checked: false},
        {id: 'entregas', parentSelector: '#yith-wapo-option-71-0', checked: false}
    ];

    spans.forEach(span => {
        const parent = document.querySelector(span.parentSelector);
        const newSpan = document.createElement('span');
        newSpan.id = span.id;
        newSpan.className = 'checkbox';
        if (span.checked) {
            newSpan.setAttribute('data-checked', 'true');
            parent.classList.add('selected');
        }
        parent.querySelector('div.label_price').appendChild(newSpan);
    });

    function Checkbox(elem, parentSelector, observer, additionalObserver) {
        this.elem = elem;
        this.parentElem = document.querySelector(parentSelector);
        this.checked = elem.dataset.checked === 'true';
        this.observer = observer;
        this.additionalObserver = additionalObserver;

        this.render();

        this.elem.addEventListener('click', e => {
            e.stopPropagation();
            this.checked = !this.checked;
            this.render();
        });

        this.parentElem.addEventListener('click', e => {
            if (!e.target.classList.contains('checkbox')) {
                this.checked = true;
                this.render();
            }
        }, false);
    }

    Checkbox.prototype.render = function () {
        if (this.checked) {
            this.elem.setAttribute('data-checked', 'true');
            this.updateClassWithDelay(true);
        } else {
            this.elem.removeAttribute('data-checked');
            this.updateClassWithDelay(false);
        }
    }

// Agregamos una variable global para controlar el bloqueo de clics
    let isLocked = false;

    Checkbox.prototype.updateClassWithDelay = function (add) {
        // Desconectar observadores para evitar que reaccionen a los cambios
        this.observer.disconnect();
        this.additionalObserver.disconnect();

        const parentElemClick = this.parentElem.querySelector("div > div > div.label-container-display > div > div > label");

        // Función para deshabilitar temporalmente los clics programáticos
        const disableProgrammaticClicks = () => {
            console.log('Desactivando clics programáticos');
            parentElemClick.style.pointerEvents = 'none'; // Deshabilitar clics programáticos
        };

        // Función para reactivar clics programáticos tras una interacción del usuario
        const reenableProgrammaticClicks = () => {
            console.log('Reactivando clics programáticos tras interacción del usuario');
            parentElemClick.style.pointerEvents = 'auto'; // Reactivar clics programáticos
            isLocked = false; // Restablecemos el flag de bloqueo
            document.removeEventListener('click', reenableProgrammaticClicks); // Eliminar el listener tras el primer clic
        };

        requestAnimationFrame(() => {
            const wasSelected = this.parentElem.classList.contains('selected');

            if (add) {
                // Si se activa, añadimos la clase 'selected'
                this.parentElem.classList.add('selected');
            } else {
                // Si se desactiva, eliminamos la clase 'selected'
                this.parentElem.classList.remove('selected');
            }

            const isNowSelected = this.parentElem.classList.contains('selected');

            // Solo disparar el clic adicional si el estado 'selected' ha cambiado
            if (wasSelected !== isNowSelected) {
                if (!add) {
                    // Si estamos removiendo, lanzamos el clic de manera programática para contraer
                    console.log('Desactivando: lanzar clic en label sin reactivar observadores.');

                    // Establecer el bloqueo temporal antes del clic
                    isLocked = true;
                    disableProgrammaticClicks();

                    setTimeout(() => {
                        console.log('Acción adicional de click sobre (desactivando)', parentElemClick);
                        parentElemClick.click(); // Hacemos el clic programático

                        // Segundo retraso para asegurarnos de que el clic se procese antes de eliminar 'selected'
                        setTimeout(() => {
                            this.parentElem.classList.remove('selected'); // Removemos 'selected' de forma segura
                            const cambiocheck = this.parentElem.querySelector(".checkbox");
                            cambiocheck.setAttribute('data-checked', 'false');

                            // Reactivar los observadores después de desactivar
                            this.observer.observe(this.parentElem, {attributes: true});
                            this.additionalObserver.observe(this.parentElem, {attributes: true});

                            // Esperar a la próxima interacción del usuario para reactivar clics programáticos
                            document.addEventListener('click', reenableProgrammaticClicks);
                        }, 10); // Retraso adicional de 100 ms para remover la clase después del clic programático

                    }, 10); // Reducimos el retraso para hacer más rápido el proceso
                } else {
                    // Si isLocked está activo, evitamos cualquier clic programático
                    if (isLocked) {
                        console.log('Clic programático bloqueado temporalmente');
                        return;
                    }

                    console.log('Acción adicional de click sobre (activando)', parentElemClick);
                    parentElemClick.click(); // Hacemos el clic programático

                    // Reactivar observadores inmediatamente después de la activación
                    this.observer.observe(this.parentElem, {attributes: true});
                    this.additionalObserver.observe(this.parentElem, {attributes: true});
                }
            } else {
                // Si no hay cambios, reactiva los observadores sin clic adicional
                this.observer.observe(this.parentElem, {attributes: true});
                this.additionalObserver.observe(this.parentElem, {attributes: true});
            }
        });
    };


    function initCheckboxes(parentSelector) {
        const parentElem = document.querySelector(parentSelector);
        const parentElemClick = parentElem.querySelector("div > div > div.label-container-display > div > div > label");

        // Primer observer
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const checkboxSpan = parentElem.querySelector('.checkbox');
                    if (checkboxSpan && checkboxSpan.getAttribute('data-checked') === 'true') {
                        if (!parentElem.classList.contains('selected')) {
                            parentElem.classList.add('selected');
                        }
                    } else {
                        parentElem.classList.remove('selected');
                    }
                }
            });
        });

        // Segundo observer para asegurar consistencia en cambios de clase
        const additionalObserver = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const checkboxSpan = parentElem.querySelector('.checkbox');
                    const isNowSelected = parentElem.classList.contains('selected');
                    const isNowChecked = checkboxSpan && checkboxSpan.getAttribute('data-checked') === 'true';

                    if (isNowSelected !== isNowChecked && isNowChecked) {
                        console.log('Acción adicional de click sobre', parentElemClick);
                        parentElemClick.click();
                    }
                }
            });
        });

        observer.observe(parentElem, {attributes: true});
        additionalObserver.observe(parentElem, {attributes: true});

        const elems = document.querySelectorAll(`${parentSelector} .checkbox`);
        elems.forEach(elem => new Checkbox(elem, parentSelector, observer, additionalObserver));
    }


    const parents = [
        '#yith-wapo-option-15-0',
        '#yith-wapo-option-16-0',
        '#yith-wapo-option-17-0',
        '#yith-wapo-option-18-0',
        '#yith-wapo-option-19-0',
        '#yith-wapo-option-20-0',
        '#yith-wapo-option-21-0',
        '#yith-wapo-option-22-0',
        '#yith-wapo-option-23-0',
        '#yith-wapo-option-71-0'
    ];

    parents.forEach(parent => initCheckboxes(parent));
});


document.addEventListener('DOMContentLoaded', () => {
    const cssMap = {
        '#yith-wapo-option-15-0': ['#yith-wapo-addon-30', '#yith-wapo-addon-31', '#yith-wapo-addon-89', '#yith-wapo-addon-32', '#yith-wapo-addon-977', '#yith-wapo-addon-33', '#yith-wapo-addon-34', '#yith-wapo-addon-970'],
        '#yith-wapo-option-16-0': ['#yith-wapo-addon-36', '#yith-wapo-addon-37', '#yith-wapo-addon-90', '#yith-wapo-addon-38', '#yith-wapo-addon-978', '#yith-wapo-addon-41', '#yith-wapo-addon-40', '#yith-wapo-addon-971'],
        '#yith-wapo-option-17-0': ['#yith-wapo-addon-24', '#yith-wapo-addon-25', '#yith-wapo-addon-26', '#yith-wapo-addon-972', '#yith-wapo-addon-27', '#yith-wapo-addon-28', '#yith-wapo-addon-29', '#yith-wapo-addon-973', '#yith-wapo-addon-78', '#yith-wapo-addon-79'],
        '#yith-wapo-option-18-0': ['#yith-wapo-addon-42', '#yith-wapo-addon-43', '#yith-wapo-addon-45', '#yith-wapo-addon-44', '#yith-wapo-addon-80'],
        '#yith-wapo-option-19-0': ['#yith-wapo-addon-47', '#yith-wapo-addon-48', '#yith-wapo-addon-979', '#yith-wapo-addon-49', '#yith-wapo-addon-51', '#yith-wapo-addon-50', '#yith-wapo-addon-52', '#yith-wapo-addon-81', '#yith-wapo-addon-82'],
        '#yith-wapo-option-20-0': ['#yith-wapo-addon-53', '#yith-wapo-addon-54', '#yith-wapo-addon-57', '#yith-wapo-addon-56'],
        '#yith-wapo-option-21-0': ['#yith-wapo-addon-60', '#yith-wapo-addon-61', '#yith-wapo-addon-62', '#yith-wapo-addon-63'],
        '#yith-wapo-option-22-0': ['#yith-wapo-addon-68', '#yith-wapo-addon-67', '#yith-wapo-addon-99', '#yith-wapo-addon-64', '#yith-wapo-addon-65', '#yith-wapo-addon-66', '#yith-wapo-addon-974', '#yith-wapo-addon-58', '#yith-wapo-addon-59'],
        '#yith-wapo-option-23-0': ['#yith-wapo-addon-83', '#yith-wapo-addon-84', '#yith-wapo-addon-85', '#yith-wapo-addon-86', '#yith-wapo-addon-69', '#yith-wapo-addon-70', '#yith-wapo-addon-87', '#yith-wapo-addon-88'],
        '#yith-wapo-option-71-0': ['#yith-wapo-addon-73', '#yith-wapo-addon-74', '#yith-wapo-addon-96', '#yith-wapo-addon-75', '#yith-wapo-addon-982', '#yith-wapo-addon-983', '#yith-wapo-addon-76', '#yith-wapo-addon-984', '#yith-wapo-addon-91', '#yith-wapo-addon-92', '#yith-wapo-addon-985', '#yith-wapo-addon-93', '#yith-wapo-addon-986', '#yith-wapo-addon-94', '#yith-wapo-addon-987', '#yith-wapo-addon-95', '#yith-wapo-addon-988', '#yith-wapo-addon-980', '#yith-wapo-addon-989']
    };


    // Función para alternar la clase 'opcion-no-visible'
    function toggleClassVisibility(parentSelector) {
        const elementsToToggle = cssMap[parentSelector] || [];
        elementsToToggle.forEach(selector => {
            const elem = document.querySelector(selector);
            if (elem) {
                elem.classList.toggle('opcion-no-visible');  // Alternar la clase 'opcion-no-visible'
            }
        });
    }

    // Asignar el evento de click a cada clave del cssMap
    Object.keys(cssMap).forEach(parentSelector => {
        const parentElem = document.querySelector(parentSelector);  // Seleccionamos el englobador
        const parentElemClick = parentElem.querySelector("div > div > div.label-container-display > div > div > label");  // Elemento de clic (label)
        const checkboxElemento = parentElem.querySelector("div > div > div.label-container-display > div > div > span");

        if (parentElem && parentElemClick) {
            let clickCount = 0;  // Contador de clics

            parentElemClick.addEventListener('click', (event) => {
                event.stopPropagation();  // Evitar que el clic se propague a otros manejadores

                clickCount++;  // Incrementar el contador de clics

                // Alternar visibilidad solo en cada segundo clic
                if (clickCount % 2 === 0) {
                    // Verificar si el englobador tiene la clase 'selected'
                    if (parentElem.classList.contains('selected')) {
                        // Alternar la visibilidad de los elementos englobados
                        toggleClassVisibility(parentSelector);
                    }
                }
            });

            checkboxElemento.addEventListener('click', (event) => {
                event.stopPropagation();  // Evitar que el clic se propague a otros manejadores

                clickCount++;  // Incrementar el contador de clics

                // Alternar visibilidad solo en cada segundo clic
                if (clickCount % 1 === 0) {
                    // Verificar si el englobador tiene la clase 'selected'
                    if (parentElem.classList.contains('selected')) {
                        // Alternar la visibilidad de los elementos englobados
                        toggleClassVisibility(parentSelector);
                    }
                }
            });
        }
    });
});
*/