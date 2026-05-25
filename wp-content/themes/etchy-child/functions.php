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

//desactivamos cupones
add_filter('woocommerce_coupons_enabled', '__return_false');

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
    /*
     * MQL: el mismo controlador sirve para:
     * - flujo normal de producto: cliente = api_id del usuario logueado
     * - presupuesto publico: cliente fijo = PRUEBAS
     *
     * En el flujo publico no exigimos carrito ni guardamos precio en sesion,
     * porque solo queremos consultar Optimus y devolver el precio.
     */
    $dataToDb = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if (!is_array($dataToDb)) {
        $dataToDb = [];
    }

    $is_public_quote = mql_is_public_quote_request($dataToDb);

    if (!$is_public_quote && !WC()->cart) {
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

    if (isset($dataToDb['yith_wapo']) && is_array($dataToDb['yith_wapo'])) {
        $yith_wapo_data = $dataToDb['yith_wapo'];
    } else {
        $yith_wapo_data = [];
    }

    if (empty($yith_wapo_data)) {
        wp_send_json_error(array('message' => 'No se han recibido datos de YITH WAPO.'));
    }

    $codOptimus = mql_get_cod_optimus_for_price_request($dataToDb);
    if (empty($codOptimus)) {
        wp_send_json_error(array('message' => 'No se ha encontrado codigo de cliente Optimus.'));
    }

    //[MQL] - FECHA ESTIMADA
    $fechaEstimada = getFechaEstimada();
    $dataOptimus = getDataOptimusToProcessOrder($yith_wapo_data);
    $priceRequest = getPricePresupuestoToOptimus($dataOptimus, $codOptimus, $fechaEstimada);

    $precio_calculado = 0;
    $first_price_line = is_array($priceRequest) ? reset($priceRequest) : null;
    if (is_array($first_price_line) && isset($first_price_line['price'])) {
        $precio_calculado = (float) $first_price_line['price'];
    }

    // Solo forzamos precio en carrito en el flujo normal.
    if (!$is_public_quote && $precio_calculado > 0 && WC()->session) {
        WC()->session->set('precio_forzado', $precio_calculado);
    }

    wp_send_json($priceRequest);
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
    if (!is_product()) return;
    ?>
    <style>
        /* Botón ? en legend */
        fieldset.wapo-section > legend{
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .dir-help-toggle{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:18px;
            height:18px;
            border-radius:999px;
            border:1px solid #111;
            background:#fff;
            color:#111;
            font-size:12px;
            line-height:1;
            padding:0;
            cursor:pointer;
        }
        .dir-help-toggle:focus{ outline:2px solid #111; outline-offset:2px; }

        /* Mini-form */
        .direccion-form-container{
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            border-radius: 12px;
        }
        .direccion-form-grid{
            display:grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        .direccion-form-grid h4{
            grid-column: 1 / -1;
            margin: 0 0 6px 0;
        }
        .direccion-form-grid #nuevo_codigo_postal,
        .direccion-form-grid #guardar-direccion{
            grid-column: 1 / -1;
        }
        .direccion-form-grid input,
        .direccion-form-grid select{
            width:100%;
        }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            // ---------- helpers ----------
            function getDireccionSelect(){
                return $('select[name="yith_wapo[][9e_ent_00_dir]"]');
            }

            // ✅ UI: botón en legend + mini-form (NO rompe si YITH re-renderiza)
            function ensureDireccionMiniFormUI() {
                const $select = getDireccionSelect();
                if (!$select.length) return;

                const $addon = $select.closest('.yith-wapo-addon');
                if (!$addon.length) return;

                const $fs = $addon.closest('fieldset.wapo-section');
                const $legend = $fs.children('legend').first();
                if (!$legend.length) return;

                // Mantener estado si el formulario estaba abierto
                const $existingBox = $('#direccion-form-container');
                const wasOpen = $existingBox.length ? $existingBox.is(':visible') : false;

                // Quitar botón antiguo si estuviese en el h3 (evitar duplicados)
                $addon.find('.addon-header .dir-help-toggle').remove();

                // Normalizar legend (evita que al append se ensucie el texto)
                if (!$legend.find('.legend-title').length) {
                    const txt = $legend.text().trim();
                    $legend.empty().append($('<span/>', { class: 'legend-title', text: txt }));
                }

                // Crear botón si no existe
                if (!$legend.find('.dir-help-toggle').length) {
                    $legend.append(
                        $('<button/>', {
                            type: 'button',
                            class: 'dir-help-toggle',
                            'aria-expanded': wasOpen ? 'true' : 'false',
                            'aria-controls': 'direccion-form-container',
                            title: 'Añadir nueva dirección'
                        }).text('?')
                    );
                } else {
                    // Si ya existe, sincroniza aria con estado real
                    $legend.find('.dir-help-toggle').attr('aria-expanded', wasOpen ? 'true' : 'false');
                }

                // Crear/normalizar el mini-formulario
                let $box = $('#direccion-form-container');

                if (!$box.length) {
                    const formHtml = `
                      <div id="direccion-form-container" class="direccion-form-container" style="display:none;" aria-hidden="true">
                          <div class="direccion-form-grid">
                            <h4>Añadir Nueva Dirección</h4>

                            <select id="nueva_ciudad" class="yith-wapo-option-value">
                              <option value="">Selecciona una provincia</option>
                            </select>

                            <input type="text" id="nuevo_codigo_postal" placeholder="Código Postal">

                            <input type="text" id="nueva_direccion" placeholder="Dirección">

                            <button id="guardar-direccion" style="background:#0073aa;color:#fff;padding:10px;border:none;border-radius:10px;">
                              Guardar Dirección
                            </button>
                          </div>
                        </div>
                    `;
                    $(formHtml).insertAfter($select);
                    $box = $('#direccion-form-container');

                    // cargar provincias sólo si lo creamos ahora
                    cargarProvincias();
                } else {
                    $box.addClass('direccion-form-container');
                    if (!$box.find('.direccion-form-grid').length) {
                        $box.wrapInner('<div class="direccion-form-grid"></div>');
                    }
                }

                // Restaurar estado (si estaba abierto, no lo cierres)
                if (wasOpen) {
                    $box.show().attr('aria-hidden', 'false');
                } else {
                    $box.hide().attr('aria-hidden', 'true');
                }
            }

            // ✅ Delegado: click del botón ?
            $(document).off('click.dirHelpToggle').on('click.dirHelpToggle', '.dir-help-toggle', function (e) {
                e.preventDefault();

                const $btn = $(this);
                const $box = $('#direccion-form-container');
                if (!$box.length) return;

                const isOpen = $box.is(':visible');

                if (isOpen) {
                    $box.stop(true, true).slideUp(180, function () {
                        $box.attr('aria-hidden', 'true');
                    });
                    $btn.attr('aria-expanded', 'false');
                } else {
                    $box.stop(true, true).slideDown(180, function () {
                        $box.attr('aria-hidden', 'false');
                        $('#nueva_direccion').trigger('focus');
                    });
                    $btn.attr('aria-expanded', 'true');
                }
            });

            // ✅ Observa cambios de YITH y reinyecta UI (evita “desaparece”)
            (function initDirHelpObserver(){
                if (window.__dirHelpObserver) return;

                const node = document.querySelector('#yith-wapo-container') || document.body;
                window.__dirHelpObserver = new MutationObserver(function(){
                    ensureDireccionMiniFormUI();
                });
                window.__dirHelpObserver.observe(node, { childList: true, subtree: true });
            })();

            function ajustarLayoutDireccion() {
                const $select = getDireccionSelect();
                if (!$select.length) return;

                const addon = $select.closest('.yith-wapo-addon').get(0);
                if (!addon) return;

                if (window.matchMedia('(min-width: 1024px)').matches) {
                    addon.style.setProperty('grid-column', 'span 4', 'important'); // 2/3
                    addon.style.setProperty('order', '30', 'important');
                } else {
                    addon.style.setProperty('grid-column', '1 / -1', 'important'); // full en tablet/móvil
                    addon.style.setProperty('order', '30', 'important');
                }
            }

            function cargarProvincias() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {action: 'get_provincias'},
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const $selectCiudad = $('#nueva_ciudad');
                            if (!$selectCiudad.length) return;

                            $selectCiudad.empty().append('<option value="">Selecciona una provincia</option>');
                            $.each(response.data, function (index, provincia) {
                                $selectCiudad.append('<option value="' + provincia.codigo + '">' + provincia.nombre + '</option>');
                            });
                            $selectCiudad.trigger('change');
                        }
                    }
                });
            }

            function cargarDirecciones() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {action: 'get_user_addresses'},
                    dataType: 'json',
                    success: function (response) {
                        const $select = getDireccionSelect();
                        if (!$select.length) return;

                        ajustarLayoutDireccion();
                        $(window).off('resize.ajustarDireccion').on('resize.ajustarDireccion', ajustarLayoutDireccion);

                        // Rellenar select
                        if (response.success && response.data.no_address) {
                            $select.empty().append('<option value="">No tienes direcciones guardadas</option>').show();
                        } else {
                            $select.empty().append('<option value="">Selecciona una dirección</option>');
                            $.each(response.data.addresses || [], function (index, address) {
                                $select.append('<option value="' + address.id + '">' + address.label + '</option>');
                            });
                            $select.trigger('change').show();
                        }

                        // Inyectar UI (y repetir tras repintados)
                        ensureDireccionMiniFormUI();
                        setTimeout(ensureDireccionMiniFormUI, 50);
                        setTimeout(ensureDireccionMiniFormUI, 300);
                    }
                });
            }

            // Cargar direcciones al iniciar
            cargarDirecciones();

            // Guardar nueva dirección
            $(document).on('click', '#guardar-direccion', function (e) {
                e.preventDefault();

                const nuevaDireccion = $('#nueva_direccion').val().trim();
                const nuevaCiudad = $('#nueva_ciudad').val().trim();
                const nuevoCodigoPostal = $('#nuevo_codigo_postal').val().trim();

                if (!nuevaDireccion || !nuevaCiudad || !nuevoCodigoPostal) {
                    alert('Por favor, completa todos los campos.');
                    return;
                }

                Swal.fire({
                    title: '<strong>Guardando dirección...</strong>',
                    html: '<p>Por favor, espera un momento.</p>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

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
                            Swal.close();

                            Swal.fire({
                                icon: 'success',
                                title: '<strong>Dirección guardada correctamente</strong>',
                                html: `<p>La nueva dirección se ha añadido a la lista.</p>`,
                                confirmButtonText: 'Aceptar',
                                width: '600px'
                            });

                            const $select = getDireccionSelect();
                            if ($select.length) {
                                const nuevaOpcion = `<option value="${response.data.id}" selected>
                                    ${response.data.direccion}, ${response.data.ciudad} (${response.data.codigo_postal})
                                </option>`;
                                $select.append(nuevaOpcion).trigger('change').show();
                            }

                            // Mantener UI
                            ensureDireccionMiniFormUI();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '<strong>Error al guardar la dirección</strong>',
                                html: '<p>Ha ocurrido un problema al guardar la dirección. Inténtalo de nuevo.</p>',
                                confirmButtonText: 'Aceptar',
                                width: '600px'
                            });
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: '<strong>Error de conexión</strong>',
                            html: '<p>No se ha podido contactar con el servidor. Revisa tu conexión e inténtalo de nuevo.</p>',
                            confirmButtonText: 'Aceptar',
                            width: '600px'
                        });
                    }
                });
            });

        });
    </script>
    <?php
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

/**
 * Solo prepara / guarda datos del checkout si lo necesitas.
 * NO crea nada en Optimus aquí.
 */
add_action('woocommerce_checkout_update_order_meta', 'mql_guardar_datos_checkout_para_optimus', 10, 1);
function mql_guardar_datos_checkout_para_optimus($order_id)
{
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    // Si necesitas dejar trazabilidad:
    $order->add_order_note('Pedido creado en WooCommerce. Pendiente de confirmación de pago.');
}

add_action('woocommerce_payment_complete', 'mql_procesar_pedido_optimus_tras_pago', 20, 1);
function mql_procesar_pedido_optimus_tras_pago($order_id)
{
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    // Evita duplicados
    if (get_post_meta($order_id, '_optimus_processed', true)) {
        // Si ya fue procesado, solo aseguramos el estado final si quieres
        if ($order->get_status() !== 'archivos-subidos') {
            $order->update_status('wc-archivos-subidos', __('Pago confirmado y archivos subidos.', 'woocommerce'));
        }
        return;
    }

    // Solo continuar si realmente está pagado
    if (!$order->is_paid()) {
        $order->add_order_note('Intento de envío a Optimus cancelado: el pedido todavía no figura como pagado.');
        return;
    }

    $user_id = $order->get_user_id();
    $codOptimusUser = $user_id ? get_user_meta($user_id, 'api_id', true) : null;

    // Leer configuración desde el pedido, no desde el carrito
    $yith_wapo_data = mql_obtener_yith_desde_pedido($order);

    if (empty($yith_wapo_data)) {
        $order->update_status('failed', __('No se encontraron datos YITH en el pedido.', 'woocommerce'));
        $order->add_order_note(__('No se encontraron datos YITH en el pedido para enviar a Optimus.', 'woocommerce'));

        wc_mail(
            get_option('admin_email'),
            __('Error en pedido WooCommerce', 'woocommerce'),
            'El pedido #' . $order_id . ' no pudo ser procesado en Optimus porque no se encontraron datos YITH en el pedido.'
        );

        return;
    }

    $dataDb = getDataOptimusToProcessOrder($yith_wapo_data);
    $fechaEstimada = getFechaEstimada();
    $total = $order->get_total();

    // Crear oferta/pedido en Optimus SOLO tras pago exitoso
    $datosPedidoOptimus = addPresupuestoToOptimus($dataDb, $fechaEstimada, $total);

    if (empty($datosPedidoOptimus['success'])) {
        $error_message = !empty($datosPedidoOptimus['error_message']) ? $datosPedidoOptimus['error_message'] : 'Error desconocido al crear el pedido en Optimus.';

        $order->update_status('failed', __('Error en Optimus: ' . $error_message, 'woocommerce'));
        $order->add_order_note(__('Error en Optimus: ' . $error_message, 'woocommerce'));

        wc_mail(
            get_option('admin_email'),
            __('Error en pedido WooCommerce', 'woocommerce'),
            'El pedido #' . $order_id . ' no pudo ser procesado en Optimus. Motivo: ' . $error_message
        );

        return;
    }

    update_post_meta($order_id, '_optimus_enq_number', sanitize_text_field($datosPedidoOptimus['enq_number']));
    update_post_meta($order_id, '_optimus_cod_pedido', sanitize_text_field($datosPedidoOptimus['cod_pedido_optimus']));
    update_post_meta($order_id, '_optimus_processed', 1);

    $subject = 'Nuevo presupuesto';
    $message = "
Aviso de nuevo presupuesto, estos son los datos:
Codigo presupuesto wordpress: " . $order_id . ",
Codigo pedido: " . $datosPedidoOptimus['cod_pedido_optimus'] . ",
Codigo oferta: " . $datosPedidoOptimus['enq_number'] . ",
Cliente: " . $codOptimusUser . "
";
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail('soporte@masquelibrosdigital.com', $subject, $message, $headers);

    $order->add_order_note('Pago confirmado. Pedido enviado a Optimus.');
    $order->add_order_note('ENQ Number: ' . $datosPedidoOptimus['enq_number']);
    $order->add_order_note('COD Pedido Optimus: ' . $datosPedidoOptimus['cod_pedido_optimus']);

    // Estado final deseado tras pago correcto + procesamiento correcto
    $order->update_status('wc-archivos-subidos', __('Pago confirmado, pedido enviado a Optimus y archivos subidos.', 'woocommerce'));
}

function mql_obtener_yith_desde_pedido($order)
{
    if (!$order instanceof WC_Order) {
        return null;
    }

    foreach ($order->get_items() as $item_id => $item) {
        $meta_data = $item->get_meta_data();

        foreach ($meta_data as $meta) {
            $key = $meta->key;
            $value = $meta->value;

            // Ajusta estas claves según cómo YITH esté guardando realmente los datos
            if ($key === 'yith_wapo_options' || $key === '_yith_wapo_options') {
                return $value;
            }
        }
    }

    return null;
}

add_action('woocommerce_checkout_create_order_line_item', 'mql_guardar_yith_en_linea_pedido', 10, 4);
function mql_guardar_yith_en_linea_pedido($item, $cart_item_key, $values, $order)
{
    if (isset($values['yith_wapo_options']) && !empty($values['yith_wapo_options'])) {
        $item->add_meta_data('yith_wapo_options', $values['yith_wapo_options'], true);
    }

    if (isset($values['_yith_wapo_options']) && !empty($values['_yith_wapo_options'])) {
        $item->add_meta_data('_yith_wapo_options', $values['_yith_wapo_options'], true);
    }
}


/* =========================================================
 * MQL - Presupuesto publico con productos YITH WAPO
 * Shortcode: [mql_presupuesto_publico]
 * URL publica con parametro cifrado: ?mqlq=TOKEN
 * Cliente Optimus forzado: PRUEBAS
 * ========================================================= */

function mql_public_quote_customer_code()
{
    return 'PRUEBAS';
}

/**
 * Clave interna para cifrar/firmar tokens.
 * Usa las salts de WordPress, no expone PRUEBAS ni el product_id en la URL.
 */
function mql_public_quote_secret_key()
{
    return hash('sha256', wp_salt('auth') . wp_salt('secure_auth') . 'mql_public_quote_secret', true);
}

function mql_base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function mql_base64url_decode($data)
{
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }

    return base64_decode(strtr($data, '-_', '+/'));
}

/**
 * Cifra payload para URL.
 */
function mql_public_quote_encrypt_payload(array $payload)
{
    $payload['ts'] = time();
    $payload['exp'] = time() + DAY_IN_SECONDS;

    $plain = wp_json_encode($payload);
    $key = mql_public_quote_secret_key();
    $iv = random_bytes(16);

    $cipher = openssl_encrypt($plain, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    if ($cipher === false) {
        return '';
    }

    $mac = hash_hmac('sha256', $iv . $cipher, $key, true);

    return mql_base64url_encode($iv . $mac . $cipher);
}

/**
 * Descifra y valida token de URL.
 */
function mql_public_quote_decrypt_payload($token)
{
    $raw = mql_base64url_decode((string) $token);

    if (!$raw || strlen($raw) <= 48) {
        return false;
    }

    $iv = substr($raw, 0, 16);
    $mac = substr($raw, 16, 32);
    $cipher = substr($raw, 48);

    $key = mql_public_quote_secret_key();
    $calc_mac = hash_hmac('sha256', $iv . $cipher, $key, true);

    if (!hash_equals($mac, $calc_mac)) {
        return false;
    }

    $plain = openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    if (!$plain) {
        return false;
    }

    $payload = json_decode($plain, true);

    if (!is_array($payload)) {
        return false;
    }

    if (empty($payload['exp']) || time() > (int) $payload['exp']) {
        return false;
    }

    return $payload;
}

function mql_get_public_quote_payload()
{
    static $payload = null;

    if ($payload !== null) {
        return $payload;
    }

    $payload = false;

    if (!empty($_GET['mqlq'])) {
        $token = sanitize_text_field(wp_unslash($_GET['mqlq']));
        $payload = mql_public_quote_decrypt_payload($token);
    }

    return $payload;
}

function mql_is_public_quote_request($data = null)
{
    if (mql_get_public_quote_payload()) {
        return true;
    }

    if (is_array($data)) {
        if (!empty($data['mql_public_quote_token'])) {
            return (bool) mql_public_quote_decrypt_payload($data['mql_public_quote_token']);
        }

        if (!empty($data['mql_public_quote'])) {
            return true;
        }
    }

    if (!empty($_REQUEST['mql_public_quote_token'])) {
        return (bool) mql_public_quote_decrypt_payload(
            sanitize_text_field(wp_unslash($_REQUEST['mql_public_quote_token']))
        );
    }

    if (!empty($_REQUEST['mql_public_quote'])) {
        return true;
    }

    return false;
}

function mql_get_cod_optimus_for_price_request($dataToDb = [])
{
    if (mql_is_public_quote_request($dataToDb)) {
        return mql_public_quote_customer_code();
    }

    $current_user = wp_get_current_user();

    if ($current_user && !empty($current_user->api_id)) {
        return $current_user->api_id;
    }

    $user_id = get_current_user_id();

    if ($user_id) {
        $api_id = get_user_meta($user_id, 'api_id', true);

        if (!empty($api_id)) {
            return $api_id;
        }
    }

    return '';
}

/**
 * Productos que pertenecen a la plantilla/bloque YITH:
 * "Personalización productos"
 */
function mql_public_quote_product_names()
{
    return [
        'Encuadernación Rústica Pur',
        'Encuadernación Rústica Cosida',
        'Encuadernación Tapa Dura Pur',
        'Encuadernación Grapado',
        'Encuadernación Espiral',
        'Encuadernación Wire-o',
        'Encuadernación Tapa Dura Lomo Cuadrado',
        'Encuadernación Tapa Dura Espiral',
        'Encuadernación Tapa Dura Wire-o',
        'Personalizado',
    ];
}

function mql_normalize_public_quote_text($text)
{
    $text = remove_accents((string) $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '', $text);

    return $text;
}

function mql_find_public_quote_product_by_name($wanted_name)
{
    if (!class_exists('WooCommerce')) {
        return null;
    }

    $wanted_normalized = mql_normalize_public_quote_text($wanted_name);

    $products = wc_get_products([
        'status' => 'publish',
        'limit' => -1,
        'return' => 'objects',
    ]);

    foreach ($products as $product) {
        if (!$product) {
            continue;
        }

        if (mql_normalize_public_quote_text($product->get_name()) === $wanted_normalized) {
            return $product;
        }
    }

    return null;
}

function mql_get_public_quote_products()
{
    $result = [];

    foreach (mql_public_quote_product_names() as $product_name) {
        $product = mql_find_public_quote_product_by_name($product_name);

        if (!$product) {
            continue;
        }

        $token = mql_public_quote_encrypt_payload([
            'product_id' => $product->get_id(),
            'customer' => mql_public_quote_customer_code(),
            'flow' => 'public_quote',
        ]);

        if (empty($token)) {
            continue;
        }

        $result[] = [
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'url' => add_query_arg([
                'mqlq' => rawurlencode($token),
            ], get_permalink($product->get_id())),
        ];
    }

    return $result;
}

add_shortcode('mql_presupuesto_publico', 'mql_render_presupuesto_publico_selector');

function mql_render_presupuesto_publico_selector()
{
    $products = mql_get_public_quote_products();

    ob_start();
    ?>
    <div class="mql-presupuesto-publico-selector">
        <h2>Presupuesto sin cuenta de cliente</h2>
        <p>Selecciona el producto para cargar su plantilla de personalización.</p>

        <p>
            <label for="mql_producto_publico"><strong>Producto</strong></label>
            <select id="mql_producto_publico">
                <option value="">Selecciona un producto</option>

                <?php foreach ($products as $product): ?>
                    <option value="<?php echo esc_url($product['url']); ?>">
                        <?php echo esc_html($product['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <button type="button" id="mql_abrir_producto_publico" class="button" disabled>
            Continuar
        </button>

        <?php if (empty($products)): ?>
            <div class="mql-error" style="margin-top:15px;">
                No se han encontrado productos publicados con los nombres configurados.
            </div>
        <?php endif; ?>
    </div>

    <script>
        jQuery(function ($) {
            const $producto = $('#mql_producto_publico');
            const $btn = $('#mql_abrir_producto_publico');

            $producto.on('change', function () {
                $btn.prop('disabled', !$(this).val());
            });

            $btn.on('click', function () {
                const url = $producto.val();

                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>

    <style>
        .mql-presupuesto-publico-selector {
            max-width: 760px;
            margin: 0 auto;
            padding: 24px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 14px;
        }

        .mql-presupuesto-publico-selector select {
            width: 100%;
            max-width: 100%;
            padding: 10px;
        }

        .mql-presupuesto-publico-selector button {
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .mql-error {
            padding: 14px;
            background: #fff0f0;
            border: 1px solid #f0b7b7;
            border-radius: 10px;
        }
    </style>
    <?php

    return ob_get_clean();
}

/**
 * En ficha de producto publica:
 * - valida token
 * - añade campos ocultos al form
 * - añade token a llamadas AJAX de tabla_precios_controller
 */
add_action('wp_footer', 'mql_public_quote_product_page_script', 50);

function mql_public_quote_product_page_script()
{
    if (!is_product()) {
        return;
    }

    $payload = mql_get_public_quote_payload();

    if (!$payload || empty($payload['product_id'])) {
        return;
    }

    global $product;

    if (!$product || (int) $product->get_id() !== (int) $payload['product_id']) {
        return;
    }

    $token = sanitize_text_field(wp_unslash($_GET['mqlq']));
    ?>
    <script>
        jQuery(function ($) {
            const mqlPublicQuoteToken = <?php echo wp_json_encode($token); ?>;

            function mqlPreparePublicQuoteForm() {
                const $forms = $('form.cart');

                $forms.each(function () {
                    const $form = $(this);

                    if (!$form.find('input[name="mql_public_quote"]').length) {
                        $form.append('<input type="hidden" name="mql_public_quote" value="1">');
                    }

                    if (!$form.find('input[name="mql_public_quote_token"]').length) {
                        $form.append(
                            $('<input>', {
                                type: 'hidden',
                                name: 'mql_public_quote_token',
                                value: mqlPublicQuoteToken
                            })
                        );
                    }
                });
            }

            mqlPreparePublicQuoteForm();
            setTimeout(mqlPreparePublicQuoteForm, 300);
            setTimeout(mqlPreparePublicQuoteForm, 1000);

            $(document).ajaxSend(function (event, jqxhr, settings) {
                if (!settings || !settings.data) {
                    return;
                }

                if (typeof settings.data === 'string' && settings.data.indexOf('action=tabla_precios_controller') !== -1) {
                    if (settings.data.indexOf('mql_public_quote=') === -1) {
                        settings.data += '&mql_public_quote=1';
                    }

                    if (settings.data.indexOf('mql_public_quote_token=') === -1) {
                        settings.data += '&mql_public_quote_token=' + encodeURIComponent(mqlPublicQuoteToken);
                    }
                }
            });
        });
    </script>
    <?php
}

function redirect_non_logged_users_to_login()
{
    if (!is_user_logged_in() && !is_admin()) {
        if (function_exists('mql_is_public_quote_request') && mql_is_public_quote_request()) {
            return;
        }
        $login_page_url = get_site_url() . '/iniciar-sesion/';
        wp_redirect($login_page_url);
        exit;
    }
}

add_action('woocommerce_before_shop_loop', 'redirect_non_logged_users_to_login');
add_action('woocommerce_before_single_product', 'redirect_non_logged_users_to_login');

//CODIGO MQL
/*add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity) {
    wc_add_notice('Las compras están desactivadas temporalmente.', 'error');
    return false;
}, 9999, 3);*/

function getConfigUrlOptimus($optimusUri)
{
    $urlBase = "http://81.42.209.224:8080/optwebsvcs/";
    $configDbOptimus = "masquelibros";
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
    $data->paymentCondition = 'TCR00';
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
    $curlResult = sendXmlOptimus($dataXml, 'enqbuilder');
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
            return ['success' => false, 'type' => 'pedido', 'error_message' => $codOptimus['message']];
        }
    }
    return ['success' => false, 'type' => 'oferta', 'error_message' => $curlResult->error];
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

//MQL - CAMPOS NUEVOS DE REGISTRO
add_action('woocommerce_register_form', 'add_custom_fields_to_registration_form');
function add_custom_fields_to_registration_form()
{
    ?>
    <p class="form-row form-row-wide">
        <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span
                    class="required">*</span></label>
        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password"
               id="reg_password" autocomplete="new-password"/>
    </p>
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
        <label for="payment_type"><?php esc_html_e('Payment methods', 'woocommerce'); ?> <span
                    class="required">*</span></label>
        <select name="payment_type" required id="payment_type" class="input-text">
            <option value="transferencia"><?php esc_html_e('Transferencia', 'woocommerce'); ?></option>
            <option value="tarjeta_credito"><?php esc_html_e('Tarjeta de crédito', 'woocommerce'); ?></option>
        </select>
    </p>
    <?php
}

// Procesar registro en página personalizada
add_action('init', 'mi_registro_personalizado');
function mi_registro_personalizado()
{
    // Solo en la página personalizada "iniciar-sesion"
    if (strpos($_SERVER['REQUEST_URI'], '/iniciar-sesion') !== false && isset($_POST['mi_registro_nonce']) && wp_verify_nonce($_POST['mi_registro_nonce'], 'mi_registro_action')) {
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $username = $_POST['name'];

        // Validar campos
        if (empty($email) || empty($password)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=Rellene todos los campos'));
            exit;
        }

        if (!is_email($email)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=El email no es válido'));
            exit;
        }

        if (email_exists($email)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=El email ya existe.'));
            exit;
        }

        if (username_exists($username)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=El nombre de usuario ya existe.'));
            exit;
        }

        $nextCustomerCode = getLastOptimusCode();
        $optimusResponse = addUserToOptimus($_POST, $nextCustomerCode);
        if ($optimusResponse['status'] !== 'success') {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=' . $optimusResponse['message']));
            exit;
        }

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=Error al crear su cuenta'));
            exit;
        }

        update_user_meta($user_id, 'api_id', $nextCustomerCode);
        update_user_meta($user_id, 'name', sanitize_text_field($_POST['name'] ?? ''));
        update_user_meta($user_id, 'cif', sanitize_text_field($_POST['cif'] ?? ''));
        update_user_meta($user_id, 'payment_type', sanitize_text_field($_POST['payment_type'] ?? ''));
        update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone_number'] ?? ''));


        global $wpdb;
        $wpdb->update(
            $wpdb->users,
            [
                'api_id' => $nextCustomerCode,
                'name' => sanitize_text_field($_POST['name'] ?? ''),
                'cif' => sanitize_text_field($_POST['cif'] ?? ''),
                'phone_number' => sanitize_text_field($_POST['phone_number'] ?? ''),
                'payment_type' => sanitize_text_field($_POST['payment_type'] ?? ''),
            ],
            ['ID' => $user_id],
            ['%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );

        // Asignar rol "customer" (WooCommerce)
        wp_update_user([
            'ID' => $user_id,
            'role' => 'customer',
        ]);

        // 🔐 Login automático
        $creds = array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => true,
        );

        $subject = 'Nuevo registro';
        $message = "
            Aviso de nuevo registro, estos son los datos:
            Usuario: " . $username . ",
            Email: " . $email . ",
            Codigo optimus: " . $nextCustomerCode . "
        ";
        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        wp_mail('soporte@masquelibrosdigital.com', $subject, $message, $headers);

        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            wp_redirect(home_url('/iniciar-sesion?registro=error&msg=Error al iniciar sesión automáticamente.'));
            exit;
        }

        wp_redirect(home_url('/iniciar-sesion?registro=exito'));
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
                $acabados_map = ['2e_barn', '2e_esta', '2e_troq', '2e_golp'];
                $key = $acabados_map[$counters['acabados2e']++] ?? end($acabados_map);
                $value = 1;
            }

            if ($key === '4e_acabados_check') {
                $acabados_map = ['4e_barn', '4e_esta', '4e_troq', '4e_golp'];
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

    //NUEVO - AÑADIMOS TIPO IMPRESION
    $dataToDb = aplicarLogicaKeysEncontradas($dataToDb, 'e_elem', function (&$dataToDb, $findKey) {
        if ($findKey !== null) {
            $target =& $dataToDb[$findKey];
        } else {
            $target =& $dataToDb;
        }

        foreach (array_keys($target) as $key) {
            if (preg_match('/^(\d+)e_elem$/', $key, $m)) {
                $num = (int)$m[1];
                $tipoKey = $num . 'e_tipo_imp';

                // Si ya está marcado como "NO IMPRES HOJA", lo respetamos.
                if (isset($target[$tipoKey]) && $target[$tipoKey] === 'NO IMPRES HOJA') {
                    continue;
                }

                $target[$tipoKey] = (in_array($num, [2, 4], true)) ? 'TONER' : 'INKJET';
            }
        }
    }, true);

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
                if ($papelEx[1] < 100)
                    $target[$elemType . 'e_tipo_grm'] = '0' . $papelEx[1];
                else
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

function getTipoOptimus($tipo)
{
    $mapa = [
        0 => 'INTERIOR',
        1 => 'INTERIOR 2',
        2 => 'CUBIERTA',
        3 => 'GUARDAS',
        4 => 'SOBRECUBIERTAS',
        5 => 'FAJA',
        6 => 'MARCAPAGINAS',
        7 => 'DESPLEGABLE'
    ];

    return $mapa[$tipo] ?? null;
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
        $data->line->productVariable[$countPV]->name = $tipo . 'e_tipo';
        $data->line->productVariable[$countPV]->type = 'string';
        $data->line->productVariable[$countPV]->value = getTipoOptimus($tipo);
        $countPV++;
    }

    //añadimos cantidad a la dirección
    $cantidad = isset($dataOptimus['quantity']['quantity'][0])
        ? (int)$dataOptimus['quantity']['quantity'][0]
        : 0;

    $dataOptimus['productVariable']['e_ent_00_cnt_01'] = floatval($cantidad);

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
            if (
                substr($key, -7) === 'e_ancho' ||  // e_ancho, 0e_ancho, 2e_ancho...
                substr($key, -6) === 'e_alto' ||  // e_alto, 0e_alto, 2e_alto...
                strpos($key, '_paginas') !== false ||
                strpos($key, 'ent_00_cnt_0') !== false
            ) {
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

    if (!empty($result)) {
        $result['xml'] = $dataXml;
        return $result;
    }

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

// Cambiar "Redsys" por "Tarjeta de crédito"
add_filter('woocommerce_order_get_payment_method_title', 'cambiar_titulo_redsys_pedido', 10, 2);
function cambiar_titulo_redsys_pedido($title, $order)
{
    if ($order->get_payment_method() === 'redsys') {
        return 'Tarjeta de crédito';
    }
    return $title;
}

// Quitar botón "Volver a pedir"
remove_action('woocommerce_order_details_after_order_table', 'woocommerce_order_again_button');

add_action('wp_footer', function () {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const botonVolver = document.getElementById('b_volv_mql');
            if (botonVolver) {
                botonVolver.addEventListener('click', function (event) {
                    event.preventDefault();
                    history.back();
                });
            }
        });
    </script>
    <?php
});

/* ===========================
 *  MQL Chat – todo en functions.php
 * =========================== */

// (Opcional) define la API key en wp-config.php: define('OPENAI_API_KEY','sk-...');
// o configúrala como variable de entorno OPENAI_API_KEY

/* 1) Endpoint REST que actúa como proxy a OpenAI */
add_action('rest_api_init', function () {
    register_rest_route('mql/v1', '/chat', [
        'methods' => 'POST',
        'callback' => 'mql_chat_handler',
        'permission_callback' => '__return_true', // añade tus checks si quieres
    ]);
});

/**
 * Extrae “needles” (palabras clave) del mensaje del usuario
 * y normaliza variantes (wire o / wire-o, rústica/tapa blanda…).
 */
function mql_extract_needles($msg)
{
    $s = mb_strtolower((string)$msg, 'UTF-8');
    $s = preg_replace('/\s+/', ' ', $s);
    $s = str_replace(['wire o', 'wireo'], 'wire-o', $s);

    $needles = [];

    // compuestos primero
    if (preg_match('/tapa\s+dura/u', $s)) $needles[] = 'tapa dura';
    if (preg_match('/tapa\s+blanda|r[uú]stica|rustica/u', $s)) $needles[] = 'tapa blanda'; // rústica ~ tapa blanda

    // simples
    if (preg_match('/wire-?o/u', $s)) $needles[] = 'wire-o';
    if (preg_match('/espiral/u', $s)) $needles[] = 'espiral';
    if (preg_match('/grapa(?:do)?/u', $s)) $needles[] = 'grapado';
    if (preg_match('/cosido/u', $s)) $needles[] = 'cosido';
    if (preg_match('/pur/u', $s)) $needles[] = 'pur';

    // si no detecta nada, usa tokens >2 chars (quitando palabras vacías)
    if (empty($needles)) {
        $tokens = array_filter(preg_split('/\s+/u', $s), fn($t) => mb_strlen($t, 'UTF-8') >= 3);
        $stop = ['con', 'para', 'los', 'las', 'una', 'unos', 'unas', 'del', 'de', 'en', 'y', 'o', 'el', 'la', 'por', 'que', 'un', 'al'];
        $needles = array_values(array_diff($tokens, $stop));
    }

    return array_values(array_unique($needles));
}

/**
 * Busca productos cuyo TÍTULO contenga TODOS los needles (AND),
 * case/acentos-insensible, con alias por término.
 *
 * @param string[] $needles
 * @param int $limit
 * @param string $categorySlug (opcional)
 * @param bool $onlyStock
 * @return WC_Product[]
 */
function mql_search_by_title_contains(array $needles, $limit = 20, $categorySlug = null, $onlyStock = false)
{
    global $wpdb;
    if (empty($needles)) return [];

    // alias para variantes ortográficas
    $aliases = [
        'wire-o' => ['wire-o', 'wire o', 'wireo'],
        'tapa dura' => ['tapa dura'],
        'tapa blanda' => ['tapa blanda', 'rústica', 'rustica'],
        'espiral' => ['espiral'],
        'grapado' => ['grapado', 'grapa'],
        'cosido' => ['cosido'],
        'pur' => ['pur'],
    ];

    $orGlobal = [];
    $params = [];
    foreach ($needles as $n) {
        $n = mb_strtolower(trim($n), 'UTF-8');
        $vars = $aliases[$n] ?? [$n];
        foreach ($vars as $v) {
            $like = '%' . $wpdb->esc_like(mb_strtolower($v, 'UTF-8')) . '%';
            $orGlobal[] = "LOWER(p.post_title) COLLATE utf8mb4_unicode_ci LIKE %s";
            $params[] = $like;
        }
    }
    $whereAND = '(' . implode(' OR ', $orGlobal) . ')';

    // categoría (opcional)
    $catSQL = '';
    if (!empty($categorySlug)) {
        $term = get_term_by('slug', sanitize_title($categorySlug), 'product_cat');
        if ($term && !is_wp_error($term)) {
            $catSQL = $wpdb->prepare("
                AND EXISTS (
                  SELECT 1 FROM {$wpdb->term_relationships} tr
                  JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  WHERE tr.object_id = p.ID
                    AND tt.taxonomy = 'product_cat'
                    AND tt.term_id = %d
                )
            ", (int)$term->term_id);
        }
    }

    // stock (opcional)
    $stockSQL = '';
    if ($onlyStock) {
        $stockSQL = "
            AND EXISTS (
              SELECT 1 FROM {$wpdb->postmeta} pm
              WHERE pm.post_id = p.ID AND pm.meta_key = '_stock_status' AND pm.meta_value = 'instock'
            )
        ";
    }

    $sql = "
        SELECT p.ID
        FROM {$wpdb->posts} p
        WHERE p.post_type='product' AND p.post_status='publish'
          {$catSQL}
          {$stockSQL}
          AND {$whereAND}
        ORDER BY p.menu_order ASC, p.post_title ASC
        LIMIT " . (int)$limit;

    $prepared = $wpdb->prepare($sql, $params);
    $ids = $wpdb->get_col($prepared);

    // cargar productos y filtrar visibilidad (excluir 'hidden')
    $out = [];
    $seen = [];
    foreach ((array)$ids as $id) {
        $p = wc_get_product($id);
        if (!$p || $p->get_status() !== 'publish') continue;
        if ($p->get_catalog_visibility() === 'hidden') continue;
        if ($onlyStock && !$p->is_in_stock()) continue;
        if (isset($seen[$id])) continue;
        $seen[$id] = true;
        $out[] = $p;
    }
    return $out;
}

function mql_chat_handler(WP_REST_Request $req)
{
    $msg = trim((string)($req->get_param('message') ?? ''));

    if ($msg === '') {
        return new WP_REST_Response([
            'reply' => 'Escribe una pregunta para poder ayudarte.'
        ], 200);
    }

    /*
     * Límite básico por IP para evitar abuso del endpoint.
     * Ajusta 20 y 5 minutos si quieres más o menos margen.
     */
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $rate_key = 'mql_chat_rl_' . md5($ip);
    $rate_count = (int) get_transient($rate_key);

    if ($rate_count >= 20) {
        return new WP_REST_Response([
            'reply' => 'Has hecho muchas consultas seguidas. Espera unos minutos y vuelve a intentarlo.'
        ], 200);
    }

    set_transient($rate_key, $rate_count + 1, 5 * MINUTE_IN_SECONDS);

    // API key desde entorno o wp-config.php
    $api_key = getenv('OPENAI_API_KEY');
    if (!$api_key && defined('OPENAI_API_KEY')) {
        $api_key = OPENAI_API_KEY;
    }

    // ---------- DETECCIÓN INTENCIÓN ----------
    $isProductIntent = preg_match(
        '/(libro|libros|impresi[oó]n|encuadernaci[oó]n|tapa|papel|formato|tirada|precio|cat[aá]logo|stock|recomienda|recomendaci[oó]n|cotizaci[oó]n|presupuesto|espiral|wire-?o|grapa(?:do)?|cosido|pur|r[uú]stica|rustica)/i',
        $msg
    );

    $found = [];

    if ($isProductIntent && class_exists('WooCommerce')) {
        $needles = mql_extract_needles($msg);
        $found = mql_search_by_title_contains($needles, 30, null, false);

        // Si quieres activar también la búsqueda general, descomenta esta parte:
        // if (empty($found)) {
        //     $found = mql_catalog_search($msg, ['limit' => 12, 'stock' => false]);
        // }
    }

    // ---------- SI HAY PRODUCTOS: DEVOLVEMOS HTML ----------
    if (!empty($found)) {
        $items = [];

        foreach ($found as $p) {
            $pl = mql_product_to_payload($p);

            $items[] = sprintf(
                '<li class="mql-item"><a href="%s" target="_blank" rel="noopener" class="mql-item-link"><strong>%s</strong></a></li>',
                esc_url($pl['link']),
                esc_html($pl['name'])
            );
        }

        $html = '<div class="mql-list-wrap">
                    <div class="mql-list-title">Opciones disponibles en catálogo:</div>
                    <ul class="mql-list">' . implode("\n", $items) . '</ul>
                 </div>';

        return new WP_REST_Response(['reply_html' => $html], 200);
    }

    /*
     * Si la intención era de catálogo y no hay coincidencias,
     * evitamos gastar llamada a OpenAI. Esto reduce mucho los 429.
     */
    if ($isProductIntent) {
        return new WP_REST_Response([
            'reply' => 'No he encontrado una coincidencia clara en el catálogo. Prueba indicando tipo de encuadernación, tamaño, número de páginas, papel o tirada.'
        ], 200);
    }

    // Si no hay API key, responder sin romper el chat.
    if (!$api_key) {
        error_log('[MQL Chat] Falta OPENAI_API_KEY');

        return new WP_REST_Response([
            'reply' => 'Ahora mismo el asistente IA no está configurado. Aun así puedo ayudarte con dudas sobre impresión, encuadernación, papel, formatos o presupuesto.'
        ], 200);
    }

    /*
     * Caché para preguntas repetidas.
     * Evita gastar tokens si varios usuarios preguntan lo mismo.
     */
    $cache_key = 'mql_chat_reply_' . md5(mb_strtolower($msg, 'UTF-8'));
    $cached_reply = get_transient($cache_key);

    if (!empty($cached_reply)) {
        return new WP_REST_Response(['reply' => $cached_reply], 200);
    }

    // ---------- IA con reglas ----------
    $rules = mql_get_business_rules();

    $rules_text = "Reglas de negocio:\n- " . implode("\n- ", array_map(
            function ($k, $v) {
                return ucfirst($k) . ": " . $v;
            },
            array_keys($rules),
            $rules
        ));

    $guardrails = "Si la intención es de catálogo, no inventes productos. Pide más detalles si no hay coincidencias.";

    $messages = [
        [
            'role' => 'system',
            'content' => "Eres el asistente de masquelibrosdigital.com. Responde en español, claro y conciso. No inventes datos.\n$rules_text\n$guardrails"
        ],
        [
            'role' => 'user',
            'content' => $msg
        ],
    ];

    $body = [
        'model' => 'gpt-4o-mini',
        'messages' => $messages,
        'temperature' => 0.2,
        'max_tokens' => 350,
    ];

    $res = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
        'timeout' => 30,
    ]);

    if (is_wp_error($res)) {
        error_log('[MQL Chat] Error WordPress HTTP: ' . $res->get_error_message());

        return new WP_REST_Response([
            'reply' => 'Ahora mismo no puedo conectar con la IA. Puedo ayudarte igualmente si me indicas tipo de libro, páginas, tamaño, papel y tirada.'
        ], 200);
    }

    $code = (int) wp_remote_retrieve_response_code($res);
    $raw_body = wp_remote_retrieve_body($res);
    $json = json_decode($raw_body, true);

    /*
     * IMPORTANTE:
     * No devolvemos 429 al navegador.
     * Devolvemos siempre 200 con un mensaje entendible para que el chat no se rompa.
     */
    if ($code === 429) {
        $api_error = $json['error']['message'] ?? 'Too Many Requests';
        error_log('[MQL Chat] OpenAI 429: ' . $api_error);

        return new WP_REST_Response([
            'reply' => 'Ahora mismo el asistente IA está saturado o ha alcanzado su límite de uso. Prueba de nuevo en unos minutos. Mientras tanto, puedo orientarte si me dices tipo de encuadernación, páginas, formato, papel y tirada.'
        ], 200);
    }

    if ($code >= 400) {
        $api_error = $json['error']['message'] ?? ('Error API HTTP ' . $code);
        error_log('[MQL Chat] OpenAI error ' . $code . ': ' . $api_error);

        return new WP_REST_Response([
            'reply' => 'Ahora mismo la IA no ha podido responder. Prueba de nuevo más tarde o indícame los datos del libro para orientarte manualmente.'
        ], 200);
    }

    $text = $json['choices'][0]['message']['content'] ?? '';

    if (trim($text) === '') {
        $text = 'Puedo ayudarte a configurar tu libro: tamaño, páginas, papel, encuadernación y tirada.';
    }

    set_transient($cache_key, $text, 12 * HOUR_IN_SECONDS);

    return new WP_REST_Response(['reply' => $text], 200);
}

/* 2) CSS: estilos del botón y panel (inline para simplificar) */
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    // Font Awesome (iconos)
    wp_enqueue_style(
        'fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Encola un “handle” vacío para poder adjuntar CSS inline
    wp_register_style('mql-chat-inline', false);
    wp_enqueue_style('mql-chat-inline');
    $css = <<<CSS
/* Launcher fijo abajo-derecha */
#mql-launcher{
  position: fixed !important;
  right: 20px !important;
  bottom: 20px !important;

  width: 56px !important;
  height: 56px !important;
  border-radius: 50% !important;

  border: none !important;
  cursor: pointer !important;

  background: #0d6efd !important;
  color: #fff !important;

  box-shadow: 0 8px 24px rgba(0,0,0,.2) !important;
  z-index: 9999 !important;

  /* centrado del contenido */
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;

  padding: 0 !important;
  line-height: 1 !important;
  text-align: center !important;
  box-sizing: border-box !important;
}

#mql-launcher:hover{
  filter: brightness(1.1);
}

/* Emoji centrado y sin “baseline raro” */
#mql-launcher .mql-emoji{
  display: block;
  font-size: 22px;
  line-height: 1;
}


#mql-chat-panel{
  position: fixed; right: 20px; bottom: 86px; z-index: 9999;
  width: 360px; max-width: 95vw;
  transition: opacity .2s ease, transform .2s ease;
}
#mql-chat-panel[hidden]{ opacity: 0; transform: translateY(10px); pointer-events: none; }
#mql-chat-panel.open{ opacity: 1; transform: translateY(0); }

.mql-chat-card{
  background: #fff; color: #212529; border-radius: 12px; overflow: hidden;
  box-shadow: 0 16px 48px rgba(0,0,0,.2); border: 1px solid rgba(0,0,0,.08);
  display: flex; flex-direction: column; height: 520px; max-height: 70vh;
}
@media (prefers-color-scheme: dark){
  .mql-chat-card{ background: #121212; color:#eaeaea; border-color: #333; }
}

.mql-chat-header{
  display: flex;
  align-items: center;         /* alinea verticalmente en el centro */
  justify-content: space-between;
  background: #0d6efd;
  color: #fff;
  font-size: 14px;
  line-height: 1;              /* reduce altura de línea */
  padding: 4px 8px;            /* menos espacio arriba/abajo */
  min-height: 32px;            /* fija altura compacta */
}

.mql-chat-header strong{
  font-weight: 600;
  margin: 0;                   /* elimina márgenes extra */
}

.mql-chat-actions{
  display: flex;
  gap: 4px;                    /* separación mínima entre botones */
}

.mql-chat-actions button{
  background: transparent;
  border: 0;
  color: #fff;
  font-size: 14px;
  width: 24px;
  height: 24px;
  line-height: 24px;
  border-radius: 4px;
  padding: 0;                  /* elimina relleno interno extra */
  display: flex;
  align-items: center;
  justify-content: center;
}
.mql-chat-actions button:hover{
  background: rgba(255,255,255,.2);
}

.mql-chat-body{
  padding: 12px; flex: 1; overflow:auto; white-space: pre-wrap;
  scrollbar-width: thin;
  background: rgba(13,110,253,.03);
}
.mql-msg{ margin: 0 0 10px 0; max-width: 85%; }
.mql-user{ margin-left: auto; text-align: right; }
.mql-bot{ margin-right: auto; }
.mql-badge{
  display:inline-block; font-size:12px; padding:2px 6px; border-radius:999px; margin-bottom:4px;
}
.mql-badge.user{ background:#0d6efd; color:#fff; }
.mql-badge.bot { background:#6c757d; color:#fff; }

.mql-bubble{
  padding:8px 10px; border-radius:10px;
  background:#fff; border:1px solid rgba(0,0,0,.07);
}
@media (prefers-color-scheme: dark){
  .mql-bubble{ background:#1b1b1b; border-color:#333; }
}

.mql-chat-footer{
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  padding: 10px !important;
  border-top: 1px solid rgba(0,0,0,.08) !important;
  background: #fff !important;
  box-sizing: border-box !important;
}

#mql-chat-input{
  flex: 1 1 auto !important;
  min-width: 0 !important;
  height: 42px !important;
  padding: 10px 12px !important;
  border-radius: 8px !important;
  border: 1px solid #ced4da !important;
  background: #fff !important;
  color: #212529 !important;
  font-size: 15px !important;
  line-height: 1.2 !important;
  box-sizing: border-box !important;
  margin: 0 !important;
}

#mql-chat-send{
  flex: 0 0 44px !important;
  width: 44px !important;
  height: 42px !important;
  min-width: 44px !important;
  max-width: 44px !important;
  border: 0 !important;
  border-radius: 8px !important;
  background: #0d6efd !important;
  color: #fff !important;
  cursor: pointer !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  padding: 0 !important;
  margin: 0 !important;
  line-height: 1 !important;
  box-sizing: border-box !important;
}

#mql-chat-send i{
  font-size: 16px !important;
  line-height: 1 !important;
  pointer-events: none !important;
}

#mql-chat-send:hover{
  filter: brightness(1.08) !important;
}

#mql-chat-send:disabled{
  opacity: .6 !important;
  cursor: not-allowed !important;
}

@media (prefers-color-scheme: dark){
  .mql-chat-footer{
    background: #121212 !important;
    border-top-color: #333 !important;
  }

  #mql-chat-input{
    background: #1b1b1b !important;
    color: #eaeaea !important;
    border-color: #444 !important;
  }
}

.mql-list-wrap { margin: 4px 0 6px; }
.mql-list-title { font-weight: 600; margin-bottom: 6px; }
.mql-list { list-style: none; padding: 0; margin: 0; }
.mql-list .mql-item { display: flex; align-items: baseline; gap: 8px; padding: 6px 8px; border: 1px solid #e5e5e5; border-radius: 6px; margin-bottom: 6px; background: #fafafa; }
.mql-item-link { text-decoration: none; color: #0d6efd; }
.mql-item-link:hover { text-decoration: underline; }
.mql-price { color: #0d6efd; font-weight: 600; }
.mql-sep { margin: 0 6px; color:#ccc; }
.mql-stock.ok { color: #198754; }
.mql-stock.ko { color: #dc3545; }


CSS;
    wp_add_inline_style('mql-chat-inline', $css);
});

/* 3) HTML + JS: botón flotante y panel (impresos en el footer) */
add_action('wp_footer', function () {
    if (is_admin()) return; // no en dashboard
    $endpoint = esc_url(site_url('/wp-json/mql/v1/chat'));
    ?>
    <!-- MQL Chat Floating -->
    <button id="mql-launcher"
            aria-label="<?php esc_attr_e('Abrir chat', 'mql'); ?>"
            aria-controls="mql-chat-panel"
            aria-expanded="false"><span class="mql-emoji" aria-hidden="true">💬</span></button>

    <div id="mql-chat-panel" role="dialog" aria-modal="false" aria-labelledby="mql-chat-title" hidden>
        <div class="mql-chat-card">
            <div class="mql-chat-header">
                <strong id="mql-chat-title"><?php esc_html_e('Asistente', 'mql'); ?></strong>
                <div class="mql-chat-actions">
                    <button id="mql-minimize" aria-label="<?php esc_attr_e('Minimizar', 'mql'); ?>">—</button>
                    <button id="mql-close" aria-label="<?php esc_attr_e('Cerrar', 'mql'); ?>">✕</button>
                </div>
            </div>
            <div id="mql-chat-log" class="mql-chat-body" tabindex="0" aria-live="polite"></div>
            <div class="mql-chat-footer">
                <input
                        id="mql-chat-input"
                        type="text"
                        placeholder="<?php esc_attr_e('Escriba aquí su consulta…', 'mql'); ?>"
                        autocomplete="off">
                <button id="mql-chat-send" type="button" aria-label="Enviar mensaje">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const launcher = document.getElementById('mql-launcher');
            const panel = document.getElementById('mql-chat-panel');
            const log = document.getElementById('mql-chat-log');
            const input = document.getElementById('mql-chat-input');
            const sendBtn = document.getElementById('mql-chat-send');

            if (!input || !sendBtn) {
                console.error('MQL Chat: no se encontró el input o el botón de enviar.');
                return;
            }
            const closeBtn = document.getElementById('mql-close');
            const miniBtn = document.getElementById('mql-minimize');
            const ENDPOINT = <?php echo json_encode($endpoint); ?>;

            let welcomeShown = false;
            let waitingForAnotherQuestion = false;

            function showWelcomeMessage() {
                if (welcomeShown) return;
                welcomeShown = true;

                append(
                    'bot',
                    'Bienvenido, le habla el asistente virtual de Masquelibros. Por favor, díganos qué producto quiere consultar.'
                );
            }

            function openPanel() {
                panel.hidden = false;
                setTimeout(() => panel.classList.add('open'), 10);
                launcher.setAttribute('aria-expanded', 'true');
                showWelcomeMessage();
                input.focus();
            }

            function closePanel() {
                panel.classList.remove('open');
                launcher.setAttribute('aria-expanded', 'false');
                setTimeout(() => panel.hidden = true, 200);
            }

            function togglePanel() {
                if (panel.hidden || !panel.classList.contains('open')) {
                    openPanel();
                } else {
                    closePanel();
                }
            }

            launcher.addEventListener('click', togglePanel);
            closeBtn.addEventListener('click', closePanel);

            miniBtn.addEventListener('click', () => {
                panel.classList.toggle('open');

                if (panel.classList.contains('open')) {
                    showWelcomeMessage();
                    input.focus();
                }
            });

            function esc(s) {
                return (s || '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m]));
            }

            function append(role, text) {
                const wrap = document.createElement('div');
                wrap.className = 'mql-msg ' + (role === 'user' ? 'mql-user' : 'mql-bot');

                wrap.innerHTML =
                    `<div class="mql-badge ${role === 'user' ? 'user' : 'bot'}">${role === 'user' ? 'Tú' : 'IA Masquelibros'}</div>
         <div class="mql-bubble">${esc(text)}</div>`;

                log.appendChild(wrap);
                log.scrollTop = log.scrollHeight;
            }

            function appendHTML(role, html) {
                const wrap = document.createElement('div');
                wrap.className = 'mql-msg ' + (role === 'user' ? 'mql-user' : 'mql-bot');

                // allowlist muy básica de etiquetas/atributos:
                const allowed = /<(\/?(div|ul|ol|li|span|strong|b|em|i|a|br))( [^>]*?)?>/gi;

                const safe = html
                    .replace(/<\/?script[^>]*?>/gi, '')
                    .replace(/ on\w+="[^"]*"/gi, '')
                    .replace(/javascript:/gi, '')
                    .replace(/<[^>]+>/g, m => m.match(allowed) ? m : '');

                wrap.innerHTML =
                    `<div class="mql-badge bot">IA Masquelibros</div>
         <div class="mql-bubble">${safe}</div>`;

                log.appendChild(wrap);
                log.scrollTop = log.scrollHeight;
            }

            function appendLoading() {
                const wrap = document.createElement('div');
                wrap.className = 'mql-msg mql-bot';
                wrap.id = 'mql-loading';
                wrap.innerHTML = `<div class="mql-badge bot">IA Masquelibros</div><div class="mql-bubble">…</div>`;
                log.appendChild(wrap);
                log.scrollTop = log.scrollHeight;
            }

            function removeLoading() {
                const n = document.getElementById('mql-loading');
                if (n) n.remove();
            }

            function normalizeText(text) {
                return (text || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .trim();
            }

            function isNegativeAnswer(text) {
                const t = normalizeText(text);

                return /^(no|no gracias|nada mas|nada más|ninguna|ninguna mas|ninguna más|eso es todo|ya esta|ya está|listo|ok no)$/i.test(t);
            }

            function isPositiveOnlyAnswer(text) {
                const t = normalizeText(text);

                return /^(si|sí|vale|ok|claro|otra|otra consulta|tengo otra duda|quiero otra consulta)$/i.test(t);
            }

            function askAnotherQuestion() {
                waitingForAnotherQuestion = true;
                append('bot', '¿Alguna otra consulta?');
            }

            function sayGoodbyeAndClose() {
                append('bot', 'De acuerdo, pase buen día.');

                setTimeout(() => {
                    closePanel();
                }, 1400);
            }

            async function ask(msg) {
                append('user', msg);

                /*
                 * Si el usuario está contestando a "¿Alguna otra consulta?"
                 * y dice que no, no llamamos al servidor: nos despedimos y cerramos.
                 */
                if (waitingForAnotherQuestion && isNegativeAnswer(msg)) {
                    waitingForAnotherQuestion = false;
                    sayGoodbyeAndClose();
                    return;
                }

                /*
                 * Si dice solo "sí", "vale", "otra", etc.,
                 * volvemos a pedirle qué producto quiere consultar.
                 */
                if (waitingForAnotherQuestion && isPositiveOnlyAnswer(msg)) {
                    waitingForAnotherQuestion = false;
                    append('bot', 'Perfecto, dígame qué producto quiere consultar.');
                    return;
                }

                /*
                 * Si estaba en "¿Alguna otra consulta?" pero escribe directamente
                 * una nueva duda o producto, lo tratamos como una nueva búsqueda.
                 */
                waitingForAnotherQuestion = false;

                appendLoading();
                sendBtn.disabled = true;

                try {
                    const res = await fetch(ENDPOINT, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({message: msg})
                    });

                    const data = await res.json();
                    removeLoading();

                    if (data.reply_html) {
                        appendHTML('bot', data.reply_html);
                        askAnotherQuestion();
                    } else if (data.reply) {
                        append('bot', data.reply);
                        askAnotherQuestion();
                    } else if (data.error) {
                        append('bot', data.error);
                        askAnotherQuestion();
                    } else {
                        append('bot', 'Ahora mismo no he podido responder. Pruebe de nuevo en unos minutos.');
                        askAnotherQuestion();
                    }

                } catch (e) {
                    removeLoading();
                    append('bot', 'Error de red. Inténtelo de nuevo.');
                } finally {
                    sendBtn.disabled = false;
                    input.focus();
                }
            }

            function sendCurrentMessage() {
                const msg = input.value.trim();
                if (!msg || sendBtn.disabled) return;

                input.value = '';
                ask(msg);
            }

            sendBtn.addEventListener('click', function (e) {
                e.preventDefault();
                sendCurrentMessage();
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendCurrentMessage();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !panel.hidden) closePanel();
            });
        })();
    </script>
    <!-- //MQL Chat Floating -->
    <?php
});


//BUSCADOR CHAT

/* ==== MQL: BÚSQUEDA ROBUSTA DE PRODUCTOS (WooCommerce) ==== */
add_action('rest_api_init', function () {
    register_rest_route('mql/v1', '/find-products', [
        'methods' => 'GET',
        'callback' => 'mql_find_products_handler',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * Busca productos por: ID, SKU exacto, SKU parcial, título, contenido.
 * Filtros opcionales: categoría (slug) y stock.
 */
function mql_find_products_handler(WP_REST_Request $req)
{
    if (!class_exists('WooCommerce')) {
        return new WP_REST_Response(['error' => 'WooCommerce no está activo'], 400);
    }
    $q = sanitize_text_field($req->get_param('q') ?? '');
    $limit = max(1, min(20, intval($req->get_param('limit') ?? 8)));
    $cat = sanitize_text_field($req->get_param('cat') ?? '');
    $onstock = $req->get_param('stock') === '1';

    $products = mql_catalog_search($q, [
        'limit' => $limit,
        'category' => $cat ?: null,
        'stock' => $onstock,
    ]);

    $data = array_map('mql_product_to_payload', $products);
    return ['count' => count($data), 'results' => $data];
}

/**
 * Búsqueda con relevancia para WooCommerce: ID, SKU, título y contenido.
 * Puntúa candidatos y devuelve top-N por score. Evita devolver siempre lo mismo.
 * @param string $query
 * @param array $opts [limit, category(slug), stock(bool)]
 * @return WC_Product[]
 */
function mql_catalog_search($query, array $opts = [])
{
    global $wpdb;

    $limit = isset($opts['limit']) ? max(1, min(50, (int)$opts['limit'])) : 8;
    $catSlug = !empty($opts['category']) ? sanitize_title($opts['category']) : null;
    $onlyStock = !empty($opts['stock']);
    $qraw = trim((string)$query);

    // Normalización básica
    $norm = function ($s) {
        $s = preg_replace('/\s+/u', ' ', $s);
        $s = mb_strtolower($s, 'UTF-8');
        return $s;
    };
    $qnorm = $norm($qraw);

    // Tokens (palabras de 2+ chars) – así “A5” también cuenta
    $tokens = array_values(array_filter(
        preg_split('/\s+/u', $qnorm),
        function ($t) {
            return mb_strlen($t, 'UTF-8') >= 2;
        }
    ));
    if (empty($tokens)) {
        // sin tokens ⇒ no devolvemos un listado genérico (evita repetir siempre)
        return [];
    }

    // Mapa sinónimos opcional
    $synonyms = [
        'wire o' => 'wire-o',
        'wireo' => 'wire-o',
        'grapa' => 'grapado',
        'tapa dura' => 'tapa dura', // ejemplo
    ];
    foreach ($synonyms as $a => $b) {
        $qnorm = str_replace($a, $b, $qnorm);
        $tokens = array_map(function ($t) use ($a, $b) {
            return str_replace($a, $b, $t);
        }, $tokens);
    }

    // Filtro por categoría (SQL)
    $termFilterSQL = '';
    if ($catSlug) {
        $term = get_term_by('slug', $catSlug, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $term_id = (int)$term->term_id;
            $termFilterSQL = $wpdb->prepare("
                AND EXISTS (
                  SELECT 1
                  FROM {$wpdb->term_relationships} tr
                  JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  WHERE tr.object_id = p.ID
                    AND tt.taxonomy = 'product_cat'
                    AND tt.term_id = %d
                )
            ", $term_id);
        }
    }

    // Colección de candidatos con puntuación
    $scores = []; // [product_id => score]
    $touch = function ($pid, $pts) use (&$scores) {
        $scores[$pid] = ($scores[$pid] ?? 0) + $pts;
    };

    // Helper convertir IDs a productos y filtrar
    $add_products_by_ids = function (array $ids, $score) use (&$touch) {
        foreach ($ids as $id) {
            if ($id) $touch((int)$id, $score);
        }
    };

    // 1) ID exacto
    if (ctype_digit($qraw)) {
        $p = wc_get_product((int)$qraw);
        if ($p && $p->get_status() === 'publish') $touch($p->get_id(), 100);
    }

    // 2) SKU exacto
    $pid = wc_get_product_id_by_sku($qraw);
    if ($pid) $touch((int)$pid, 90);

    // 3) SKU parcial
    $likeRaw = '%' . $wpdb->esc_like($qraw) . '%';
    $idsSku = $wpdb->get_col($wpdb->prepare("
        SELECT p.ID
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = '_sku'
        WHERE p.post_type = 'product' AND p.post_status = 'publish'
          AND pm.meta_value LIKE %s
        LIMIT %d
    ", $likeRaw, $limit * 3));
    $add_products_by_ids($idsSku, 60);

    // 4) Título exacto (case/acentos insensible)
    $idsTitleExact = $wpdb->get_col($wpdb->prepare("
        SELECT p.ID
        FROM {$wpdb->posts} p
        WHERE p.post_type='product' AND p.post_status='publish'
          {$termFilterSQL}
          AND LOWER(p.post_title) COLLATE utf8mb4_unicode_ci = %s
        LIMIT %d
    ", $qnorm, $limit * 2));
    $add_products_by_ids($idsTitleExact, 80);

    // 5) Título empieza por...
    $likeStart = $wpdb->esc_like($qnorm) . '%';
    $idsTitleStart = $wpdb->get_col($wpdb->prepare("
        SELECT p.ID
        FROM {$wpdb->posts} p
        WHERE p.post_type='product' AND p.post_status='publish'
          {$termFilterSQL}
          AND LOWER(p.post_title) COLLATE utf8mb4_unicode_ci LIKE %s
        ORDER BY p.menu_order ASC, p.post_title ASC
        LIMIT %d
    ", $likeStart, $limit * 3));
    $add_products_by_ids($idsTitleStart, 70);

    // 6) Todos los tokens en título o contenido (AND)
    $whereParts = [];
    foreach ($tokens as $t) {
        $like = '%' . $wpdb->esc_like($t) . '%';
        $whereParts[] = $wpdb->prepare(
            "(LOWER(p.post_title) COLLATE utf8mb4_unicode_ci LIKE %s OR LOWER(p.post_content) COLLATE utf8mb4_unicode_ci LIKE %s)",
            $like, $like
        );
    }
    if ($whereParts) {
        $whereSQL = implode(' AND ', $whereParts);
        $idsTok = $wpdb->get_col("
            SELECT p.ID
            FROM {$wpdb->posts} p
            WHERE p.post_type='product' AND p.post_status='publish'
              {$termFilterSQL}
              AND {$whereSQL}
            ORDER BY p.menu_order ASC, p.post_title ASC
            LIMIT " . (int)($limit * 4)
        );
        $add_products_by_ids($idsTok, 40);
    }

    // 7) Fallback controlado: wc_get_products(search) – solo si hay tokens pero poca señal
    if (count($scores) < $limit) {
        $argsWoo = [
            'status' => 'publish',
            'limit' => $limit * 2,
            'return' => 'ids', // ids para sumar score, luego cargamos objetos
        ];
        if ($qraw !== '') $argsWoo['search'] = $qraw;
        if ($catSlug) $argsWoo['category'] = [$catSlug];
        if ($onlyStock) $argsWoo['stock_status'] = 'instock';

        $idsWoo = wc_get_products($argsWoo);
        foreach ($idsWoo as $id) $touch((int)$id, 20);
    }

    if (empty($scores)) return [];

    // Cargar productos y filtrar visibilidad/stock
    $products = [];
    foreach (array_keys($scores) as $pid) {
        $p = wc_get_product($pid);
        if (!$p || $p->get_status() !== 'publish') continue;
        $vis = $p->get_catalog_visibility(); // visible | catalog | search | hidden
        if ($vis === 'hidden') continue;
        if ($onlyStock && !$p->is_in_stock()) continue;
        $products[$pid] = $p;
    }
    if (empty($products)) return [];

    // Bonus al score por estar en stock y por categoría coincidente (si hay)
    foreach ($products as $pid => $p) {
        if ($p->is_in_stock()) $scores[$pid] += 5;
        if ($catSlug) {
            $cats = wp_get_post_terms($pid, 'product_cat', ['fields' => 'slugs']);
            if (in_array($catSlug, $cats, true)) $scores[$pid] += 8;
        }
    }

    // Ordenar por score desc, luego por título asc
    uasort($products, function ($a, $b) use ($scores) {
        $sa = $scores[$a->get_id()] ?? 0;
        $sb = $scores[$b->get_id()] ?? 0;
        if ($sa === $sb) {
            return strcasecmp($a->get_name(), $b->get_name());
        }
        return $sb <=> $sa;
    });

    // Devolver top N
    return array_slice(array_values($products), 0, $limit);
}

/** Serializa un producto a un payload ligero para el chat o UI */
function mql_product_to_payload($p)
{
    $img = wp_get_attachment_image_src($p->get_image_id(), 'medium');
    return [
        'id' => $p->get_id(),
        'sku' => $p->get_sku(),
        'name' => $p->get_name(),
        'price' => wc_price($p->get_price()),
        'price_raw' => $p->get_price(),
        'link' => get_permalink($p->get_id()),
        'image' => $img ? $img[0] : null,
        'stock' => $p->is_in_stock() ? 'in_stock' : 'out_of_stock',
        'categories' => wp_get_post_terms($p->get_id(), 'product_cat', ['fields' => 'names']),
        'short_desc' => wp_strip_all_tags($p->get_short_description()),
    ];
}

/* ==== MQL: reglas de negocio del asistente ==== */
function mql_get_business_rules()
{
    return [
        'actividad' => 'Somos fabricantes de libros (impresión y encuadernación). No vendemos otros tipos de productos no relacionados.',
        'oferta' => 'Trabajamos tiradas cortas y medianas, tapa blanda/dura, distintos papeles y tamaños. Personalización bajo pedido.',
        'envio' => 'Envíos 24-48h en península según tirada y carga de trabajo. Urgentes: consultar.',
        'devolucion' => 'Devoluciones 14 días salvo productos personalizados.',
        'venta' => 'Recomienda sólo productos/servicios del catálogo WooCommerce. Si no hay coincidencias, pide detalles y no inventes.',
        'cruces' => 'Si un producto no está en stock, sugiere 1-2 alternativas del catálogo cercanas por categoría/servicio.',
    ];
}