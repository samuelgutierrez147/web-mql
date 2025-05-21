<?php

if (!function_exists('etchy_child_theme_enqueue_scripts')) {
    /**
     * Function that enqueue theme's child style
     */
    function etchy_child_theme_enqueue_scripts()
    {
        $main_style = 'etchy-main';

        wp_enqueue_style('etchy-child-style', get_stylesheet_directory_uri() . '/style.css', array($main_style));
    }

    add_action('wp_enqueue_scripts', 'etchy_child_theme_enqueue_scripts');
}

// Incluir Bootstrap CSS
function bootstrap_css()
{
    wp_enqueue_style('bootstrap_css',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css',
        array(),
        '4.1.3'
    );
}

add_action('wp_enqueue_scripts', 'bootstrap_css');


// Incluir Bootstrap JS y dependencia popper
function bootstrap_js()
{
    wp_enqueue_script('popper_js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
        array(),
        '1.14.3',
        true);
    wp_enqueue_script('bootstrap_js',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js',
        array('jquery', 'popper_js'),
        '4.1.3',
        true);
}

add_action('wp_enqueue_scripts', 'bootstrap_js');

function enqueue_custom_script_for_products()
{
    if (is_product()) {
        wp_enqueue_script(
            'presupuesto-custom-js', // Handle del script
            get_stylesheet_directory_uri() . '/js/presupuesto-custom.js', // URL del script
            array(),
            null,
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'enqueue_custom_script_for_products');

add_action('wp_enqueue_scripts', 'enqueue_tabla_js');
function enqueue_tabla_js()
{
    if (is_product()) { // Solo en páginas de producto
        wp_enqueue_script('tabla-precios-js', get_stylesheet_directory_uri() . '/js/table-precios.js', ['jquery'], null, true);

        // Localizar datos para el script
        wp_localize_script('tabla-precios-js', 'ajaxData', [
            'ajaxUrl' => admin_url('admin-ajax.php'), // URL dinámica para AJAX
        ]);
    }
}

function cargar_sweetalert()
{
    // Incluir el script de SweetAlert2
    wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', [], null, true);
    // Opcional: Incluir los estilos (normalmente ya viene estilizado por defecto)
    wp_enqueue_style('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css', [], null);
}

add_action('wp_enqueue_scripts', 'cargar_sweetalert');

function cargar_script_subida_archivos()
{
    if (is_page('subir-archivos')) {
        wp_enqueue_script('validacion-subida', get_stylesheet_directory_uri() . '/js/validacion-subida.js', array('jquery'), null, true);
        wp_localize_script('validacion-subida', 'ajaxurl', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}

add_action('wp_enqueue_scripts', 'cargar_script_subida_archivos');

function custom_loginlogo()
{
    echo "<style type='text/css'>
	h1 a {background-image: url(get_size_url().'/wp-content/webp-express/webp-images/uploads/2023/09/logo-masquelibros.png.webp') !important;    width: 300px !important;
		height: 80px !important;
		background-size: 270px 79px !important; }
	</style>";
}

add_action('login_head', 'custom_loginlogo');

function cmplz_custom_banner_file($path, $filename)
{
    if ($filename === 'cookiebanner.php') {
        error_log("change path to " . '/wp-content/themes/etchy-child/cookiebanner.php');
        return 'wp-content/themes/etchy-child/cookiebanner/cookiebanner.php';
    }
    return $path;
}

add_filter('cmplz_template_file', 'cmplz_custom_banner_file', 10, 2);

// * Eliminar comentario después de las notas
add_filter('comment_form_defaults', 'afn_custom_comment_form');
function afn_custom_comment_form($fields)
{
    $fields['comment_notes_after'] = ''; // Elimina comentario después de las notas
    return $fields;
}

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

/*Eliminar el marcaje schema de las páginas Categoria y Tienda*/
function wc_remove_product_schema_product_archive()
{
    remove_action('woocommerce_shop_loop', array(WC()->structured_data, 'generate_product_data'), 10, 0);
}

add_action('woocommerce_init', 'wc_remove_product_schema_product_archive');

function year_shortcode()
{
    return date_i18n('Y');
}

add_shortcode('year', 'year_shortcode');

function bbloomer_only_one_in_cart($passed)
{
    wc_empty_cart();
    return $passed;
}

add_filter('woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 9999);

function ocultar_section_en_pagina_61()
{
    if (is_page(61)) {
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                var section = document.getElementById('politica-de-custodia-home');
                if (section) {
                    section.style.display = 'block';
                }
            });
        </script>
        <?php
    }
}

add_action('wp_footer', 'ocultar_section_en_pagina_61');

// Añade código al principio del body
add_action('wp_body_open', 'scripts_comienzo_body');
function scripts_comienzo_body()
{
    ?>


    <?php
}

function remplazar_alts_vacios($content)
{
    $post = new DOMDocument();
    $post->loadHTML('<?xml encoding="utf-8" ?>' . $content);


    $images = $post->getElementsByTagName('img');
    // var_dump($images);
    // die();
    foreach ($images as $image) {


        if (empty($image->getAttribute('alt'))) {


            $src = $image->getAttribute('src');
            $alt = pathinfo($src, PATHINFO_FILENAME);
            // $alt .= '';
            $image->setAttribute('alt', $alt);

        }
    }

    return $post->saveHTML();
}

add_filter('the_content', 'remplazar_alts_vacios', 1000);

function bloggerpilot_gravatar_alt($bloggerpilotGravatar)
{
    if (have_comments()) {
        $alt = get_comment_author();
    } else {
        $alt = get_the_author_meta('display_name');
    }
    $bloggerpilotGravatar = str_replace('alt=\'\'', 'alt=\'Avatar para ' . $alt . '\'', $bloggerpilotGravatar);
    return $bloggerpilotGravatar;
}

add_filter('get_avatar', 'bloggerpilot_gravatar_alt');

function add_nofollow_enlaces($content)
{
    $post = new DOMDocument();
    libxml_use_internal_errors(true);
    $post->loadHTML('<?xml encoding="utf-8" ?>' . $content);
    libxml_clear_errors();
    $enlaces = $post->getElementsByTagName('a');


    foreach ($enlaces as $enlace) {
        if (!empty($enlace->getAttribute('href')) && ($enlace->getAttribute('href') != '#')) {
            $addNofollow = $enlace->getAttribute('href');
            $rel = $enlace->getAttribute('rel');
            if (!empty($rel)) {
                $rel .= ' nofollow';
            } else {
                $rel = 'nofollow';
            }
            if ((strpos($addNofollow, "twitter") !== false) ||
                (strpos($addNofollow, "instagram") !== false) ||
                (strpos($addNofollow, "youtube") !== false) ||
                (strpos($addNofollow, "legal") !== false) ||
                (strpos($addNofollow, "privacidad") !== false) ||
                (strpos($addNofollow, "cookies") !== false) ||
                (strpos($addNofollow, "facebook") !== false) ||
                (strpos($addNofollow, "whatsapp") !== false) ||
                (strpos($addNofollow, "mailto:") !== false) ||
                (strpos($addNofollow, "tel") !== false)
            ) {
                $enlace->setAttribute('rel', $rel);
            }
        }
    }
    $content = $post->saveHTML();
    return $content;
}

add_filter('the_content', 'add_nofollow_enlaces', 999);

function getDataOptimusToProcessOrder($yith_wapo_data)
{
    $transformateData = transformateDataToErp($yith_wapo_data);
    return $transformateData['data_optimus'];
}

function handle_tabla_precios_controller()
{
    if (!WC()->cart) {
        wp_send_json_error(array('message' => 'No se ha encontrado el carrito.'));
    }
    /*CAMPOS A TENER EN CUENTA
        - alto_ancho_personalizado
        - 0e_tipo_papel
        - 2e_cub_ab
        - estampados cubierta (sin id) 4e_acabados_check
        - 6e_papelap_2caras (papel aportado cliente y 2 caras de marcapaginas)
        - 8e_cabezadas_cinta (cabezas y cinta separadora encuadernacion)
        - retractilado_ensobrado
        - encajado_entrega
    */

    $dataToDb = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (isset($dataToDb['yith_wapo']) && is_array($dataToDb['yith_wapo'])) {
        $yith_wapo_data = $dataToDb['yith_wapo'];
    } else {
        $yith_wapo_data = [];
    }

    $current_user = wp_get_current_user();
    $codOptimus = $current_user->api_id;
    //[MQL] - FECHA ESTIMADA
    $fechaEstimada = getFechaEstimada();
    $dataOptimus = getDataOptimusToProcessOrder($yith_wapo_data);
    $priceRequest = getPricePresupuestoToOptimus($dataOptimus, $codOptimus, $fechaEstimada);

    // Cambiar
    $precio_calculado = reset($priceRequest)['price'];

    // CAMBIAR
    if ($precio_calculado > 0) {
        WC()->session->set('precio_forzado', $precio_calculado);
    }

    echo json_encode($priceRequest);
    exit;
}

add_action('wp_ajax_tabla_precios_controller', 'handle_tabla_precios_controller');
add_action('wp_ajax_nopriv_tabla_precios_controller', 'handle_tabla_precios_controller');

add_filter('woocommerce_get_cart_item_from_session', 'apply_custom_price_for_cart_items', 10, 3);
function apply_custom_price_for_cart_items($cart_item, $values, $key)
{
    // Obtener el precio forzado almacenado en la sesión
    $precio_forzado = WC()->session->get('precio_forzado', 0); // Si no hay precio en la sesión, se devuelve 0

    // Verificar que el precio sea válido y mayor a 0
    if ($precio_forzado > 0) {
        // Modificar el precio del producto
        $cart_item['data']->set_price($precio_forzado);
    }

    return $cart_item;
}

//AGREGAR PRECIO FORZADO FUNCIONA
/*add_action('woocommerce_cart_calculate_fees', 'agregar_precio_forzado_al_carrito');
function agregar_precio_forzado_al_carrito() {
    // Verificar que WooCommerce y el carrito están disponibles
    if ( ! WC()->cart ) {
        return;
    }

    // Aquí calculas el precio de tu tabla (el precio forzado que quieres agregar)
    $precio_calculado = 100; // Este es un ejemplo de precio forzado, cámbialo según tu lógica

    // Añadir la tarifa al carrito
    // 'Precio Forzado' es el nombre de la tarifa y $precio_calculado es el valor
    WC()->cart->add_fee(__('Precio Forzado', 'tu-texto'), $precio_calculado, true, '');
}*/

add_action('woocommerce_table_mql_desglose', 'agregar_datos_personalizados_checkout');
function agregar_datos_personalizados_checkout()
{
    $cart_items = WC()->cart->get_cart();
    foreach ($cart_items as $cart_item) {
        // Verificar si el producto tiene opciones personalizadas de YITH
        if (isset($cart_item['yith_wapo_options']) && !empty($cart_item['yith_wapo_options'])) {
            $wapo_options = $cart_item['yith_wapo_options'];

            foreach ($wapo_options as $option) {
                foreach ($option as $key => $value) {
                    if ($key == '9e_ent' || $key == '9e_ent_00_zona' || $key == '9e_ent_00_dir')
                        continue;

                    if (!empty($value)) {
                        $key_modified = $key;

                        if ($value == 'NULL')
                            $value = 'No';

                        if (preg_match('/^\d+e_/', $key)) {
                            $key_modified = preg_replace('/^\d+e_/', '', $key);
                        }

                        if (strpos($key_modified, 'e_') === 0) {
                            $key_modified = substr($key_modified, 2); // Quitar el "e_" al inicio
                        }

                        if (strpos($key_modified, '_') !== false) {
                            $key_modified = str_replace('_', ' ', $key_modified);
                        }

                        if (strpos($value, '_') !== false) {
                            $value = str_replace('_', ' ', $value);
                        }

                        if ($key_modified == 'plast')
                            $key_modified = 'Plastificado';

                        if ($key_modified == 'quantity')
                            $key_modified = 'Cantidad';

                        if ($key_modified == 'encu')
                            $key_modified = 'Encuadernación';

                        if (preg_match('/tintas$/', $key_modified))
                            $value = getTintasByCode($value);

                        if ($key_modified == 'elem') {
                            echo '<tr class="custom-data-row title-row">
                                <th colspan="2">' . esc_html($value) . '</th>
                            </tr>';
                        } else {
                            echo '<tr class="custom-data-row">
                                <th>' . esc_html($key_modified) . '</th>
                                <td>
                                    <p>' . esc_html($value) . '</p>
                                </td>
                            </tr>';
                        }
                    }
                }
            }
        }
    }
}

/* PROVINCIAS EN PRODUCTOS */
add_action('wp_ajax_get_provincias', 'obtener_provincias');
add_action('wp_ajax_nopriv_get_provincias', 'obtener_provincias');
function obtener_provincias()
{
    global $wpdb;

    // Consultar la base de datos para obtener provincias
    $results = $wpdb->get_results("SELECT provincia, codigo FROM provincia", ARRAY_A);

    if ($results) {
        $provincias = array_map(function ($item) {
            return [
                'codigo' => esc_attr($item['provincia']),
                'nombre' => esc_html($item['codigo'])
            ];
        }, $results);

        wp_send_json_success($provincias);
    } else {
        wp_send_json_error('No se encontraron provincias.');
    }

    wp_die();
}

add_action('wp_footer', 'actualizar_select_provincias');
function actualizar_select_provincias()
{
    if (is_product()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                function cargarProvincias() {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {action: 'get_provincias'},
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                let selectField = $('select[name="yith_wapo[][9e_ent_00_zona]"]');

                                if (selectField.length > 0) {
                                    selectField.empty();
                                    selectField.append('<option value="">Selecciona una provincia</option>');

                                    $.each(response.data, function (index, provincia) {
                                        selectField.append('<option value="' + provincia.codigo + '">' + provincia.nombre + '</option>');
                                    });

                                    selectField.trigger('change'); // Forzar actualización de Select2 si está en uso
                                }
                            }
                        }
                    });
                }

                // Cargar provincias al cargar la página
                cargarProvincias();
            });
        </script>
        <?php
    }
}

/* PROVINCIAS EN PRODUCTOS */

/* PROVINCIAS EN CARRITO */
add_filter('woocommerce_checkout_fields', 'ocultar_campos_facturacion_checkout');
function ocultar_campos_facturacion_checkout($fields)
{
    if (isset($fields['billing'])) {
        // Eliminar Provincia
        unset($fields['billing']['billing_state']);

        // Eliminar Teléfono
        unset($fields['billing']['billing_phone']);

        // Eliminar Nombre de Empresa
        unset($fields['billing']['billing_company']);

        unset($fields['billing']['billing_address_2']);

        unset($fields['billing']['billing_country']);

        unset($fields['billing']['billing_phone']);
    }
    return $fields;
}

/* PROVINCIAS EN CARRITO */

/* DIRECCIONES EN PRODUCTOS*/
add_action('wp_ajax_get_user_addresses', 'obtener_direcciones_usuario');
add_action('wp_ajax_nopriv_get_user_addresses', '__return_false');

function obtener_direcciones_usuario()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Usuario no logueado.']);
        wp_die();
    }

    $user_id = get_current_user_id();
    $addresses = [];

    // Buscar direcciones almacenadas en la base de datos (para múltiples direcciones)
    $user_meta = get_user_meta($user_id);
    foreach ($user_meta as $key => $value) {
        if (strpos($key, '_address') !== false) {
            $id = str_replace('_address', '', $key);
            $ciudad = get_user_meta($user_id, $id . '_city', true);
            $codigo_postal = get_user_meta($user_id, $id . '_postal_code', true);
            $cod_optimus_dir = get_user_meta($user_id, $id . '_direccion_optimus', true);
            $direccion = $value[0];

            if (!empty($direccion) && !empty($ciudad) && !empty($codigo_postal)) {
                $addresses[] = [
                    'id' => $cod_optimus_dir,
                    'label' => esc_html($direccion . ', ' . $ciudad . ' (' . $codigo_postal . ')'),
                    'address' => esc_html($direccion),
                    'city' => esc_html($ciudad),
                    'postal_code' => esc_html($codigo_postal)
                ];
            }
        }
    }

    if (empty($addresses)) {
        wp_send_json_success(['no_address' => true]);
    } else {
        wp_send_json_success(['addresses' => $addresses]);
    }

    wp_die();
}

add_action('wp_footer', 'insertar_formulario_direccion_en_checkout');
function insertar_formulario_direccion_en_checkout()
{
    if (is_product()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                function cargarDirecciones() {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {action: 'get_user_addresses'},
                        dataType: 'json',
                        success: function (response) {
                            let selectField = $('select[name="yith_wapo[][9e_ent_00_dir]"]');

                            if (selectField.length > 0) {
                                if ($('#direccion-form-container').length === 0) {
                                    $('<div id="direccion-form-container" style="margin-top: 30px; padding: 15px; border: 1px solid #ccc; background: #f9f9f9;">' +
                                        '<h4>Añadir Nueva Dirección</h4>' +
                                        '<input type="text" id="nueva_direccion" placeholder="Dirección" style="width: 100%; padding: 8px; margin-bottom: 10px;">' +
                                        '<select id="nueva_ciudad" style="width: 100%; padding: 8px; margin-bottom: 10px;">' +
                                        '<option value="">Selecciona una provincia</option>' +
                                        '</select>' +
                                        '<input type="text" id="nuevo_codigo_postal" placeholder="Código Postal" style="width: 100%; padding: 8px; margin-bottom: 10px;">' +
                                        '<button id="guardar-direccion" style="background: #0073aa; color: white; padding: 8px; border: none;">Guardar Dirección</button>' +
                                        '</div>').insertAfter(selectField);

                                    cargarProvincias();
                                }

                                function cargarProvincias() {
                                    $.ajax({
                                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                        type: 'POST',
                                        data: {action: 'get_provincias'},
                                        dataType: 'json',
                                        success: function (response) {
                                            if (response.success) {
                                                let selectCiudad = $('#nueva_ciudad');

                                                selectCiudad.empty();
                                                selectCiudad.append('<option value="">Selecciona una provincia</option>');

                                                $.each(response.data, function (index, provincia) {
                                                    selectCiudad.append('<option value="' + provincia.codigo + '">' + provincia.nombre + '</option>');
                                                });

                                                selectCiudad.trigger('change'); // Forzar actualización si usa Select2
                                            }
                                        }
                                    });
                                }

                                if (response.success && response.data.no_address) {
                                    selectField.hide();
                                } else {
                                    selectField.empty();
                                    selectField.append('<option value="">Selecciona una dirección</option>');

                                    $.each(response.data.addresses, function (index, address) {
                                        selectField.append('<option value="' + address.id + '">' + address.label + '</option>');
                                    });

                                    selectField.trigger('change');
                                    selectField.show();
                                }
                            }
                        }
                    });
                }

                cargarDirecciones();

                $(document).on('click', '#guardar-direccion', function (e) {
                    e.preventDefault();

                    let nuevaDireccion = $('#nueva_direccion').val().trim();
                    let nuevaCiudad = $('#nueva_ciudad').val().trim();
                    let nuevoCodigoPostal = $('#nuevo_codigo_postal').val().trim();

                    if (nuevaDireccion === '' || nuevaCiudad === '' || nuevoCodigoPostal === '') {
                        alert('Por favor, completa todos los campos.');
                        return;
                    }

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'guardar_nueva_direccion',
                            direccion: nuevaDireccion,
                            ciudad: nuevaCiudad,
                            codigo_postal: nuevoCodigoPostal
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                alert('Dirección guardada correctamente.');

                                let selectField = $('select[name="yith_wapo[][9e_ent_00_dir]"]');

                                // Agregar la nueva dirección al select y seleccionarla automáticamente
                                let nuevaOpcion = `<option value="${response.data.id}" selected>
                                    ${response.data.direccion}, ${response.data.ciudad} (${response.data.codigo_postal})
                                   </option>`;
                                selectField.append(nuevaOpcion);
                                selectField.trigger('change');

                                selectField.show();
                            } else {
                                alert('Error al guardar la dirección.');
                            }
                        }
                    });
                });
            });
        </script>
        <?php
    }
}

add_action('wp_ajax_guardar_nueva_direccion', 'guardar_nueva_direccion');
function guardar_nueva_direccion()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Debes estar logueado.']);
        wp_die();
    }

    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);
    $direccion = sanitize_text_field($_POST['direccion']);
    $ciudad = sanitize_text_field($_POST['ciudad']);
    $codigo_postal = sanitize_text_field($_POST['codigo_postal']);

    $data = readOptimusXml('addCustomerAddress');
    $data->name = $user_info->user_nicename;
    $data->customerCode = $user_info->api_id;
    $data->addressLine1 = $direccion;
    $data->addressLine2 = '';
    $data->addressLine3 = $ciudad;
    $data->postcode = $codigo_postal;
    $data->contact = $user_info->user_nicename;
    $data->email = $user_info->user_email;
    $data->phone = $user_info->phone_number;

    $data->isInvoice = 1;
    $data->isDelivery = 1;
    $data->isQuote = 1;
    $data->isLabel = 1;
    $data->isWebToPrint = 1;
    $curlResult = sendXmlOptimus($data->asXML(), 'customer/addCustomerAddress');
    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        $optimusCodeDir = $curlResult->addressNumber;
        if (empty($direccion) || empty($ciudad) || empty($codigo_postal)) {
            wp_send_json_error(['message' => 'Faltan datos.']);
            wp_die();
        }

        // Crear un identificador único basado en timestamp
        $direccion_id = 'shipping_' . time();

        // Guardar la dirección en la base de datos
        update_user_meta($user_id, $direccion_id . '_address', $direccion);
        update_user_meta($user_id, $direccion_id . '_city', $ciudad);
        update_user_meta($user_id, $direccion_id . '_postal_code', $codigo_postal);
        update_user_meta($user_id, $direccion_id . '_direccion_optimus', $optimusCodeDir);

        // Respuesta AJAX con datos correctos
        wp_send_json_success([
            'message' => 'Dirección guardada correctamente.',
            'success' => true,
            'id' => $optimusCodeDir,
            'direccion' => $direccion,
            'ciudad' => $ciudad,
            'codigo_postal' => $codigo_postal,
            'cod_optimus' => $optimusCodeDir
        ]);
    } else {
        wp_send_json_success([
            'message' => 'Hubo un error al procesar la dirección.',
            'success' => false
        ]);
    }
    wp_die();
}

/* DIRECCIONES EN PRODUCTOS */

//AL CARGAR EL FORMULARIO CHECKOUT FINAL
global $custom_price_request;
$custom_price_request = null;

function comprobar_billing_state_en_checkout()
{
    $current_user = wp_get_current_user();
    $codOptimus = $current_user->api_id;
    $cart_items = WC()->cart->get_cart();
    $dataToDb = [];
    foreach ($cart_items as $cart_item) {
        // Verificar si el producto tiene opciones personalizadas de YITH
        if (isset($cart_item['yith_wapo_options']) && !empty($cart_item['yith_wapo_options'])) {
            $dataToDb = $cart_item['yith_wapo_options'];
        }
    }

    $customer_billing_state = WC()->customer->get_billing_state();

    if ($customer_billing_state != '') {
        $dataToDb[] = ['e_ent' => 1];
        $dataToDb[] = ['e_ent_00_zona' => $customer_billing_state];
        $transformateData = transformateDataToErp($dataToDb);
        $dataOptimus = $transformateData['data_optimus'];
        $fechaEstimada = getFechaEstimada();
        $custom_price_request = getPricePresupuestoToOptimus($dataOptimus, $codOptimus, $fechaEstimada);

        //CAMBIAR
        $precio_calculado = reset($custom_price_request)['price'];
        $cart_subtotal = WC()->cart->get_subtotal();
        $nuevo_subtotal = $cart_subtotal + $precio_calculado;

        // Establecer el nuevo subtotal
        WC()->cart->set_subtotal($nuevo_subtotal);

        // Calcular el nuevo total, si es necesario
        $cart_total = WC()->cart->get_total('edit');
        $nuevo_total = $cart_total + $precio_calculado;

        // Establecer el nuevo total
        WC()->cart->set_total($nuevo_total);

        // Actualizar el carrito para reflejar los cambios
        WC()->cart->calculate_totals();
    }
}

add_action('woocommerce_before_checkout_form', 'comprobar_billing_state_en_checkout');

/*
add_filter('woocommerce_cart_totals_subtotal_html', 'modificar_subtotal_con_price_request', 10, 1);
function modificar_subtotal_con_price_request($subtotal_html) {
    global $custom_price_request;

    error_log('modificar_subtotal_con_price_request ejecutado'); // Depuración

    if ($custom_price_request) {
        $nuevo_subtotal = wc_price($custom_price_request);
        error_log('Nuevo Subtotal: ' . $nuevo_subtotal); // Depuración
        $subtotal_html = '<span class="custom-price-request">' . esc_html__('Custom Price:', 'tu-texto') . ' ' . $nuevo_subtotal . '</span>';
    }

    return $subtotal_html;
}*/

// añadir campos de facturación con direccion correcta
function obtener_identificador_direccion($user_id, $direccion_optimus)
{
    $user_meta = get_user_meta($user_id);

    foreach ($user_meta as $key => $value) {
        // Si la clave contiene '_direccion_optimus' y el valor coincide con `direccion_optimus`
        if (strpos($key, '_direccion_optimus') !== false && $value[0] == $direccion_optimus) {
            // Extraer el identificador base eliminando '_direccion_optimus'
            return str_replace('_direccion_optimus', '', $key);
        }
    }

    return null; // Si no se encuentra el identificador
}

add_filter('woocommerce_checkout_fields', 'autocompletar_detalles_facturacion_desde_carrito', 20, 1);
function autocompletar_detalles_facturacion_desde_carrito($fields)
{
    if (!is_user_logged_in()) {
        return $fields;
    }

    $user_id = get_current_user_id();
    $direccion_optimus = null;

    // 🔍 Buscar `9e_ent_00_dir` en el carrito
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (isset($cart_item['yith_wapo_options'])) {
            foreach ($cart_item['yith_wapo_options'] as $option) {
                if (isset($option['9e_ent_00_dir'])) {
                    $direccion_optimus = $option['9e_ent_00_dir'];
                    break 2; // Salimos del bucle
                }
            }
        }
    }

    if ($direccion_optimus) {
        // 🏠 Obtener el identificador de la dirección en base al `direccion_optimus`
        $identificador_direccion = obtener_identificador_direccion($user_id, $direccion_optimus);

        if ($identificador_direccion) {
            // 🛠 Obtener los valores de dirección
            $direccion = get_user_meta($user_id, "{$identificador_direccion}_address", true);
            $ciudad = get_user_meta($user_id, "{$identificador_direccion}_city", true);
            $codigo_postal = get_user_meta($user_id, "{$identificador_direccion}_postal_code", true);
            $telefono = get_user_meta($user_id, "billing_phone", true);

            // 📌 Si no hay teléfono, buscar en otro campo alternativo
            if (empty($telefono)) {
                $telefono = get_user_meta($user_id, "phone_number", true);
            }

            // 🔄 Forzar los valores en los campos de facturación
            $fields['billing']['billing_address_1']['default'] = $direccion ?: '';
            $fields['billing']['billing_city']['default'] = $ciudad ?: '';
            $fields['billing']['billing_postcode']['default'] = $codigo_postal ?: '';
            $fields['billing']['billing_phone']['default'] = $telefono ?: '';

            // 🔄 Forzar la actualización de los valores en el frontend
            $_POST['billing_address_1'] = $direccion ?: '';
            $_POST['billing_city'] = $ciudad ?: '';
            $_POST['billing_postcode'] = $codigo_postal ?: '';
            $_POST['billing_phone'] = $telefono ?: '';
        }
    }

    return $fields;
}


function obtener_yith_wapo_del_carrito()
{
    $cart_items = WC()->cart->get_cart();
    foreach ($cart_items as $cart_item) {
        // Verificar si el producto tiene opciones personalizadas de YITH
        if (isset($cart_item['yith_wapo_options']) && !empty($cart_item['yith_wapo_options'])) {
            return $cart_item['yith_wapo_options'];
        }
    }
    return null;
}

add_action('woocommerce_checkout_update_order_meta', 'cambiar_estado_y_asignar_codigo_pedido', 10, 1);
function cambiar_estado_y_asignar_codigo_pedido($order_id)
{
    if (!$order_id) {
        return;
    }

    $order = new WC_Order($order_id);
    $dataDb = getDataOptimusToProcessOrder(obtener_yith_wapo_del_carrito());
    $fechaEstimada = getFechaEstimada();
    $total = $order->get_total();

    // Procesar pedido en Optimus
    $datosPedidoOptimus = addPresupuestoToOptimus($dataDb, $fechaEstimada, $total);

    if (!$datosPedidoOptimus['success']) {
        $order->update_status('failed', __('Error en Optimus: ' . $datosPedidoOptimus['error_message'], 'woocommerce'));
        $order->add_order_note(__('Error en Optimus: ' . $datosPedidoOptimus['error_message'], 'woocommerce'));

        // Notificar por correo
        wc_mail(
            get_option('admin_email'),
            __('Error en pedido WooCommerce', 'woocommerce'),
            'El pedido #' . $order_id . ' no pudo ser procesado en Optimus. Motivo: ' . $datosPedidoOptimus['error_message']
        );
        return;
    }

    // Guardar en metadatos
    update_post_meta($order_id, '_optimus_enq_number', sanitize_text_field($datosPedidoOptimus['enq_number']));
    update_post_meta($order_id, '_optimus_cod_pedido', sanitize_text_field($datosPedidoOptimus['cod_pedido_optimus']));

    // Agregar notas al pedido
    $order->add_order_note('Pedido enviado a Optimus.');
    $order->add_order_note('ENQ Number: ' . $datosPedidoOptimus['enq_number']);
    $order->add_order_note('COD Pedido Optimus: ' . $datosPedidoOptimus['cod_pedido_optimus']);

    // Filtro para cambiar el número de pedido
    add_filter('woocommerce_order_number', function ($order_number, $order) use ($order_id) {
        return get_post_meta($order_id, '_optimus_cod_pedido', true) ?: $order_number;
    }, 10, 2);
}

function redirect_non_logged_users_to_login()
{
    if (!is_user_logged_in() && !is_admin()) {
        $login_page_url = get_site_url() . '/iniciar-sesion/';
        wp_redirect($login_page_url);
        exit;
    }
}

add_action('woocommerce_before_shop_loop', 'redirect_non_logged_users_to_login');
add_action('woocommerce_before_single_product', 'redirect_non_logged_users_to_login');

//CODIGO MQL
function getConfigUrlOptimus($optimusUri)
{
    $urlBase = "http://81.42.209.224:8080/optwebsvcs/";
    $configDbOptimus = "pruebas";
    return $urlBase . $optimusUri . '?db=' . $configDbOptimus;
}

function sendXmlOptimus($xml, $optimusUri, $type = 'POST', $params = false, $xmlOption = false)
{
    //The URL that you want to send your XML to.
    $url = getConfigUrlOptimus($optimusUri);
    if ($type == 'POST') {
        //Initiate cURL
        if ($xml) {
            $curl = curl_init($url);
            //Set the Content-Type to text/xml.
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

            //Set CURLOPT_POST to true to send a POST request.
            curl_setopt($curl, CURLOPT_POST, true);

            //Attach the XML string to the body of our request.
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

            //Tell cURL that we want the response to be returned as
            //a string instead of being dumped to the output.
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //Execute the POST request and send our XML.
            $result = curl_exec($curl);

            //Do some basic error checking.
            if (curl_errno($curl)) {
                throw new Exception(curl_error($curl));
            }

            //Close the cURL handle.
            curl_close($curl);

            $xml = simplexml_load_string($result);
            //Print out the response output.
            if ($xmlOption) {
                return $xml;
            } else {
                $json_convert = json_encode($xml);
                return json_decode($json_convert);
            }
        } else {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,
                $params);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($curl);

            curl_close($curl);
            //RESPUESTA SIEMPRE TRUE
            return true;
        }
    } elseif ($type == 'GET') {
        return sendCurl($url, 'GET', $params);
    }

    return true;
}

function sendCurl($url, $type = 'GET', $params = false, $authToken = false, $format = false, $separator = false)
{
    $data = [];
    if ($type == 'GET') {
        //Abrimos conexión cURL y la almacenamos en la variable $ch.
        $ch = curl_init();

        if ($params)
            $url .= $params;

        if ($authToken)
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $authToken
            ));

        //Configuramos mediante CURLOPT_URL la URL de nuestra API
        curl_setopt($ch, CURLOPT_URL, $url);

        //Abrimos conexión cURL y la almacenamos en la variable $ch.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 0 o 1, indicamos que no queremos al Header en nuestra respuesta
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //Ejecuta la petición HTTP y almacena la respuesta en la variable $data.
        $data = curl_exec($ch);

        if ($format == 'json') {
            if (gettype($data) == 'string') {
                $data = utf8_encode($data);
                $data = explode($separator, $data);
            }
            $data = array_values($data);
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        //Cerramos la conexión cURL
        curl_close($ch);

    } elseif ($type == 'POST') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,
            $params);

        $curlHeader = [
            ($authToken) ? 'Authorization: Bearer ' . $authToken : '',
            ($format) ? 'Content-Type:application/' . $format : ''
        ];

        if (!empty($curlHeader)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                $curlHeader
            );
        }

        //REVISAR
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        curl_close($curl);
    }
    return $data;
}

function readOptimusXml($xmlName)
{
    // Obtén la ruta de la carpeta xml dentro del tema activo
    $dirXmlFiles = get_stylesheet_directory() . '/xml/';

    // Construye la ruta completa del archivo XML
    $xmlFileRoute = $dirXmlFiles . $xmlName . '.xml';

    // Verifica si el archivo existe
    if (file_exists($xmlFileRoute)) {
        // Lee el contenido del archivo
        $xmlFile = file_get_contents($xmlFileRoute);

        // Carga el XML como un objeto SimpleXML
        $xml = simplexml_load_string($xmlFile);

        // Si la carga fue exitosa, devuelve el objeto SimpleXML
        if ($xml !== false) {
            return $xml;
        } else {
            // Manejo de error si el XML es inválido
            error_log("Error: No se pudo cargar el archivo XML: $xmlFileRoute");
            return null;
        }
    } else {
        // Manejo de error si el archivo no existe
        error_log("Error: El archivo XML no existe: $xmlFileRoute");
        return null;
    }
}

function getLastOptimusCode()
{
    $data = readOptimusXml('nextCustomerCode');
    $curlResult = sendXmlOptimus($data->asXML(), 'customer/nextCustomerCode');
    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        return $curlResult->customerCode;
    }
    return false;
}

function addUserToOptimus($dataDb, $nextCustomerCode)
{
    $data = readOptimusXml('addCustomer');
    $data->customerCode = $nextCustomerCode;
    $data->companyName = $dataDb['name'];
    $data->priceCategory = 'WEB';

    $data->classificationCode = 'OTROS';
    $data->taxReference = $dataDb['cif'];
    $data->paymentCondition = 'TRC00';
    if (isset($dataDb['payment_type']) && $dataDb['payment_type'] == 'transferencia')
        $data->paymentCondition = 'TRF00';

    $curlResult = sendXmlOptimus($data->asXML(), 'customer/addCustomer');
    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        return [
            'status' => 'success',
            'message' => ''
        ];
    }

    return [
        'status' => 'error',
        'message' => $curlResult->error
    ];
}

function getCodTipoPedido($encu, $tipoImpresionInterior)
{
    $codTipoPedido = ($tipoImpresionInterior == 'INKJET') ? 'INK_' : 'TON_';
    switch ($encu) {
        case 'RUSTICA_PUR':
            $codTipoPedido .= 'PUR';
            break;
        case 'RUSTICA_COSIDA':
            $codTipoPedido .= 'RSCOSI';
            break;
        case 'GRAPA':
            $codTipoPedido .= 'GRAPA';
            break;
        case 'ESPIRAL':
            $codTipoPedido .= 'ESPPLA';
            break;
        case 'WIREO':
            $codTipoPedido .= 'WIRE-O';
            break;
        case 'TAPA_DURA_PUR':
            $codTipoPedido .= 'TD';
            break;
        case 'TAPA_DURA_COSI':
            $codTipoPedido .= 'TDCOS';
            break;
        case 'TAPA_DURA_ESPIRAL':
            $codTipoPedido .= 'TD_ES';
            break;
        case 'TAPA_DURA_WIRE-O':
            $codTipoPedido .= 'TD_WI';
            break;
    }
    return $codTipoPedido;
}

function getTintasByCode($tinta)
{
    $codigoTinta = '';

    if ($tinta == 'CL_CA') {
        $codigoTinta = '4 / 0';
    } elseif ($tinta == 'CLCA_BNDO') {
        $codigoTinta = '4 / 1';
    } elseif ($tinta == 'CL_CADO') {
        $codigoTinta = '4 / 4';
    } elseif ($tinta == 'BN_CADO') {
        $codigoTinta = '1 / 1';
    } elseif ($tinta == 'BNMG_CADO') {
        $codigoTinta = '2 / 2';
    }

    return $codigoTinta;
}

function addPresupuestoToOptimus($dataPresupuesto, $fechaEstimada, $settedPrice)
{
    if (!isset($dataPresupuesto['productVariable']))
        return false;

    $userData = wp_get_current_user();
    $data = readOptimusXml('enqbuilder-request');
    $cantidadFinal = 0;

    $jobVariableKeys = array_keys($dataPresupuesto['productVariable']);
    $tipos = [];
    foreach ($jobVariableKeys as $key => $jobVariableKey) {
        if (strpos($jobVariableKey, 'e_tipo_imp') !== false) {
            $array = explode('e_tipo_imp', $jobVariableKey);
            $tipos[] = reset($array);
        }
    }


    $data->emailAddress = $userData->user_email;
    $data->customerCode = $userData->api_id;
    $data->addressNumber = ($dataPresupuesto['productVariable']['9e_ent_00_dir']) ?? 1;

    $data->jobVariable->name = 'ep_fecha_entrega';
    $data->jobVariable->type = 'datetime';
    if ($fechaEstimada)
        $data->jobVariable->value = $fechaEstimada . 'T' . date('H:i');

    $encuadernacion = $dataPresupuesto['jobVariable']['e_encu'];
    $tipoImpresionInterior = $dataPresupuesto['productVariable']['0e_tipo_imp'] ?? $dataPresupuesto['productVariable']['2e_tipo_imp'];

    $tipoPedido = getCodTipoPedido($encuadernacion, $tipoImpresionInterior);
    $data->jobVariable[1]->value = $tipoPedido;

    $data->jobVariable[2]->value = $dataPresupuesto['jobVariable']['titulo'];
    if (isset($dataPresupuesto['jobVariable']['titulo_2'])) {
        $data->jobVariable[3]->value = $dataPresupuesto['jobVariable']['titulo_2'];
        unset($dataPresupuesto['jobVariable']['titulo_2']);
    }

    if (isset($dataPresupuesto['jobVariable']['isbn']))
        $data->jobVariable[4]->value = $dataPresupuesto['jobVariable']['isbn'];

    $data->jobVariable[6]->value = ($dataPresupuesto['jobVariable']['ep_tipo_iva']) ?? '';

    //se cambia a 2 para poder coger el ep_titulo
    $countJV = 0;
    $countPV = 0;

    $data->line->description = $dataPresupuesto['productVariable']['titulo_elemento'] ?? $dataPresupuesto['jobVariable']['titulo'];
    unset($dataPresupuesto['jobVariable']['titulo']);
    unset($dataPresupuesto['jobVariable']['ep_tipo_iva']);
    unset($dataPresupuesto['jobVariable']['user_id']);
    $dataPresupuesto['jobVariable']['e_ancho'] = $dataPresupuesto['jobVariable']['ancho_mm'];
    $dataPresupuesto['jobVariable']['e_alto'] = $dataPresupuesto['jobVariable']['alto_mm'];
    unset($dataPresupuesto['jobVariable']['ancho_mm']);
    unset($dataPresupuesto['jobVariable']['alto_mm']);

    $data->line->productCode = 'GENERICO';
    $data->line->includeInQuote = true;

    $data->line->productVariable[$countPV]->name = 'e_elem_mod';
    $data->line->productVariable[$countPV]->type = 'integer';
    $data->line->productVariable[$countPV]->value = 1;
    $countPV++;

    foreach ($tipos as $tipo) {
        $data->line->productVariable[$countPV]->name = $tipo . 'e_elem';
        $data->line->productVariable[$countPV]->type = 'integer';
        $data->line->productVariable[$countPV]->value = 1;
        $countPV++;
    }

    foreach ($dataPresupuesto as $keyType => $valueData) {
        foreach ($valueData as $key => $value) {
            $key = str_replace('9', '', $key);
            $typeValue = 'string';
            switch (gettype($value)) {
                case 'double':
                    if (is_float($value))
                        $typeValue = 'decimal';
                    else
                        $typeValue = 'integer';
                    break;
                case 'boolean':
                    $typeValue = 'boolean';
                    break;
                default:
                    break;
            }
            if ($key == 'e_ancho' || $key == 'e_alto' || strpos($key, '_paginas') !== false) {
                $typeValue = 'integer';
            }
            if ($keyType == 'jobVariable' || $keyType == 'productVariable') {
                if (is_numeric($key[0]) && !in_array($key[0], $tipos))
                    continue;

                if (is_bool($value)) {
                    $value = intval($value);
                }
                $data->line->productVariable[$countPV]->name = $key;
                $data->line->productVariable[$countPV]->type = $typeValue;
                $data->line->productVariable[$countPV]->value = $value;
                $countPV++;
            } elseif ($keyType == 'quantity') {
                sort($value);
                $value = array_values(array_filter($value));
                foreach ($value as $keyQ => $quantity) {
                    if ($quantity > 0) {
                        $data->line->quantity[$keyQ] = $quantity;
                        $cantidadFinal = $quantity;

                        $data->line->productVariable[$countPV]->name = 'e_prc_esp';
                        $data->line->productVariable[$countPV]->type = 'boolean';
                        $data->line->productVariable[$countPV]->value = 1;
                        $countPV++;

                        $data->line->productVariable[$countPV]->name = 'e_prc_esp_' . ($keyQ + 1);
                        $data->line->productVariable[$countPV]->type = 'decimal';
                        $data->line->productVariable[$countPV]->value = $settedPrice;
                        $countPV++;

                    }
                }
            }
        }
    }

    /*echo header("Content-type: text/xml");
    echo $data->asXML();
    exit;*/

    $dataXml = $data->asXML();
    $curlResult = sendXmlOptimus($data->asXML(), 'enqbuilder');
    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        $dataPres = [
            'isbn' => $dataPresupuesto['jobVariable']['ep_isbn'] ?? '',
            'enq_number' => $curlResult->enqNumber,

        ];
        $codOptimus = markSuccessful($cantidadFinal, $fechaEstimada, $dataPres);
        if ($codOptimus['success']) {
            return [
                'success' => true,
                'enq_number' => $curlResult->enqNumber,
                'cod_pedido_optimus' => $codOptimus['cod_pedido_optimus']
            ];
        } else {
            return ['success' => false, 'type' => 'pedido', 'error_message' => $codOptimus['mensaje']];
        }
    }
    return ['success' => false, 'type' => 'oferta', 'error_message' => $curlResult->error];
}

//MQL - CAMPOS NUEVOS DE REGISTRO
add_action('woocommerce_register_form', 'add_custom_fields_to_registration_form');
function add_custom_fields_to_registration_form()
{
    ?>
    <p class="form-row form-row-wide">
        <label for="name"><?php esc_html_e('Name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" required class="input-text" name="name" id="name"
               value="<?php echo esc_attr(!empty($_POST['name']) ? $_POST['name'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="cif"><?php esc_html_e('CIF', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" required class="input-text" name="cif" id="cif"
               value="<?php echo esc_attr(!empty($_POST['cif']) ? $_POST['cif'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="phone_number"><?php esc_html_e('Phone Number', 'woocommerce'); ?> <span
                    class="required">*</span></label>
        <input type="text" required class="input-text" name="phone_number" id="phone_number"
               value="<?php echo esc_attr(!empty($_POST['phone_number']) ? $_POST['phone_number'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="payment_type"><?php esc_html_e('Payment Methods', 'woocommerce'); ?> <span
                    class="required">*</span></label>
        <select name="payment_type" required id="payment_type" class="input-text">
            <option value="transferencia"><?php esc_html_e('Transferencia', 'woocommerce'); ?></option>
            <option value="tarjeta_credito"><?php esc_html_e('Tarjeta de crédito', 'woocommerce'); ?></option>
        </select>
    </p>
    <?php
}

// Añadir los campos personalizados en el perfil del usuario
add_action('show_user_profile', 'add_custom_fields_to_user_profile');
add_action('edit_user_profile', 'add_custom_fields_to_user_profile');

function add_custom_fields_to_user_profile($user)
{
    ?>
    <h3><?php esc_html_e('Información adicional 2', 'woocommerce'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="name"><?php esc_html_e('Nombre', 'woocommerce'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="name" id="name" required
                       value="<?php echo esc_attr(get_the_author_meta('name', $user->ID)); ?>" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="cif"><?php esc_html_e('CIF', 'woocommerce'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="cif" id="cif" required
                       value="<?php echo esc_attr(get_the_author_meta('cif', $user->ID)); ?>" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="phone_number"><?php esc_html_e('Número de teléfono', 'woocommerce'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="phone_number" id="phone_number" required
                       value="<?php echo esc_attr(get_the_author_meta('phone_number', $user->ID)); ?>"
                       class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="payment_type"><?php esc_html_e('Método de pago', 'woocommerce'); ?><span
                            class="required">*</span></label></th>
            <td>
                <select name="payment_type" id="payment_type" required>
                    <option value=""><?php esc_html_e('Selecciona un método de pago', 'woocommerce'); ?></option>
                    <option value="transferencia" <?php selected(get_the_author_meta('payment_type', $user->ID), 'transferencia'); ?>><?php esc_html_e('Transferencia', 'woocommerce'); ?></option>
                    <option value="tarjeta_credito" <?php selected(get_the_author_meta('payment_type', $user->ID), 'tarjeta_credito'); ?>><?php esc_html_e('Tarjeta de crédito', 'woocommerce'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Guardar los campos personalizados al actualizar el perfil del usuario
add_action('personal_options_update', 'save_custom_fields_to_user_profile');
add_action('edit_user_profile_update', 'save_custom_fields_to_user_profile');
function save_custom_fields_to_user_profile($user_id)
{
    // Verificar si el usuario tiene permisos para editar su perfil
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // Guardar los campos en la tabla wp_users
    global $wpdb;

    $name = sanitize_text_field($_POST['name']);
    $cif = sanitize_text_field($_POST['cif']);
    $phoneNumber = sanitize_text_field($_POST['phone_number']);
    $paymentType = sanitize_text_field($_POST['payment_type']);

    // Actualizar la tabla wp_users
    $wpdb->update(
        'wp_users',
        [
            'name' => $name,
            'cif' => $cif,
            'phone_number' => $phoneNumber,
            'payment_type' => $paymentType
        ],
        [
            'ID' => $user_id
        ]
    );
}

// Añadir los campos personalizados a la página de "Editar Cuenta" de WooCommerce
add_action('woocommerce_edit_account_form', 'add_custom_fields_to_edit_account_form');
function add_custom_fields_to_edit_account_form()
{
    // Obtiene los datos del usuario actual
    $user_id = get_current_user_id();
    $dataUser = get_userdata($user_id);
    $name = $dataUser->name;
    $cif = $dataUser->cif;
    $phoneNumber = $dataUser->phone_number;
    $paymentType = $dataUser->payment_type;

    ?>
    <h3><?php esc_html_e('Información adicional', 'woocommerce'); ?></h3>
    <p class="form-row form-row-wide">
        <label for="name"><?php esc_html_e('Nombre', 'woocommerce'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" name="name" required id="name" value="<?php echo esc_attr($name); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="cif"><?php esc_html_e('CIF', 'woocommerce'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" required name="cif" id="cif" value="<?php echo esc_attr($cif); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="phone_number"><?php esc_html_e('Número de teléfono', 'woocommerce'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" required name="phone_number" id="phone_number"
               value="<?php echo esc_attr($phoneNumber); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="payment_type"><?php esc_html_e('Método de pago', 'woocommerce'); ?><span
                    class="required">*</span></label>
        <select name="payment_type" id="payment_type" required class="select">
            <option value=""><?php esc_html_e('Selecciona un método de pago', 'woocommerce'); ?></option>
            <option value="transferencia" <?php selected($paymentType, 'transferencia'); ?>><?php esc_html_e('Transferencia', 'woocommerce'); ?></option>
            <option value="tarjeta_credito" <?php selected($paymentType, 'tarjeta_credito'); ?>><?php esc_html_e('Tarjeta de crédito', 'woocommerce'); ?></option>
        </select>
    </p>
    <?php
}

// Guardar los campos personalizados directamente en la tabla wp_users
add_action('woocommerce_save_account_details', 'save_custom_fields_to_wp_users', 12, 1);
function save_custom_fields_to_wp_users($user_id)
{
    global $wpdb;
    $wpdb->update(
        'wp_users',
        [
            'name' => sanitize_text_field($_POST['name']) ?? '',
            'cif' => sanitize_text_field($_POST['cif']) ?? '',
            'phone_number' => sanitize_text_field($_POST['phone_number']) ?? '',
            'payment_type' => sanitize_text_field($_POST['payment_type']) ?? '',
        ],
        [
            'ID' => $user_id
        ]
    );
}

add_action('user_register', 'crear_usuario');
function crear_usuario($user_id)
{
    global $wpdb;
    //MQL - OBTENER NEXT CUSTOMER CODE
    $nextCustomerCode = getLastOptimusCode();

    $wpdb->update(
        'wp_users',
        [
            'api_id' => $nextCustomerCode,
            'name' => sanitize_text_field($_POST['name']) ?? '',
            'cif' => sanitize_text_field($_POST['cif']) ?? '',
            'payment_type' => sanitize_text_field($_POST['payment_type']) ?? '',
            'phone_number' => sanitize_text_field($_POST['phone_number']) ?? ''
        ],
        [
            'ID' => $user_id
        ]
    );

    $userData = get_userdata($user_id);

    //MQL - CREAMOS CLIENTE EN OPTIMUS
    $optimusResponse = addUserToOptimus($_POST, $nextCustomerCode);
    if ($optimusResponse['status'] == 'success') {
        // Si se creó correctamente el cliente en Optimus, redirigimos al perfil del usuario
        wp_redirect(get_edit_user_link($user_id));
    } else {
        $login_url = home_url('/iniciar-sesion/');
        wc_add_notice($optimusResponse['message'], 'error');
        wp_redirect($login_url);
        exit;
    }
}

add_action('wp_footer', 'add_div_before_my_account_widget');
function add_div_before_my_account_widget()
{
    // Solo ejecutar en la página de inicio de sesión (iniciar sesión de WooCommerce)
    if (is_account_page() && !is_user_logged_in()) {
        $error_message = '';

        // Verificar si el mensaje de error está presente en los parámetros GET
        if (isset($_GET['registration']) && $_GET['registration'] == 'failed') {
            // Sanitizar el mensaje y asignarlo
            $error_message = isset($_GET['message']) ? sanitize_text_field($_GET['message']) : 'Hubo un error durante el registro.';
        }

        // Convertir el mensaje en una cadena JavaScript válida
        $error_message_js = json_encode($error_message); // Esto lo convierte en una cadena que puede ser usada en JavaScript
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // Crear el div con el mensaje de error solo si hay un mensaje
                if (<?php echo $error_message_js; ?>) {
                    var newDiv = $('<div class="message-error">' + <?= $error_message_js; ?> + '</div>');

                    // Agregar el div antes del contenedor con la clase '.elementor-widget-woocommerce-my-account'
                    $('.elementor-widget-woocommerce-my-account').first().before(newDiv);
                }
            });
        </script>
        <?php
    }
}

//cambiamos menú de "mi cuenta"
add_filter('woocommerce_account_menu_items', function ($items) {
    unset($items['dashboard']); // Quita "Escritorio"
    unset($items['downloads']); // Quita "Descargas"
    return $items;
}, 10, 1);

function updateArrayValueByKey(&$array, $key, $value)
{
    foreach ($array as &$item) {
        if (is_array($item) && array_key_exists($key, $item)) {
            $item[$key] = $value;
            return true;
        }
    }
    return false;
}

function dataFormatted($yith_wapo_data)
{
    $counters = [
        'cub_ab' => 0,
        'guard_ab' => 0,
        'sobrecub_ab' => 0,
        'despd_ab' => 0,
        'alto_ancho' => 0,
        'acabados2e' => 0,
        'acabados4e' => 0,
        'papel2caras6e' => 0,
        'retractiEnso' => 0
    ];

    // Mapeo de claves que requieren cambios dinámicos
    $key_mappings = [
        '2e_cub_ab' => ['2e_ancho_ab', '2e_alto_ab'],
        '3e_cub_ab' => ['3e_ancho_ab', '3e_alto_ab'],
        '4e_sobre_ab' => ['4e_ancho_ab', '4e_alto_ab'],
        '7e_desp_ab' => ['7e_ancho_ab', '7e_alto_ab'],
        'alto_ancho_personalizado' => ['ancho_mm', 'alto_mm'],
        //'8e_cabezadas_cinta' => ['']
    ];

    return array_reduce($yith_wapo_data, function ($carry, $item) use (&$counters, $key_mappings) {
        if (is_array($item)) {
            $key = array_key_first($item);
            $value = trim(str_replace(' ', '', $item[$key]));

            if (str_ends_with($key, '_tipo') || ($key == 'formato' && $value == 'Personalizado'))
                return $carry;

            if (str_ends_with($key, '_elem') || $key == '9e_ent') {
                $value = true;
            }

            // Mapear claves específicas con alternancia de valores
            if (isset($key_mappings[$key])) {
                $newKey = $key_mappings[$key][$counters[$key] % 2];

                if (!empty($value)) {
                    $key = $newKey;
                    $counters[$key]++;
                } else {
                    return $carry; // Omitir este elemento del array
                }
            }

            // Campos que deben convertirse en `1`
            if (str_ends_with($key, 'sop_cliente') || str_ends_with($key, '_barn')) {
                $value = 1;
            }

            // Procesar acabados 2e y 4e
            if ($key === '2e_acabados_check') {
                $acabados_map = ['2e_esta', '2e_troq', '2e_golp'];
                $key = $acabados_map[$counters['acabados2e']++] ?? end($acabados_map);
                $value = 1;
            }

            if ($key === '4e_acabados_check') {
                $acabados_map = ['4e_esta', '4e_troq', '4e_golp'];
                $key = $acabados_map[$counters['acabados4e']++] ?? end($acabados_map);
                $value = 1;
            }

            // Procesar retractilado y ensobrado
            if ($key === 'retractilado_ensobrado') {
                $retractil_map = ['9e_emp_retrcol', '9e_emp_retruni', '9e_emp_ensobrado'];
                $key = $retractil_map[$counters['retractiEnso']++] ?? end($retractil_map);
                $value = 1;
            }

            // Procesar papel con 2 caras
            if ($key === '6e_papelap_2caras') {
                $papel_map = ['6e_sop_cliente', '6e_plast_2c'];
                $key = $papel_map[$counters['papel2caras6e']++];
                if ($key === '6e_plast_2c' && !empty($value)) {
                    $value = 1;
                }
            }

            if (str_ends_with($key, '_solapas')) {
                if (empty($value) || $value == 0)
                    return $carry;

                $value = intval($value);
            }

            if ($key == 'formato' || str_ends_with($key, 'e_tipo_papel') || str_ends_with($key, 'e_tintas')) {
                $value = str_replace('/', '____', $value);
            }

            // Agregar al resultado
            if (isset($carry[$key])) {
                if (!is_array($carry[$key])) {
                    $carry[$key] = [$carry[$key]];
                }
                $carry[$key][] = $value;
            } else {
                $carry[$key] = $value;
            }
        }
        return $carry;
    }, []);
}

function transformateDataToErp($yith_wapo_data)
{
    $dataToDb = dataFormatted($yith_wapo_data);
    //Añadimos encajado obligatorio
    $dataToDb['e_emp_encaj'] = true;
    $productVariableKeys = ['titulo', 'e_pruebas', 'orientacion', 'e_observaciones_cli', 'e_encu', 'alto_mm', 'ancho_mm'];
    $dataOptimus = [];

    $noPlast = [];
    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '2e_plast', function (&$dataToDb, $findKey) use (&$noPlast) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        $plastificados = array_filter($target, function ($value, $key) {
            return substr($key, -7) === 'e_plast';
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($plastificados as $keyP => $plast) {
            if ($plast === "NULL" || $plast == 'NO_PLAST') {
                unset($target[$keyP]);
                $noPlast[$keyP] = $plast;
            }
        }
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'titulo', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        $target['titulo'] = removeCharacters($target['titulo']);
    });

    $titulo2 = '';
    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'titulo_2', function (&$dataToDb, $findKey) use (&$titulo2) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }

        $titulo2 = removeCharacters($target['titulo_2']);
        unset($target['titulo_2']);
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '7e_elem', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        switch ($target['7e_formapleg']) {
            case 'DIPTICO':
                $target['7e_paginas'] = 4;
                break;
            case 'TRIPTICO':
                $target['7e_paginas'] = 6;
                break;
            case 'CUADRIPTICO':
                $target['7e_paginas'] = 8;
                break;
            default:
                $target['7e_paginas'] = 2;
                break;
        }
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'e_sop_cliente', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        for ($i = 0; $i < 8; $i++) {
            if (!array_key_exists($i . 'e_sop_cliente', $target)) {
                $target[$i . 'e_sop_cliente'] = false;
            }
            if (strpos($target[$i . 'e_tipo_impresion'], 'INKJET') !== false) {
                $target[$i . 'e_tinta_cobertura'] = 12;
            }
        }
    }, true);

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '2e_barn', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        if (isset($target['2e_barn']))
            $target['2e_barn_perc'] = 25;

        if (isset($target['4e_barn']))
            $target['4e_barn_perc'] = 25;
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '2e_esta', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        if (isset($target['2e_esta']))
            $target['2e_esta_cli_1'] = 80;

        if (isset($target['4e_esta']))
            $target['4e_esta_cli_1'] = 80;
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '2e_golp', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        if (isset($target['2e_golp']))
            $target['2e_golp_cli_1'] = 80;

        if (isset($target['4e_golp']))
            $target['4e_golp_cli_1'] = 80;
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '2e_troq', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        if (isset($target['2e_troq']))
            $target['2e_troq_cli_1'] = 80;

        if (isset($target['4e_troq']))
            $target['4e_troq_cli_1'] = 80;
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'e_tipo_impresion', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        for ($i = 0; $i < 8; $i++) {
            if (array_key_exists($i . 'e_tintas', $target) && strpos($target[$i . 'e_tintas'], 'NO IMPRESO') !== false) {
                $target[$i . 'e_tipo_imp'] = 'NO IMPRES HOJA';
                if ($i == 3) {
                    $target[$i . 'e_tipo_pap'] = 'OF-BLC';
                    $target[$i . 'e_tipo_grm'] = 140;
                }
            }
            if (array_key_exists($i . 'e_tipo_impresion', $target)) {
                $target[$i . 'e_tipo_imp'] = $target[$i . 'e_tipo_impresion'];
                unset($target[$i . 'e_tipo_impresion']);
            }
        }
    }, true);

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '6e_elem', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        $target['6e_paginas'] = 2;
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'e_pruebas', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        $target['e_pruebas'] = ($target['e_pruebas'] == 'NO_PRUEBA') ? '' : $target['e_pruebas'];
    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, '0e_elem', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }
        if (
            array_key_exists('0e_elem', $target) &&
            (array_key_exists('1e_elem', $target))
        ) {
            if (
                (
                    strpos($target['0e_tintas'], 'BN_CA') !== false ||
                    strpos($target['0e_tintas'], 'BN_CADO') !== false
                ) &&
                (!is_null($target['1e_tintas']) && (
                        strpos($target['1e_tintas'], 'BN_CA') === false ||
                        strpos($target['1e_tintas'], 'BN_CADO') === false
                    )
                )
            ) {
                $elementosInterior1 = array_filter($target, function ($key) {
                    return substr($key, 0, 2) == '0e';
                }, ARRAY_FILTER_USE_KEY);

                $elementosInterior2 = array_filter($target, function ($key) {
                    return substr($key, 0, 2) == '1e';
                }, ARRAY_FILTER_USE_KEY);

                foreach ($target as $key => $item) {
                    if (substr($key, 0, 2) == '0e') {
                        $elemKey = substr($key, 1);
                        unset($target[$key]);
                        $target[$key] = $elementosInterior2['1' . $elemKey];
                    } elseif (substr($key, 0, 2) == '1e') {
                        $elemKey = substr($key, 1);
                        unset($target[$key]);
                        $target[$key] = $elementosInterior1['0' . $elemKey];
                    }
                }
            }
        }

    });

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'e_tipo_papel', function (&$data, $findKey) {
        if ($findKey !== null) {
            $target =& $data[$findKey];
        } else {
            $target =& $data;
        }
        foreach (array_keys($target) as $key) {
            if (strpos($key, 'e_tipo_papel') !== false) {
                $elemEx = explode('e_tipo_papel', $key);
                $elemType = reset($elemEx);
                $papelEx = explode('____', $target[$key]);
                $target[$elemType . 'e_tipo_pap'] = $papelEx[0];
                $target[$elemType . 'e_tipo_grm'] = $papelEx[1];
                unset($target[$key]);
            }
        }
    }, true);

    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'formato', function (&$data, $findKey) {
        if ($findKey !== null) {
            $target =& $data[$findKey];
        } else {
            $target =& $data;
        }
        if ($target['formato'] != 'custom') {
            $formato = explode('____', $target['formato']);
            $alto_ancho_keys = ($target['orientacion'] == 'HORIZONTAL') ? [0, 1] : [1, 0];

            $target['alto_mm'] = $formato[$alto_ancho_keys[0]];
            $target['ancho_mm'] = $formato[$alto_ancho_keys[1]];

            $target['2e_alto'] = $formato[$alto_ancho_keys[0]];
            $target['2e_ancho'] = $formato[$alto_ancho_keys[1]];
        }
        unset($target['formato']);
        unset($target['orientacion']);
    });

    foreach ($dataToDb as $key => $item) {
        if ($key == 'quantity')
            continue;

        $itemOptimus = '';
        $itemValue = '';

        if ($item === false) {
            $itemValue = 0;
            $itemOptimus = false;
        }

        if ($itemOptimus == '' && $itemValue == '') {
            $itemOptimus = $item;
        }

        if ($key == '9e_ent_00_dir')
            $itemOptimus = floatval($item);

        if (!in_array($key, $productVariableKeys))
            $dataOptimus['productVariable'][$key] = $itemOptimus;
        else
            $dataOptimus['jobVariable'][$key] = $itemOptimus;
    }

    if (isset($dataToDb['quantity'])) {
        if (is_array($dataToDb['quantity'])) {
            foreach ($dataToDb['quantity'] as $quantity) {
                if (is_object($quantity)) {
                    $quantity = get_object_vars($quantity);
                }
                $quantity = str_replace('.', '', $quantity);
                $dataOptimus['quantity']['quantity'][] = (int)$quantity;
            }
        } else {
            $quantity = str_replace('.', '', $dataToDb['quantity']);
            $dataOptimus['quantity']['quantity'][] = (int)$quantity;
        }
    }

    if ($titulo2 != '') {
        if (isset($dataOptimus['jobVariable']['data_optimus'])) {
            $dataOptimus['jobVariable']['data_optimus']['titulo_2'] = $titulo2;
        } else {
            $dataOptimus['jobVariable']['titulo_2'] = $titulo2;
        }
    }

    return [
        'data_optimus' => $dataOptimus
    ];
}

function buscarClaveEnArrayRecursivo($clave, $array, $buscarSimilar = false, $clavePadre = null, &$resultado = [])
{
    foreach ($array as $claveActual => $valor) {
        if ($buscarSimilar) {
            // Buscar similar utilizando strpos en las claves
            if (strpos($claveActual, $clave) !== false) {
                if ($clavePadre !== null) {
                    $resultado[] = $clavePadre;
                } else {
                    $resultado[] = 0; // Indicar que se encontró pero no tiene clave padre
                }
            }
        } else {
            // Buscar exactamente igual
            if ($claveActual === $clave) {
                if ($clavePadre !== null) {
                    $resultado[] = $clavePadre;
                } else {
                    $resultado[] = 0; // Indicar que se encontró pero no tiene clave padre
                }
            }
        }
        if (is_array($valor)) {
            // Llamar recursivamente para buscar en subarrays
            buscarClaveEnArrayRecursivo($clave, $valor, $buscarSimilar, $claveActual, $resultado);
        }
    }
    return $resultado;
}

function aplicarLogicaKeysEncontradas($data, $claveBuscar, $logica, $buscarSimilar = false)
{
    $clavesEncontradas = buscarClaveEnArrayRecursivo($claveBuscar, $data, $buscarSimilar);
    $clavesEncontradas = array_unique($clavesEncontradas);

    if (!empty($clavesEncontradas)) {
        foreach ($clavesEncontradas as $findKey) {
            if ($findKey === 0) {
                $logica($data, null); // Procesa la lógica en el array raíz
            } else {
                $logica($data, $findKey); // Procesa la lógica para la clave encontrada
            }
        }
    }

    return $data;
}

function removeCharacters($string)
{
    $string = str_replace('"', "", $string);
    $string = str_replace("'", "", $string);
    $string = str_replace("/", "", $string);
    $string = str_replace("*", "", $string);
    $string = str_replace("|", "", $string);
    $string = str_replace("(", "", $string);
    $string = str_replace(")", "", $string);
    $string = str_replace("?", "", $string);
    $string = str_replace("¿", "", $string);
    $string = str_replace("ñ", "", $string);
    $string = str_replace("Ñ", "", $string);
    $string = str_replace(",", "", $string);
    $string = str_replace(":", "", $string);
    $string = str_replace("º", "", $string);
    $string = str_replace("ª", "", $string);
    $string = str_replace("á", "a", $string);
    $string = str_replace("é", "e", $string);
    $string = str_replace("í", "i", $string);
    $string = str_replace("ó", "o", $string);
    $string = str_replace("ú", "u", $string);
    $string = str_replace("Á", "A", $string);
    $string = str_replace("É", "E", $string);
    $string = str_replace("Í", "I", $string);
    $string = str_replace("Ó", "O", $string);
    $string = str_replace("Ú", "U", $string);
    $string = str_replace("à", "a", $string);
    $string = str_replace("è", "e", $string);
    $string = str_replace("ì", "i", $string);
    $string = str_replace("ò", "o", $string);
    $string = str_replace("ù", "u", $string);
    $string = str_replace("À", "A", $string);
    $string = str_replace("È", "E", $string);
    $string = str_replace("Ì", "I", $string);
    $string = str_replace("Ò", "O", $string);
    $string = str_replace("Ù", "U", $string);
    $string = str_replace("´", "", $string);
    $string = str_replace("`", "", $string);
    $string = str_replace("  ", " ", $string);
    return $string;
}

function getFechaEstimada()
{
    return date('Y-m-d', strtotime('+14 days'));
}

function preparePricePresupuestoData($curlResult, $dataXml)
{
    $result = [];
    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        $data = [];
        for ($i = 0; $i <= 3; $i++) {
            if ($curlResult->lineQtyPrice[$i] == null)
                break;
            else
                array_push($data, $curlResult->lineQtyPrice[$i]);
        }
        if (is_array($data) && sizeof($data) > 1) {
            foreach ($data as $valueData) {
                $coste_portes = 0;
                $costePapel = 0;
                $ftm_cubierta_alto_ab = 0;
                $ftm_cubierta_ancho_ab = 0;
                $ftm_guardas_alto_ab = 0;
                $ftm_guardas_ancho_ab = 0;
                $cajas = 0;
                $pallet = 0;
                $lomo = 0;
                $peso = 0;
                if (filter_var($valueData->isUnresolved, FILTER_VALIDATE_BOOLEAN)) {
                    $result[] = [
                        'error_message' => $valueData->whyUnresolved,
                        'xml' => $dataXml
                    ];
                } else {
                    for ($i = 0; $i < count($valueData->variable); $i++) {
                        if ($valueData->variable[$i] == null)
                            break;

                        $name = $valueData->variable[$i]->attributes()->name;
                        $value = json_decode($valueData->variable[$i]);
                        if (strpos($name, 'ftm_cubierta_alto_ab') !== false) {
                            $ftm_cubierta_alto_ab = $value;
                        } else if (strpos($name, 'ftm_cubierta_ancho_ab') !== false) {
                            $ftm_cubierta_ancho_ab = $value;
                        } else if (strpos($name, 'ftm_guardas_alto_ab') !== false) {
                            $ftm_guardas_alto_ab = $value;
                        } else if (strpos($name, 'ftm_guardas_ancho_ab') !== false) {
                            $ftm_guardas_ancho_ab = $value;
                        } else if (strpos($name, 'lomo') !== false) {
                            $lomo = $value;
                        } else if (strpos($name, 'peso') !== false) {
                            $peso = $value;
                        } else if (strpos($name, 'coste_portes') !== false) {
                            $coste_portes = $value;
                        } else if (strpos($name, 'coste_papel') !== false) {
                            $costePapel = $value;
                        } else if (strpos($name, 'no_cajas') !== false) {
                            $cajas = $value;
                        } else if (strpos($name, 'no_palets') !== false) {
                            $pallet = $value;
                        }
                    }

                    $result[] = [
                        'quantity' => (float)$valueData->quantity,
                        'price' => round((float)$valueData->price, 2),
                        'tax_value' => (float)$valueData->taxValue,
                        'ftm_cubierta_alto_ab' => $ftm_cubierta_alto_ab,
                        'ftm_cubierta_ancho_ab' => $ftm_cubierta_ancho_ab,
                        'ftm_guardas_alto_ab' => $ftm_guardas_alto_ab,
                        'ftm_guardas_ancho_ab' => $ftm_guardas_ancho_ab,
                        'lomo' => round($lomo, 2),
                        'peso' => round($peso, 2),
                        'coste_portes' => round((float)$coste_portes, 2),
                        'coste_papel' => round((float)$costePapel, 2),
                        'cajas' => $cajas,
                        'pallet' => $pallet
                    ];
                }
            }
        } else {
            if (filter_var($data[0]->isUnresolved, FILTER_VALIDATE_BOOLEAN)) {
                $result[] = [
                    'error_message' => $data[0]->whyUnresolved,
                    'xml' => $dataXml
                ];
            } else {
                $coste_portes = 0;
                $costePapel = 0;
                $ftm_cubierta_alto_ab = 0;
                $ftm_cubierta_ancho_ab = 0;
                $ftm_guardas_alto_ab = 0;
                $ftm_guardas_ancho_ab = 0;
                $cajas = 0;
                $pallet = 0;
                $lomo = 0;
                $peso = 0;
                for ($i = 0; $i < count($data[0]->variable); $i++) {
                    if ($data[0]->variable[$i] == null)
                        break;

                    $name = $data[0]->variable[$i]->attributes()->name;
                    $value = json_decode($data[0]->variable[$i]);
                    if (strpos($name, 'ftm_cubierta_alto_ab') !== false) {
                        $ftm_cubierta_alto_ab = $value;
                    } else if (strpos($name, 'ftm_cubierta_ancho_ab') !== false) {
                        $ftm_cubierta_ancho_ab = $value;
                    } else if (strpos($name, 'ftm_guardas_alto_ab') !== false) {
                        $ftm_guardas_alto_ab = $value;
                    } else if (strpos($name, 'ftm_guardas_ancho_ab') !== false) {
                        $ftm_guardas_ancho_ab = $value;
                    } else if (strpos($name, 'lomo') !== false) {
                        $lomo = $value;
                    } else if (strpos($name, 'peso') !== false) {
                        $peso = $value;
                    } else if (strpos($name, 'coste_portes') !== false) {
                        $coste_portes = $value;
                    } else if (strpos($name, 'coste_papel') !== false) {
                        $costePapel = $value;
                    } else if (strpos($name, 'no_cajas') !== false) {
                        $cajas = $value;
                    } else if (strpos($name, 'no_palets') !== false) {
                        $pallet = $value;
                    }
                }

                $result[] = [
                    'quantity' => (float)$data[0]->quantity,
                    'price' => round((float)$data[0]->price, 2),
                    'tax_value' => (float)$data[0]->taxValue,
                    'ftm_cubierta_alto_ab' => $ftm_cubierta_alto_ab,
                    'ftm_cubierta_ancho_ab' => $ftm_cubierta_ancho_ab,
                    'ftm_guardas_alto_ab' => $ftm_guardas_alto_ab,
                    'ftm_guardas_ancho_ab' => $ftm_guardas_ancho_ab,
                    'lomo' => round($lomo, 2),
                    'peso' => round($peso, 2),
                    'coste_portes' => round((float)$coste_portes, 2),
                    'coste_papel' => round((float)$costePapel, 2),
                    'cajas' => $cajas,
                    'pallet' => $pallet
                ];
            }
        }
    }
    return $result;
}

function getPricePresupuestoToOptimus($dataOptimus, $codCliente, $fechaEstimada = '')
{
    if (!isset($dataOptimus['productVariable']))
        return [['error_message' => 'productVariable']];

    $jobVariableKeys = array_keys($dataOptimus['productVariable']);
    $tipos = [];
    foreach ($jobVariableKeys as $key => $jobVariableKey) {
        if (strpos($jobVariableKey, 'e_tipo_imp') !== false) {
            $array = explode('e_tipo_imp', $jobVariableKey);
            if ($dataOptimus['productVariable'][$jobVariableKey] == 'NO IMPRESO') {
                $dataOptimus['productVariable'][reset($array) . 'e_tintas'] = 'NO IMPRESO';
            }
            $tipos[] = reset($array);
        }
    }

    $data = readOptimusXml('price-request');
    $data->customerCode = $codCliente;
    $data->addressNumber = ($dataOptimus['productVariable']['9e_ent_00_dir']) ?? 1;

    $data->jobVariable->name = 'ep_fecha_entrega';
    $data->jobVariable->type = 'datetime';
    if ($fechaEstimada)
        $data->jobVariable->value = $fechaEstimada . 'T' . date('H:i');

    $encuadernacion = $dataOptimus['jobVariable']['e_encu'];
    $tipoImpresionInterior = $dataOptimus['productVariable']['0e_tipo_imp'] ?? $dataOptimus['productVariable']['2e_tipo_imp'];

    $tipoPedido = getCodTipoPedido($encuadernacion, $tipoImpresionInterior);
    $data->jobVariable[1]->value = $tipoPedido;
    $data->jobVariable[2]->value = $dataOptimus['jobVariable']['titulo'];

    $countJV = 0;
    $countPV = 0;
    $data->line->description = $dataOptimus['jobVariable']['titulo'];
    unset($dataOptimus['jobVariable']['titulo']);
    $dataOptimus['jobVariable']['e_ancho'] = $dataOptimus['jobVariable']['ancho_mm'];
    $dataOptimus['jobVariable']['e_alto'] = $dataOptimus['jobVariable']['alto_mm'];
    unset($dataOptimus['jobVariable']['ancho_mm']);
    unset($dataOptimus['jobVariable']['alto_mm']);

    $data->line->productVariable[$countPV]->name = 'e_elem_mod';
    $data->line->productVariable[$countPV]->type = 'integer';
    $data->line->productVariable[$countPV]->value = 1;
    $countPV++;

    foreach ($tipos as $tipo) {
        $data->line->productVariable[$countPV]->name = $tipo . 'e_elem';
        $data->line->productVariable[$countPV]->type = 'integer';
        $data->line->productVariable[$countPV]->value = 1;
        $countPV++;
    }

    foreach ($dataOptimus as $keyType => $valueData) {
        foreach ($valueData as $key => $value) {
            $key = str_replace('9', '', $key);
            $typeValue = 'string';
            switch (gettype($value)) {
                case 'double':
                    if (is_float($value))
                        $typeValue = 'decimal';
                    else
                        $typeValue = 'integer';
                    break;
                case 'boolean':
                    $typeValue = 'boolean';
                    break;
                default:
                    break;
            }
            if ($key == 'e_ancho' || $key == 'e_alto' || strpos($key, '_paginas') !== false) {
                $typeValue = 'integer';
            }
            if ($keyType == 'jobVariable' || $keyType == 'productVariable') {
                if (is_numeric($key[0]) && !in_array($key[0], $tipos))
                    continue;

                if (is_bool($value)) {
                    $value = intval($value);
                }

                if (is_array($value) || $value == '')
                    continue;

                $data->line->productVariable[$countPV]->name = $key;
                $data->line->productVariable[$countPV]->type = $typeValue;
                $data->line->productVariable[$countPV]->value = $value;
                $countPV++;
            } elseif ($keyType == 'quantity') {
                foreach ($value as $keyQ => $quantity) {
                    if ($quantity > 0)
                        $data->line->quantity[$keyQ] = $quantity;
                }
            }
        }
    }

    $dataXml = $data->asXML();
    /*header("Content-type: text/xml");
    echo $dataXml;
    exit;*/
    $curlResult = sendXmlOptimus($dataXml, 'enqPrice', 'POST', false, true);
    $result = preparePricePresupuestoData($curlResult, $dataXml);

    if (!empty($result))
        return $result;

    /*if (empty($result)) {
        return ['error_message' => $curlResult->error, 'xml' => $dataXml];
    } else {
        //DESCUENTO EN FICHA DE CLIENTE
        if (!is_null($userData['percent_descuento']) && $userData['percent_descuento'] != 0) {
            $percentDiscount = $userData['percent_descuento'];
            $result = array_map(function ($item) use ($percentDiscount) {
                if (is_array($item) && isset($item['price']) && is_numeric($item['price'])) {
                    $item['price'] += ($item['price'] * $percentDiscount / 100);
                }
                return $item;
            }, $result);
        }

        $result['xml'] = $dataXml;
        $formatoAncho = $presupuestoData['data_optimus']['jobVariable']['ancho_mm'];
        if ($tarifa = getIfTarifa($userId, $formatoAncho)) {
            processTarifa($tarifa, $presupuestoData, $result);
            $result['is_tarifa'] = $tarifa['id_tarifa'];
        }
        return $result;
    }*/
}

//acción en pedidos "subir archivos"
add_filter('woocommerce_my_account_my_orders_actions', function ($actions, $order) {
    // Obtener el valor del meta campo "cod_pedido_optimus"
    $cod_pedido_optimus = get_post_meta($order->get_id(), '_optimus_cod_pedido', true);

    // Asegurar que el código de pedido está definido
    if (!empty($cod_pedido_optimus)) {
        $query_args = array(
            'order_id' => $order->get_id(),
            'cod_ped' => $cod_pedido_optimus
        );
    } else {
        $query_args = array(
            'order_id' => $order->get_id()
        );
    }

    // Asegurar que solo se muestre en ciertos estados (puedes modificar esto)
    if ($order->has_status('completed')) {
        $actions['subir_archivos'] = array(
            'url' => add_query_arg($query_args, site_url('/subir-archivos/')), // Agregar parámetros dinámicamente
            'name' => __('Subir Archivos', 'woocommerce'),
        );
    }

    return $actions;
}, 10, 2);

add_filter('manage_edit-shop_order_columns', function ($columns) {
    $columns['cod_pedido_optimus'] = __('Código Optimus', 'woocommerce');
    return $columns;
});

// Mostrar el contenido en la nueva columna
add_action('manage_shop_order_posts_custom_column', function ($column, $post_id) {
    if ($column === 'cod_pedido_optimus') {
        $cod_pedido_optimus = get_post_meta($post_id, '_optimus_cod_pedido', true);
        echo !empty($cod_pedido_optimus) ? esc_html($cod_pedido_optimus) : '-';
    }
}, 10, 2);

// Reemplazar el número de pedido en la página "Mis Pedidos"
add_filter('woocommerce_order_number', function ($order_number, $order) {
    $cod_pedido_optimus = get_post_meta($order->get_id(), '_optimus_cod_pedido', true);
    return !empty($cod_pedido_optimus) ? $cod_pedido_optimus : $order_number;
}, 10, 2);


function getFtpFolder($presupuestoOptimusEnqNumber)
{
    $folderCount = 100;
    while ($folderCount < $presupuestoOptimusEnqNumber) {
        $folderCount += 100;
    }

    if ($presupuestoOptimusEnqNumber % 100 != 0) {
        $folderCount -= 100;
    }

    $folder = substr_replace($folderCount, 'xx', -2);
    if ($folder == 'xx') {
        $folder = '0xx';
    }
    return [
        'folder' => $folder,
        'order_id' => $presupuestoOptimusEnqNumber
    ];
}

function filtrar_archivos_validos($archivos)
{
    $archivos_validos = [];

    foreach ($archivos as $key => $archivo) {
        if ($archivo['error'] === 0 && $archivo['size'] > 0) {
            $archivos_validos[$key] = $archivo;
        }
    }

    return $archivos_validos;
}

function procesar_subida_archivos()
{
    $archivos_validos = filtrar_archivos_validos($_FILES);

    if (empty($archivos_validos) || !isset($_POST['order_id']) || !isset($_POST['cod_ped'])) {
        echo json_encode(['message' => 'No se ha seleccionado un archivo o falta el pedido.']);
        wp_die();
    }

    $order_id = intval($_POST['order_id']);
    $es_local = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

    // 🔹 Si es local, usar 99999 como código
    $cod_optimus = $es_local ? 99999 : intval($_POST['cod_ped']);
    $order = wc_get_order($order_id);
    if (!$order) {
        echo json_encode(['message' => 'El pedido no existe.']);
        wp_die();
    }

    $host = '81.42.209.224';
    $port = '21';
    $timeout = 10;
    $user = "Felipe";
    $pass = "Masque2601";
    $ftp_dir = '';

    $folder = getFtpFolder($cod_optimus);
    if (is_array($folder))
        $ftp_dir = '/pdf/' . $folder['folder'] . '/' . $folder['order_id'];
    else
        $ftp_dir = $folder;

    $ftp = ftp_connect($host, $port, $timeout);
    ftp_login($ftp, $user, $pass);
    ftp_pasv($ftp, true);

    $archivos_subidos = [];
    $errores = [];

    foreach ($archivos_validos as $key => $filename) {
        $codigo = preg_replace('/^archivo_pdf_/', '', $key);
        $elementoNumeracion = getTypeElemByNumberId($codigo);

        $fileName = $cod_optimus . $elementoNumeracion['numeracion'] . '_' . strtolower($elementoNumeracion['tipo']) . '_' . str_replace(' ', '_', 'pedido_web') . '.pdf';
        $tmp_name = $filename['tmp_name'];
        $destination_file = $ftp_dir . '/' . basename($fileName);

        // Verificar si el archivo temporal existe
        if (!file_exists($tmp_name) || filesize($tmp_name) == 0) {
            $errores[] = "Error: El archivo temporal no existe o está vacío - {$fileName}";
            continue;
        }

        // Subida asíncrona con ftp_nb_put()
        $upload_status = ftp_nb_put($ftp, $destination_file, $tmp_name, FTP_BINARY);

        while ($upload_status == FTP_MOREDATA) {
            // Aquí podrías realizar otras tareas si es necesario
            $upload_status = ftp_nb_continue($ftp);
        }

        if ($upload_status == FTP_FINISHED) {
            $archivos_subidos[] = $fileName;
        } else {
            $errores[] = "Error al subir {$fileName}.";
        }
    }

    ftp_close($ftp);

    if (!empty($archivos_subidos)) {
        $order->update_status('wc-archivos-subidos', __('Se han subido archivos al FTP.', 'woocommerce'));
        echo json_encode([
            'message' => 'Archivos subidos correctamente: ' . implode(', ', $archivos_subidos),
            'error' => !empty($errores) ? implode(', ', $errores) : ''
        ]);
    } else {
        echo json_encode(['message' => 'No se subió ningún archivo.', 'error' => implode(', ', $errores)]);
    }

    wp_die();
}

add_action('wp_ajax_procesar_subida_archivos', 'procesar_subida_archivos');
add_action('wp_ajax_nopriv_procesar_subida_archivos', 'procesar_subida_archivos');

function getTypeElemByNumberId($elemId)
{
    $result = [];
    preg_match('/(\d)e$/', $elemId, $matches);
    if (isset($matches[1])) {
        if ($matches[1] == 0)
            $numeracion = str_pad($matches[1] + 1, 2, '0', STR_PAD_LEFT);
        else
            $numeracion = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
    } else {
        $numeracion = null;
    }

    switch ($elemId) {
        case '0e':
        case '1e':
            $result['tipo'] = 'interior';
            break;
        case '4e':
        case '2e':
            $result['tipo'] = 'cubierta';
            break;
        case '3e':
            $result['tipo'] = 'guardas';
            break;
        case '5e':
            $result['tipo'] = 'faja';
            break;
        case '6e':
            $result['tipo'] = 'marcapaginas';
            break;
        case '7e':
            $result['tipo'] = 'desplegable';
            break;
    }

    // Solo añadir 'numeracion' si se encontró un número válido
    if ($numeracion !== null) {
        $result['numeracion'] = $numeracion;
    }

    return $result;
}

function proteger_pagina_subir_archivos()
{
    if (is_page('subir-archivos')) { // Verifica que estamos en la página correcta
        if (!is_user_logged_in()) {
            // Si el usuario no está logueado, redirigir a la página de login
            wp_redirect(home_url('/iniciar-sesion'));
            exit;
        }

        if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
            // Si no hay order_id en la URL, redirigir a la página de pedidos
            wp_redirect(home_url('/iniciar-sesion/orders/'));
            exit;
        }

        $order_id = intval($_GET['order_id']);
        $current_user_id = get_current_user_id();

        $order = wc_get_order($order_id);

        if (!$order) {
            wp_redirect(home_url('/iniciar-sesion/orders/'));
            exit;
        }

        if ($order->get_user_id() !== $current_user_id) {
            wp_redirect(home_url('/iniciar-sesion/orders/'));
            exit;
        }
    }
}

add_action('template_redirect', 'proteger_pagina_subir_archivos');

//Nuevo estado
add_action('init', function () {
    register_post_status('wc-archivos-subidos', array(
        'label' => 'Archivos subidos',
        'public' => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list' => true,
        'exclude_from_search' => false,
        'label_count' => _n_noop('Archivos subidos <span class="count">(%s)</span>', 'Archivos subidos <span class="count">(%s)</span>', 'woocommerce')
    ));
});

// Agregar el estado en la lista de estados de WooCommerce
add_filter('wc_order_statuses', function ($order_statuses) {
    $order_statuses['wc-archivos-subidos'] = __('Archivos subidos', 'woocommerce');
    return $order_statuses;
});

add_action('woocommerce_payment_complete', function ($order_id) {
    $order = wc_get_order($order_id);
    if ($order && $order->get_payment_method() === 'redsys') { // Asegúrate de que 'redsys' sea el ID de la pasarela
        $order->update_status('wc-archivos-subidos', __('Pedido en espera de archivos.', 'woocommerce'));
    }
});

function markSuccessful($cantidadFinal, $fechaEstimada, $dataPres)
{
    $markResult = [];
    $tipoIva = ($dataPres['isbn'] == '' || is_null($dataPres['isbn']) || $dataPres['no_isbn'] == 1) ? 'G' : 'S';
    $imprimirRh = 'SI';

    if ($fechaEstimada)
        $fechaEstimada = $fechaEstimada . 'T' . date('H:i');

    $aceptarOfertaXml = readOptimusXml('markSuccessful');
    $aceptarOfertaXml->enquiryNumber = $dataPres['enq_number'];
    $aceptarOfertaXml->successInfo->jobVariable[0]->name = 'ep_fecha_entrega';
    $aceptarOfertaXml->successInfo->jobVariable[0]->type = 'datetime';
    $aceptarOfertaXml->successInfo->jobVariable[0]->value = $fechaEstimada;
    $aceptarOfertaXml->successInfo->jobVariable[1]->name = 'ep_imprimirhr';
    $aceptarOfertaXml->successInfo->jobVariable[1]->type = 'string';
    $aceptarOfertaXml->successInfo->jobVariable[1]->value = $imprimirRh;
    $aceptarOfertaXml->successInfo->jobVariable[2]->name = 'ep_tipo_iva';
    $aceptarOfertaXml->successInfo->jobVariable[2]->type = 'string';
    $aceptarOfertaXml->successInfo->jobVariable[2]->value = $tipoIva;

    $aceptarOfertaXml->successInfo->lineSuccessInfo[0]->lineNumber = 1;
    $aceptarOfertaXml->successInfo->lineSuccessInfo[0]->acceptedQuantity = $cantidadFinal;

    $curlResult = sendXmlOptimus($aceptarOfertaXml->asXML(), 'enquiry/markSuccessful');

    if (filter_var($curlResult->success, FILTER_VALIDATE_BOOLEAN)) {
        $markResult = [
            'success' => true,
            'cod_pedido_optimus' => $curlResult->jobNumber
        ];
    } else {
        $markResult = [
            'success' => false,
            'message' => $curlResult->error
        ];
    }

    return $markResult;
}

/*function replacePrice($price)
{
    return str_replace('.', ',', $price);
}*/