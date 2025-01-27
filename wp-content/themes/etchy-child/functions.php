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

    $transformateData = transformateDataToErp($yith_wapo_data);
    $dataOptimus = $transformateData['data_optimus'];
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
function apply_custom_price_for_cart_items($cart_item, $values, $key) {
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

function custom_billing_state_options($states)
{
    global $wpdb;

    // Consultar las provincias desde la tabla 'provincia' (ajustar nombre de tabla y columna)
    $results = $wpdb->get_results("SELECT provincia, codigo FROM provincia", ARRAY_A);

    // Convertir los resultados en un array de provincias (codigo => nombre)
    if ($results) {
        // Extraer los valores con wp_list_pluck()
        $provincias = wp_list_pluck($results, 'codigo', 'provincia');

        $provincias = array('' => __('Select an option')) + $provincias;

        // Asignar las provincias al estado de España (ES)
        $states['ES'] = $provincias;
    }

    return $states;
}

add_filter('woocommerce_states', 'custom_billing_state_options');

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

add_action('woocommerce_checkout_update_order_review', 'enviar_datos_a_api_al_actualizar_resumen', 10, 1);
function enviar_datos_a_api_al_actualizar_resumen($order_id)
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

    $dirData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $country = isset($dirData['s_country']) ? $dirData['s_country'] : '';
    $state = isset($dirData['s_state']) ? $dirData['s_state'] : '';
    $postcode = isset($dirData['s_postcode']) ? $dirData['s_postcode'] : '';
    $city = isset($dirData['s_city']) ? $dirData['s_city'] : '';
    $address = isset($dirData['s_address']) ? $dirData['s_address'] : '';
    $address_2 = isset($dirData['s_address_2']) ? $dirData['s_address_2'] : '';

    if ($state != '') {
        $dataToDb[] = ['e_ent' => 1];
        $dataToDb[] = ['e_ent_00_zona' => $state];
        $transformateData = transformateDataToErp($dataToDb);
        $dataOptimus = $transformateData['data_optimus'];
        $fechaEstimada = getFechaEstimada();
        $priceRequest = getPricePresupuestoToOptimus($dataOptimus, $codOptimus, $fechaEstimada);
    }
}

add_action('woocommerce_payment_complete', 'so_payment_complete');
function so_payment_complete($order_id)
{
    $order = wc_get_order($order_id);
    $cart_items = WC()->cart->get_cart();
    $dataToDb = [];
    foreach ($cart_items as $cart_item) {
        if (isset($cart_item['yith_wapo_options']) && !empty($cart_item['yith_wapo_options'])) {
            $dataToDb = $cart_item['yith_wapo_options'];
        }
    }
    var_dump($dataToDb);
    exit;
    $user = $order->get_user();
    $codOptimus = $user->api_id;

    $url_funcion = "/customer/addCustomerAddress";
    // $url_funcion = "/customer/updateCustomerAddress";
    $url = $urlBase . $url_funcion . $url_db;
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<customerAddress>
	 <customerCode>' . $customercode . '</customerCode>
	 <name>' . $order->get_billing_first_name() . '</name>
	 <addressLine1>' . $order->get_billing_address_1() . '</addressLine1>
	 <addressLine2>' . $order->get_billing_address_2() . '</addressLine2>
	 <addressLine3>' . $order->get_billing_city() . '</addressLine3>
	 <addressLine4></addressLine4>
	 <postcode>' . $order->get_billing_postcode() . '</postcode>
	 <contact>' . $order->get_billing_phone() . '</contact>
	 <email>' . $order->get_billing_email() . '</email>
	 <areaCode>ANDALUCIA</areaCode>
	 <repCode>FELIPE</repCode>
	 <isInvoice>1</isInvoice>
	 <isDelivery>1</isDelivery>
	 <isQuote>1</isQuote>
	 <isLabel>1</isLabel>
	 <isWebToPrint>0</isWebToPrint>
	 <taxClassification></taxClassification>
	</customerAddress>';
    $response = sendXmlOverPost($url, $xml);
    $response_xml = new SimpleXMLElement($response);

    // Extraer los valores de los metadatos
    if ($item->get_meta('ywapo-addon-7-0') && $item->get_meta('ywapo-addon-7-0') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-0');
    }

    if ($item->get_meta('ywapo-addon-7-1') && $item->get_meta('ywapo-addon-7-1') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-1');
    }

    if ($item->get_meta('ywapo-addon-7-2') && $item->get_meta('ywapo-addon-7-2') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-2');
    }

    if ($item->get_meta('ywapo-addon-7-3') && $item->get_meta('ywapo-addon-7-3') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-3');
    }

    if ($item->get_meta('ywapo-addon-7-4') && $item->get_meta('ywapo-addon-7-4') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-4');
    }

    if ($item->get_meta('ywapo-addon-7-5') && $item->get_meta('ywapo-addon-7-5') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-5');
    }

    if ($item->get_meta('ywapo-addon-7-6') && $item->get_meta('ywapo-addon-7-6') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-6');
    }

    if ($item->get_meta('ywapo-addon-7-7') && $item->get_meta('ywapo-addon-7-7') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-7');
    }

    if ($item->get_meta('ywapo-addon-7-8') && $item->get_meta('ywapo-addon-7-8') != "NULL") {
        $encuadernacion = $item->get_meta('ywapo-addon-7-8');
    }

    $tipoImpresionInterior = $item->get_meta('ywapo-addon-30-0');
    $codTipoPedido = getCodTipoPedido($encuadernacion, $tipoImpresionInterior);


    //var_dump($item);

    $addressNumber = (array)$response_xml->addressNumber;
    $addressNumber = $addressNumber[0];
    $url_funcion = "/enqbuilder";
    $url = $urlBase . $url_funcion . $url_db;
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<enquiry>
		<jobTemplateCode>PL_STD_PEDIDO</jobTemplateCode>
		<customerCode>' . $customercode . '</customerCode>
		<addressNumber>' . $addressNumber . '</addressNumber>
		<customerRef>' . $user->ID . '</customerRef>
		<contactName>' . $order->get_billing_first_name() . '</contactName>
		<telephone>' . $order->get_billing_phone() . '</telephone>
		<emailAddress>' . $order->get_billing_email() . '</emailAddress>
		<repCode>FELIPE</repCode>
		<origCode>WEB</origCode>
		<currencyCode></currencyCode>
		<dueAt></dueAt>
		<prevEnqNumber>0</prevEnqNumber>
		<cancelPrevious>false</cancelPrevious>
		<jobVariable>
			<name>ep_fecha_entrega</name>
			<type>datetime</type>
			<value>' . date("Y-m-d") . 'T' . date("H:i") . '</value>
		</jobVariable>
		<jobVariable>
			<name>ep_tipo_pedido</name>
			<type>string</type>
			<value>' . $codTipoPedido . '</value>
		</jobVariable>
		<jobVariable>
			<name>ep_titulo</name>
			<type>string</type>
			<value>' . $item->get_meta('ywapo-addon-2-0') . '</value>
		</jobVariable>';
    if ($item->get_meta('ywapo-addon-9-0')) {
        $xml .= '
		<jobVariable>
			<name>ep_isbn</name>
			<type>string</type>
			<value>' . $item->get_meta('ywapo-addon-9-0') . '</value>
		</jobVariable>
        <jobVariable>
        <name>ep_imprimirhr</name>
        <type>boolean</type>
        <value>1</value>
        </jobVariable>';
    }
    $xml .= '<line>
			<productCode>GENERICO</productCode>
			<description>' . $item->get_meta('ywapo-addon-2-0') . '</description>
			<includeInQuote>true</includeInQuote>
			<productVariable>
				<name>e_elem_mod</name>
				<type>integer</type>
				<value>1</value>
			</productVariable>';
    for ($i = 0; $i <= 8; $i++) {
        $meta_key = 'ywapo-addon-7-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>e_encu</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-15-0') && $item->get_meta('ywapo-addon-15-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_elem</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-16-0') && $item->get_meta('ywapo-addon-16-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-20-0') && $item->get_meta('ywapo-addon-20-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>5e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-21-0') && $item->get_meta('ywapo-addon-21-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>6e_tipo</name>
					<type>string</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-22-0') && $item->get_meta('ywapo-addon-22-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_tipo</name>
					<type>string</type>
					<value>1</value>
				</productVariable>';
    }
    $xml .= '
			<productVariable>
				<name>0e_tinta_cobertura</name>
				<type>decimal</type>
				<value>12</value>
			</productVariable>';
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-5-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>e_ancho</name>
						<type>integer</type>
						<value>' . $e_ancho . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>e_alto</name>
						<type>integer</type>
						<value>' . $e_alto . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL") {
        $e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
        $e_alto = trim($item->get_meta('ywapo-addon-975-1'));

        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);

        $xml .= '
				<productVariable>
					<name>e_ancho</name>
					<type>integer</type>
					<value>' . $e_ancho . '</value>
				</productVariable>';
        $xml .= '
				<productVariable>
					<name>e_alto</name>
					<type>integer</type>
					<value>' . $e_alto . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-15-0') && $item->get_meta('ywapo-addon-15-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-15-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-30-0') && $item->get_meta('ywapo-addon-30-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-30-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-30-1') && $item->get_meta('ywapo-addon-30-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-30-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 15; $i++) {
        $meta_key = 'ywapo-addon-31-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>0e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>0e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-89-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>0e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>0e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-32-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>0e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-977-0') && $item->get_meta('ywapo-addon-977-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_paginas</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-977-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-33-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>0e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-970-0') && $item->get_meta('ywapo-addon-970-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-34-0') && $item->get_meta('ywapo-addon-34-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>0e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-16-0') && $item->get_meta('ywapo-addon-16-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-16-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-36-0') && $item->get_meta('ywapo-addon-36-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-36-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-36-1') && $item->get_meta('ywapo-addon-36-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-36-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-37-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>1e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>1e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-90-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>1e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>1e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-38-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>1e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-978-0') && $item->get_meta('ywapo-addon-978-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_paginas</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-978-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-41-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>1e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-971-1') && $item->get_meta('ywapo-addon-971-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-40-0') && $item->get_meta('ywapo-addon-40-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>1e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-17-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-24-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>2e_tipo_imp</name>
						<type>string</type>
						<value>TONER</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>2e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>2e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-1001-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>2e_tipo_imp</name>
						<type>string</type>
						<value>TONER</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>2e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>2e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-25-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>2e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-1002-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>2e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-26-0') && $item->get_meta('ywapo-addon-26-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_solapas</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-26-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-5-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-17-0') != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0') != "NULL") {
                $xml .= '
					<productVariable>
						<name>2e_ancho</name>
						<type>integer</type>
						<value>' . $e_ancho . '</value>
					</productVariable>';
                $xml .= '
					<productVariable>
						<name>2e_alto</name>
						<type>integer</type>
						<value>' . $e_alto . '</value>
					</productVariable>';
            }
        }
    }
    if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-17-0') != "NULL") {
        $e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
        $e_alto = trim($item->get_meta('ywapo-addon-975-1'));


        if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0') != "NULL") {
            $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
            $e_alto = preg_replace('/[^0-9]/', '', $e_alto);

            $xml .= '
				<productVariable>
					<name>2e_ancho</name>
					<type>integer</type>
					<value>' . $e_ancho . '</value>
				</productVariable>';
            $xml .= '
				<productVariable>
					<name>2e_alto</name>
					<type>integer</type>
					<value>' . $e_alto . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-972-0') && $item->get_meta('ywapo-addon-972-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_ancho_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-972-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-972-1') && $item->get_meta('ywapo-addon-972-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_alto_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-972-1') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-27-0') && $item->get_meta('ywapo-addon-27-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-28-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>2e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-29-0') && $item->get_meta('ywapo-addon-29-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('yith-wapo-option-973-0') && $item->get_meta('ywapo-addon-973-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_barn</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 15; $i++) {
        $meta_key = 'ywapo-addon-78-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>2e_esta_color</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-79-0') && $item->get_meta('ywapo-addon-79-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_esta</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-79-1') && $item->get_meta('ywapo-addon-79-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_troq</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-79-2') && $item->get_meta('ywapo-addon-79-2') != "NULL") {
        $xml .= '
				<productVariable>
					<name>2e_golp</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-18-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-42-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
                    <productVariable>
                        <name>3e_tipo_imp</name>
                        <type>string</type>
                        <value>TONER</value>
                    </productVariable>';
            $xml .= '
					<productVariable>
						<name>3e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>3e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-43-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>3e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-44-0') && $item->get_meta('ywapo-addon-44-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-45-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>3e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-44-1') && $item->get_meta('ywapo-addon-44-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-5-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-18-0') != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0') != "NULL") {
                $xml .= '
					<productVariable>
						<name>3e_ancho</name>
						<type>integer</type>
						<value>' . $e_ancho . '</value>
					</productVariable>';
                $xml .= '
					<productVariable>
						<name>3e_alto</name>
						<type>integer</type>
						<value>' . $e_alto . '</value>
					</productVariable>';
            }
        }
    }
    if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-18-0') != "NULL") {
        $e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
        $e_alto = trim($item->get_meta('ywapo-addon-975-1'));

        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);

        if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0') != "NULL") {
            $xml .= '
				<productVariable>
					<name>3e_ancho</name>
					<type>integer</type>
					<value>' . $e_ancho . '</value>
				</productVariable>';
            $xml .= '
				<productVariable>
					<name>3e_alto</name>
					<type>integer</type>
					<value>' . $e_alto . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-80-0') && $item->get_meta('ywapo-addon-80-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_ancho_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-80-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-80-1') && $item->get_meta('ywapo-addon-80-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>3e_alto_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-80-1') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-19-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-47-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
                    <productVariable>
                        <name>4e_tipo_imp</name>
                        <type>string</type>
                        <value>TONER</value>
                    </productVariable>';
            $xml .= '
					<productVariable>
						<name>4e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>4e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-48-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>4e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-979-0') && $item->get_meta('ywapo-addon-979-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_solapas</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-979-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-5-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0') != "NULL") {
                $xml .= '
					<productVariable>
						<name>4e_ancho</name>
						<type>integer</type>
						<value>' . $e_ancho . '</value>
					</productVariable>';
                $xml .= '
					<productVariable>
						<name>4e_alto</name>
						<type>integer</type>
						<value>' . $e_alto . '</value>
					</productVariable>';
            }
        }
    }
    if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL") {
        $e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
        $e_alto = trim($item->get_meta('ywapo-addon-975-1'));

        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);

        if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0') != "NULL") {
            $xml .= '
				<productVariable>
					<name>4e_ancho</name>
					<type>integer</type>
					<value>' . $e_ancho . '</value>
				</productVariable>';
            $xml .= '
				<productVariable>
					<name>4e_alto</name>
					<type>integer</type>
					<value>' . $e_alto . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-49-0') && $item->get_meta('ywapo-addon-49-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_ancho_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-49-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-49-1') && $item->get_meta('ywapo-addon-49-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_alto_ab</name>
					<type>decimal</type>
					<value>' . $item->get_meta('ywapo-addon-49-1') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-51-0') && $item->get_meta('ywapo-addon-51-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-50-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>4e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-52-0') && $item->get_meta('ywapo-addon-52-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_barn</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-81-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>4e_esta_color</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-82-0') && $item->get_meta('ywapo-addon-82-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_esta</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-82-1') && $item->get_meta('ywapo-addon-82-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_troq</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-82-2') && $item->get_meta('ywapo-addon-82-2') != "NULL") {
        $xml .= '
				<productVariable>
					<name>4e_golp</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-20-0') && $item->get_meta('ywapo-addon-20-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>5e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-20-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-53-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
                    <productVariable>
                        <name>5e_tipo_imp</name>
                        <type>string</type>
                        <value>TONER</value>
                    </productVariable>';
            $xml .= '
					<productVariable>
						<name>5e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>5e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-54-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>5e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-57-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>5e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-56-0') && $item->get_meta('ywapo-addon-56-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>5e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-21-0') && $item->get_meta('ywapo-addon-21-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>6e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-21-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-60-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
                    <productVariable>
                        <name>6e_tipo_imp</name>
                        <type>string</type>
                        <value>TONER</value>
                    </productVariable>';
            $xml .= '
					<productVariable>
						<name>6e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>6e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-61-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>6e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-62-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>6e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-63-0') && $item->get_meta('ywapo-addon-63-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>6e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-63-1') && $item->get_meta('ywapo-addon-63-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>6e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-22-0') && $item->get_meta('ywapo-addon-22-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_tipo</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-22-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-68-0') && $item->get_meta('ywapo-addon-68-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-68-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-68-1') && $item->get_meta('ywapo-addon-68-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_tipo_imp</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-68-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-67-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>7e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>7e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = 'ywapo-addon-99-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $pap = trim($val[0]);
            $grm = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>7e_tipo_pap</name>
						<type>string</type>
						<value>' . $pap . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>7e_tipo_grm</name>
						<type>string</type>
						<value>' . $grm . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-64-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
					<productVariable>
						<name>7e_tintas</name>
						<type>string</type>
						<value>' . $meta_value . '</value>
					</productVariable>';
        }
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-65-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>7e_plast</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-66-0') && $item->get_meta('ywapo-addon-66-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-974-0') && $item->get_meta('ywapo-addon-974-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-58-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>7e_formapleg</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = 'ywapo-addon-5-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-22-0') != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            $xml .= '
					<productVariable>
						<name>7e_ancho</name>
						<type>integer</type>
						<value>' . $e_ancho . '</value>
					</productVariable>';
            $xml .= '
					<productVariable>
						<name>7e_alto</name>
						<type>integer</type>
						<value>' . $e_alto . '</value>
					</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-22-0') != "NULL") {
        $e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
        $e_alto = trim($item->get_meta('ywapo-addon-975-1'));

        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);

        $xml .= '
				<productVariable>
					<name>7e_ancho</name>
					<type>integer</type>
					<value>' . $e_ancho . '</value>
				</productVariable>';
        $xml .= '
				<productVariable>
					<name>7e_alto</name>
					<type>integer</type>
					<value>' . $e_alto . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-59-0') && $item->get_meta('ywapo-addon-59-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_ancho_ab</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-59-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-59-1') && $item->get_meta('ywapo-addon-59-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>7e_alto_ab</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-59-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-83-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_encu_wr_color</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-84-0') && $item->get_meta('ywapo-addon-84-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_wr_color_otro</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-84-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-85-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_encu_es_color</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-86-0') && $item->get_meta('ywapo-addon-86-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_es_color_otro</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-86-0') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = 'ywapo-addon-69-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_encu_td_carton</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-70-0') && $item->get_meta('ywapo-addon-70-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_td_cab</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-87-0') && $item->get_meta('ywapo-addon-87-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_tc_cab_color</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-87-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-70-1') && $item->get_meta('ywapo-addon-70-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_td_csep</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-88-0') && $item->get_meta('ywapo-addon-88-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_tc_csep_color</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-88-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-70-2') && $item->get_meta('ywapo-addon-70-2') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_td_csep2</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-89-0') && $item->get_meta('ywapo-addon-89-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_tc_csep2_color</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-89-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-70-3') && $item->get_meta('ywapo-addon-70-3') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_td_csep3</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-90-0') && $item->get_meta('ywapo-addon-90-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_encu_tc_csep3_color</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-90-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-73-0') && $item->get_meta('ywapo-addon-73-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_emp_retrcol</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-74-0') && $item->get_meta('ywapo-addon-74-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_emp_retrcol_ctd</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-74-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-73-1') && $item->get_meta('ywapo-addon-73-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_emp_retruni</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-73-2') && $item->get_meta('ywapo-addon-73-2') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_emp_ensobrado</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-96-0') && $item->get_meta('ywapo-addon-96-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_extracostes</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-96-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-96-1') && $item->get_meta('ywapo-addon-96-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_comentario_cli</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-96-1') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-75-0') && $item->get_meta('ywapo-addon-75-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_emp_encaj</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-75-1') && $item->get_meta('ywapo-addon-75-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-982-0') && $item->get_meta('ywapo-addon-982-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_04_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-982-0') . '</value>
				</productVariable>';

        $xml .= '
				<productVariable>
					<name>e_ent_04_cnt_01</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-983-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_04_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-76-0') && $item->get_meta('ywapo-addon-76-0') != "NULL") {

        $xml .= '
				<productVariable>
					<name>e_ent_00_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-76-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-76-1') && $item->get_meta('ywapo-addon-76-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_00_cnt_01</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-76-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-984-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_00_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }

    if ($item->get_meta('ywapo-addon-92-0') && $item->get_meta('ywapo-addon-92-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_01_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-92-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-92-1') && $item->get_meta('ywapo-addon-92-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_01_cnt_01</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-92-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-985-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_01_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-93-0') && $item->get_meta('ywapo-addon-93-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_02_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-93-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-93-1') && $item->get_meta('ywapo-addon-93-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_02_cnt_01</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-93-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-986-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_02_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-94-0') && $item->get_meta('ywapo-addon-94-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_03_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-94-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-94-1') && $item->get_meta('ywapo-addon-94-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_03_cnt_01</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-94-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-987-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_03_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-95-0') && $item->get_meta('ywapo-addon-95-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_04_dir</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-95-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-95-1') && $item->get_meta('ywapo-addon-95-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_ent_04_cnt_01</name>
					<type>integer</type>
					<value>' . $item->get_meta('ywapo-addon-95-1') . '</value>
				</productVariable>';
    }
    for ($i = 0; $i <= 50; $i++) {
        $meta_key = 'ywapo-addon-988-' . $i;
        $meta_value = $item->get_meta($meta_key);

        if ($meta_value && $meta_value != "NULL") {
            $xml .= '
				<productVariable>
					<name>e_ent_04_zona</name>
					<type>string</type>
					<value>' . $meta_value . '</value>
				</productVariable>';
        }
    }
    if ($item->get_meta('ywapo-addon-3-0') && $item->get_meta('ywapo-addon-3-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-3-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-3-1') && $item->get_meta('ywapo-addon-3-1') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-3-1') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-3-2') && $item->get_meta('ywapo-addon-3-2') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-3-2') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-8-0') && $item->get_meta('ywapo-addon-8-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>e_observaciones_cli</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-8-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-1-0') && $item->get_meta('ywapo-addon-1-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>orientacion</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-1-0') . '</value>
				</productVariable>';
    }
    if ($item->get_meta('ywapo-addon-1-1') && $item->get_meta('ywapo-addon-1-0') != "NULL") {
        $xml .= '
				<productVariable>
					<name>orientacion</name>
					<type>string</type>
					<value>' . $item->get_meta('ywapo-addon-1-0') . '</value>
				</productVariable>';
    }

    $cantidad = str_replace("Cantidad 1: ", "", $item->get_meta('ywapo-addon-980-0'));
    $xml .= '
			<quantity>' . $cantidad . '</quantity>
		</line>
	</enquiry>';

    //var_dump($xml);
    //die();


    $url2_funcion = "/enqPrice";
    $url2 = $urlBase . $url2_funcion . $url_db;

    $response = sendXmlOverPost($url2, $xml);
    $response_xml = new SimpleXMLElement($response);

    //var_dump($response);
    //die();

    $lineQtyPrice = (array)$response_xml->lineQtyPrice;
    $total = round($lineQtyPrice["price"], 2);

    $order->set_total($total);
    $order->save();


    $response = sendXmlOverPost($url, $xml);
    $response_xml = new SimpleXMLElement($response);

    $success = (array)$response_xml->success;
    if ($success[0] == "false") {
        $error = (array)$response_xml->error;
        echo '
		{
			"result": "failure",
			"messages": "\n<ul class=\"woocommerce-error\" role=\"alert\">\n\t\t\t<li data-id=\"billing_first_name\">\n\t\t\t' . $error[0] . '\t\t<\/li>\n\t<\/ul>\n",
			"refresh": false,
			"reload": false
		}';
        //var_dump($xml);
        exit();
    } else {
        $success = (array)$response_xml->success;
        echo '
		{
			"result": "true",
			"messages": "\n<ul class=\"woocommerce-error\" role=\"alert\">\n\t\t\t<li data-id=\"billing_first_name\" >\n\t\t\t' . $response . '\t\t<\/li>\n\t<\/ul>\n",
			"refresh": false,
			"reload": false,
			"redirect_url": get_size_url()."/gracias-por-comprar/"
		}';

    }


    $url_funcion = "/enquiry/markSuccessful";
    $url = $urlBase . $url_funcion . $url_db;
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<enquirySuccess>
		<enquiryNumber></enquiryNumber>
		<successInfo>
			<lineSuccessInfo>
				<lineNumber></lineNumber>
				<acceptedQuantity></acceptedQuantity>
			</lineSuccessInfo>
		</successInfo>
	</enquirySuccess>';
    $response = sendXmlOverPost($url, $xml);
    $response_xml = new SimpleXMLElement($response);

    // var_dump($response_xml);
    // die();
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

//MQL - CAMPOS NUEVOS DE REGISTRO
add_action('woocommerce_register_form', 'add_custom_fields_to_registration_form');
function add_custom_fields_to_registration_form()
{
    ?>
    <p class="form-row form-row-wide">
        <label for="name"><?php esc_html_e('Name', 'textdomain'); ?> <span class="required">*</span></label>
        <input type="text" required class="input-text" name="name" id="name"
               value="<?php echo esc_attr(!empty($_POST['name']) ? $_POST['name'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="cif"><?php esc_html_e('CIF', 'textdomain'); ?> <span class="required">*</span></label>
        <input type="text" required class="input-text" name="cif" id="cif"
               value="<?php echo esc_attr(!empty($_POST['cif']) ? $_POST['cif'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="phone_number"><?php esc_html_e('Phone Number', 'textdomain'); ?> <span
                    class="required">*</span></label>
        <input type="text" required class="input-text" name="phone_number" id="phone_number"
               value="<?php echo esc_attr(!empty($_POST['phone_number']) ? $_POST['phone_number'] : ''); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="payment_type"><?php esc_html_e('Payment Method', 'textdomain'); ?> <span
                    class="required">*</span></label>
        <select name="payment_type" required id="payment_type" class="input-text">
            <option value="transferencia"><?php esc_html_e('Transferencia', 'textdomain'); ?></option>
            <option value="tarjeta_credito"><?php esc_html_e('Tarjeta de crédito', 'textdomain'); ?></option>
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
    <h3><?php _e('Información adicional 2', 'textdomain'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="name"><?php _e('Nombre', 'textdomain'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="name" id="name" required
                       value="<?php echo esc_attr(get_the_author_meta('name', $user->ID)); ?>" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="cif"><?php _e('CIF', 'textdomain'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="cif" id="cif" required
                       value="<?php echo esc_attr(get_the_author_meta('cif', $user->ID)); ?>" class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="phone_number"><?php _e('Número de teléfono', 'textdomain'); ?><span
                            class="required">*</span></label></th>
            <td>
                <input type="text" name="phone_number" id="phone_number" required
                       value="<?php echo esc_attr(get_the_author_meta('phone_number', $user->ID)); ?>"
                       class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="payment_type"><?php _e('Método de pago', 'textdomain'); ?><span
                            class="required">*</span></label></th>
            <td>
                <select name="payment_type" id="payment_type" required>
                    <option value=""><?php _e('Selecciona un método de pago', 'textdomain'); ?></option>
                    <option value="transferencia" <?php selected(get_the_author_meta('payment_type', $user->ID), 'transferencia'); ?>><?php _e('Transferencia', 'textdomain'); ?></option>
                    <option value="tarjeta_credito" <?php selected(get_the_author_meta('payment_type', $user->ID), 'tarjeta_credito'); ?>><?php _e('Tarjeta de crédito', 'textdomain'); ?></option>
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
    <h3><?php _e('Información adicional', 'textdomain'); ?></h3>
    <p class="form-row form-row-wide">
        <label for="name"><?php _e('Nombre', 'textdomain'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" name="name" required id="name" value="<?php echo esc_attr($name); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="cif"><?php _e('CIF', 'textdomain'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" required name="cif" id="cif" value="<?php echo esc_attr($cif); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="phone_number"><?php _e('Número de teléfono', 'textdomain'); ?><span
                    class="required">*</span></label>
        <input type="text" class="input-text" required name="phone_number" id="phone_number"
               value="<?php echo esc_attr($phoneNumber); ?>"/>
    </p>
    <p class="form-row form-row-wide">
        <label for="payment_type"><?php _e('Método de pago', 'textdomain'); ?><span
                    class="required">*</span></label>
        <select name="payment_type" id="payment_type" required class="select">
            <option value=""><?php _e('Selecciona un método de pago', 'textdomain'); ?></option>
            <option value="transferencia" <?php selected($paymentType, 'transferencia'); ?>><?php _e('Transferencia', 'textdomain'); ?></option>
            <option value="tarjeta_credito" <?php selected($paymentType, 'tarjeta_credito'); ?>><?php _e('Tarjeta de crédito', 'textdomain'); ?></option>
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
    return array_reduce($yith_wapo_data, function ($carry, $item) {
        if (is_array($item)) {
            $key = array_key_first($item);
            $value = trim(str_replace(' ', '', $item[$key]));

            if (str_ends_with($key, '_tipo')) {
                return $carry;
            }

            if (str_ends_with($key, '_elem'))
                $value = true;

            if ($key == 'formato' || str_ends_with($key, 'e_tipo_papel') || str_ends_with($key, 'e_tintas'))
                $value = str_replace('/', '____', $value);

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

        if (!in_array($key, $productVariableKeys))
            $dataOptimus['productVariable'][$key] = $itemOptimus;
        else
            $dataOptimus['jobVariable'][$key] = $itemOptimus;
    }

    if (isset($dataToDb['quantity'])) {
        foreach ($dataToDb['quantity'] as $quantity) {
            if (is_object($quantity)) {
                $quantity = get_object_vars($quantity);
            }
            $quantity = str_replace('.', '', $quantity);
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