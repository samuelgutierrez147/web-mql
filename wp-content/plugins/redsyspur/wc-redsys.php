<?php

/**
 * NOTA SOBRE LA LICENCIA DE USO DEL SOFTWARE
 *
 * El uso de este software está sujeto a las Condiciones de uso de software que
 * se incluyen en el paquete en el documento "Aviso Legal.pdf". También puede
 * obtener una copia en la siguiente url:
 * http://www.redsys.es/wps/portal/redsys/publica/areadeserviciosweb/descargaDeDocumentacionYEjecutables
 *
 * Redsys es titular de todos los derechos de propiedad intelectual e industrial
 * del software.
 *
 * Quedan expresamente prohibidas la reproducción, la distribución y la
 * comunicación pública, incluida su modalidad de puesta a disposición con fines
 * distintos a los descritos en las Condiciones de uso.
 *
 * Redsys se reserva la posibilidad de ejercer las acciones legales que le
 * correspondan para hacer valer sus derechos frente a cualquier infracción de
 * los derechos de propiedad intelectual y/o industrial.
 *
 * Redsys Servicios de Procesamiento, S.L., CIF B85955367
 */

require_once('apiRedsys/redsysLibrary.php');
require_once('apiRedsys/apiRedsysFinal.php');
require_once('wc-redsys-ref.php');
require_once('wc-redsys-refund.php');

class WC_Redsys extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'redsys';  
        //$this->icon               = home_url() . '/wp-content/plugins/redsys/pages/assets/images/Redsys.png';
        $this->method_title       = __( 'Redirección · Pasarela Unificada de Redsys para WooCommerce', 'woocommerce' );
        $this->method_description = __( 'Permita a sus clientes pagar con tarjeta redirigiéndoles a los servicios de Redsys.', 'woocommerce' );
        $this->notify_url         = add_query_arg( 'wc-api', 'WC_redsys', home_url( '/' ) );
        $this->payment_fields_url = add_query_arg( 'wc-api', 'WC_redsys_payment_fields', home_url( '/' ) );
        $this->redirect_options_url = add_query_arg( 'wc-api', 'WC_redsys_redirect_options', home_url( '/' ) );
        $this->redirect_url       = add_query_arg( 'wc-api', 'WC_redsys_redirect', home_url( '/' ) );

        $this->has_fields         = false;

        // Load the settings
        $this->init_settings();
        $this->init_form_fields();

        $this->supports           = array( 'refunds' );

        $this->title              = $this->get_option( 'title' );
        $this->description        = $this->get_option( 'description' );
        $this->buttonLabel        = "Continuar en la pasarela de pago";

        // Get settings
        $this->entorno            = $this->get_option( 'entorno' );
        $this->nombre             = $this->get_option( 'name' );
        $this->fuc                = $this->get_option( 'fuc' );
        $this->tipopago           = $this->get_option( 'tipopago' );
        $this->clave256           = $this->get_option( 'clave256' );
        $this->terminal           = $this->get_option( 'terminal' );
        $this->genPedido          = $this->get_option( 'genPedido' );
        $this->pedidoExtendido    = $this->get_option( 'pedidoExtendido' );
        $this->activar_log	      = $this->get_option( 'activar_log' );
        $this->estado             = $this->get_option( 'estado' );
        $this->activar_3ds        = $this->get_option( 'activar_3ds' );
        $this->mantener_carrito   = $this->get_option( 'mantener_carrito' );
        $this->activar_anulaciones= $this->get_option( 'activar_anulaciones' );
        $this->withref            = $this->get_option( 'withref' );
        $this->tabla_ordenes      = $this->get_option( 'tabla_ordenes' );
        $this->moneda_manual      = $this->get_option( 'moneda_manual' );
        $this->modal              = $this->get_option( 'modal' );
		$this->urlOK              = $this->get_option( 'urlOK' );
		$this->urlKO              = $this->get_option( 'urlKO' );

        //moneda a usar
        $this->moneda = currency_code(get_option('woocommerce_currency'));

        $this->moduleComent = "Pasarela Unificada de Redsys para WooCommerce";

        //idLog
        $this->logString          = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Actions
        add_action( 'woocommerce_receipt_redsys', array( $this, 'receipt_page' ) );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        //Payment listener/API hook
        add_action( 'woocommerce_api_wc_redsys', array( $this, 'check_rds_response' ) );
        add_action( 'woocommerce_api_wc_redsys_payment_fields', array( $this, 'payment_fields_api' ) );
        add_action( 'woocommerce_api_wc_redsys_redirect_options', array( $this, 'redirect_options' ) );
        add_action( 'woocommerce_api_wc_redsys_redirect', array( $this, 'redirect' ) );
        add_action( 'woocommerce_before_checkout_form', array( $this, 'advertencia_sandbox' ) );
    }

    function init_form_fields() {
        global $woocommerce;

        $this->form_fields = array(
                'enabled' => array(
                        'title'       => __( 'Activación del Módulo', 'woocommerce' ),
                        'type'        => 'select',
                        'description' => __( 'Activa o desactiva el Módulo de pago con Tarjeta', 'woocommerce' ),
                        'default'     => 'yes',
                        'options'     => array(
                                'yes' => __( 'Activado', 'woocommerce' ),
                                'no'  => __( 'Desactivado', 'woocommerce' )
                        ),
                        'desc_tip'    => true,
                ),
                'title' => array(
                        'title'       => __( 'Título del método de Pago', 'woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'Título del método de Pago que el cliente verá en la página de compra.', 'woocommerce' ),
                        'default'     => __( 'Pagar con Tarjeta', 'woocommerce' ),
                        'desc_tip'    => true,
                ),
                'description' => array(
                        'title'       => __( 'Descripción del método de Pago', 'woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'Descripción del método de Pago que el cliente verá en la página de compra.', 'woocommerce' ),
                        'default'     => __( 'Pague con tarjeta usando los servicios de Redsys.', 'woocommerce' ),
                        'desc_tip'    => true,
                ),
                'entorno' => array(
                        'title'       => __( 'Entorno de Operación', 'woocommerce' ),
                        'type'        => 'select',
                        'description' => __( 'Entorno donde procesar el pago. <br>Recuerde no activar el modo "Sandbox" en su entorno de producción, de lo contrario podrían producirse ventas no deseadas. Dispone de más información sobre cómo realizar pruebas <a href=https://pagosonline.redsys.es/entornosPruebas.html target="_blank" rel="noopener noreferrer">aquí</a>.', 'woocommerce' ),
                        'default'     => 0,
                        'options'     => array(
                            0 => __( 'Sandbox', 'woocommerce' ),
                            1 => __( 'Producción', 'woocommerce' )
                        )
                ),
                'name' => array(
                        'title'       => __( 'Nombre del Comercio', 'woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'Nombre de su comercio que se establecerá a la hora de enviar las operaciones.', 'woocommerce' ),
                        'default'     => __( '', 'woocommerce' ),
                        'desc_tip'    => true,
                ),
                'fuc' => array(
                        'title'       => __( 'Número de Comercio (FUC)', 'woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'El número de comercio, también denominado FUC, es un número que identifica a su comercio y debe habérselo provisto su Entidad Bancaria.', 'woocommerce' ),
                        'default'     => __( '', 'woocommerce' ),
                        'desc_tip'    => true,
                ),
                'terminal' => array(
                        'title'       => __( 'Número de Terminal', 'woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'El número de terminal es el número que identifica el terminal dentro de su comercio y debe habérselo provisto su Entidad Bancaria.', 'woocommerce' ),
                        'default'     => __( '', 'woocommerce' ),
                        'desc_tip'    => true,
                ),
                'clave256' => array(
                    'title'       => __( 'Clave de Encriptación SHA-256', 'woocommerce' ),
                    'type'        => 'text',
                    'description' => __( 'Esta clave permite firmar todas las operaciones enviadas por el módulo y ha debido ser provista de ella por su Entidad Bancaria. Recuerde guardarla en un lugar seguro. <br> Para realizar pruebas en el entorno Sandbox, puede usar: sq7HjrUOBfKmC576ILgskD5srU870gJ7 o la provista por su Entidad Bancaria.', 'woocommerce' ),
                    'default'     => __( 'sq7HjrUOBfKmC576ILgskD5srU870gJ7', 'woocommerce' ),
                ),
                'tipopago' => array(
                    'title'       => __( 'Tipo de transacción', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( '<b>Autorización:</b> Es la operación estándar para que tus clientes realicen un pago.<br><b>Preautorización:</b> Esta operación retiene el cargo en la tarjeta del cliente, pero debe ser confirmada por ti en el Portal de Administración del TPV Virtual para que tenga efecto contable.<br><b>Autenticación:</b> Confirma los datos de la tarjeta del cliente pero no retiene el dinero en su cuenta. Para que tenga valor contable, debes confirmar la operación en el Portal de Administración del TPV Virtual, al igual que con la preautorización.', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                            '0' => __( 'Autorización', 'woocommerce' ),
                            '1' => __( 'Preautorización', 'woocommerce' ),
                            '7' => __( 'Autenticación', 'woocommerce' )
                    ),
                ),
                'estado' => array(
                    'title'       => __( 'Estado del pedido al verificarse el pago para las autorizaciones', 'redsys_wc' ),
                    'type'        => 'select',
                    'description' => __( 'Aquí puede configurar el estado en el que se mostrará el pedido en el apartado "Pedidos" de su backoffice una vez el módulo reciba la notificación de que el pago ha sido correcto.', 'redsys_wc' ),
                    'default'     => 'processing',
                    'options'     => array(),
                    'desc_tip'    => true,
                ),
                'estado_preautorizacion' => array(
                    'title'       => __( 'Estado del pedido al verificarse el proceso de preautorización', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Aquí puede configurar el estado en el que se mostrará el pedido en el apartado "Pedidos" de su backoffice al realizar una preautorizacion.', 'woocommerce' ),
                    'default'     => 'on-hold',
                    'options'     => array(),
                    'desc_tip'    => true,
                ),
                'estado_autenticacion' => array(
                    'title'       => __( 'Estado del pedido al verificarse el proceso de autenticación', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Aquí puede configurar el estado en el que se mostrará el pedido en el apartado "Pedidos" de su backoffice al realizar una autenticación.', 'woocommerce' ),
                    'default'     => 'on-hold',
                    'options'     => array(),
                    'desc_tip'    => true,
                ),
                'modal' => array(
                    'title'       => __( 'Habilitar ventana de pago modal', 'woocommerce' ),
                    'type'        => 'select', //checkbox
//                    'label'       => 'Habilitar ventana de pago modal',
                    'description' => __( '<span style="color:#fa7878">Esta funcionalidad se ha descontinuado y ya no recibirá más actualizaciones, por lo que podría desaparecer en un futuro.</span>', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                        '0' => __( 'No', 'woocommerce' ),
                        '1' => __( 'Si', 'woocommerce' )
                    ),
                    'disabled'    => false,
                    'desc_tip'    => false,
                ),
                'withref' => array(
                    'title'       => __( 'Pago por Referencia', 'woocommerce' ),
                    'type'        => 'select', //checkbox
//                    'label'       => 'Activar Pago por Referencia',
                    'description' => __( '<span style="color:#fa7878; font-weight:bold;">( ! )</span> Esta configuración podría requerir activación por parte de su Entidad Bancaria. <br> El Pago por Referencia permite al cliente guardar su tarjeta para futuras compras en formato de Token y de forma totalmente segura.<br>Sólo está disponible si tu comercio utiliza los Bloques de Woocommerce para crear el Checkout.', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                        '0' => __( 'No', 'woocommerce' ),
                        '1' => __( 'Si', 'woocommerce' )
                    )
               ),
               'activar_3ds' => array(
                    'title'       => __( 'Pago seguro usando 3D Secure', 'woocommerce'),
                    'type'        => 'select', //checkbox
    //                'label'       => 'Activar envío de parámetros adicionales 3DSecure',
                    'description' => __( 'Esta opción permite enviar información adicional del cliente que está realizando la compra, proporcionando más seguirdad a la hora de autenticar la operación. Se recomienda el envío de esta información en los datos de la operación.', 'woocommerce' ),
                    'default'     => 'si',
                    'options'     => array(
                            'no' => __( 'Desactivado', 'woocommerce' ),
                            'si' => __( 'Activado', 'woocommerce' )
                    ),
                    'desc_tip'    => true,
                ),
                'mantener_carrito' => array(
                    'title'       => __( 'Redirigir al checkout en caso de error para reintentar la operación', 'woocommerce'),
                    'type'        => 'select', //checkbox
    //                'label'       => 'Con esta opción activa, el carrito no se borrará si se produce un error durante el proceso de pago y el cliente será redirigido al checkout para poder intentarlo de nuevo. No se creará un pedido con esta opción activa.',
                    'description' => __( 'Esta función está desactivada temporalmente', 'woocommerce' ),
                    'default'     => 'no',
                    'options'     => array(
                            'no' => __( 'Desactivado', 'woocommerce' ),
                            'si' => __( 'Activado', 'woocommerce' )
                    ),
                    'desc_tip'    => true,
                    'disabled'    => true,
                ),
                'activar_anulaciones' => array(
                    'title'       => __( 'Realizar una anulación automática en caso de error fatal', 'woocommerce' ),
                    'description' => __( 'Con esta opción activada, si el cliente realiza un pago en la pasarela pero la validación del pedido falla, se emitirá una anulación automática.', 'woocommerce' ),
                    'type'        => 'select',
                    'default'     => '1',
                    'options'     => array(
                        '0' => __( 'No', 'woocommerce' ),
                        '1' => __( 'Si', 'woocommerce' )
                    ),
                    'disabled'    => false,
                    'desc_tip'    => false,
                ),
                'genPedido' => array(
                    'title' => __( 'Método de generación del número de pedido', 'redsys_wc' ),
                    'type' => 'select',
                    'description' => __( 'Esta opción no modifica la forma en la que se identifica la orden en su Backoffice, sino el número de pedido (adaptado para que siempre ocupe doce dígitos) que se envía a Redsys para identificar la operación.<br>Recuerde que en los detalles de cada orden puede ver el número de pedido que identifica la operación en el Portal de Administración del TPV Virtual.', 'redsys_wc' ),
                    'default' => '0',
                    'options' => array(
                            '0' => __( 'Híbrido (recomendado)', 'woocommerce' ),
                            '1' => __( 'Sólo ID del carrito', 'woocommerce' ),
                            '2' => __( 'Aleatorio', 'woocommerce' )
                    ),
                ),
                'pedidoExtendido' => array(
                    'title'       => __( 'El terminal permite número de pedido extendido', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Marque esta opción si su terminal está configurado para admitir números de pedidos extendidos. Esto es útil para tiendas cuyos número de pedidos podrían exceder las doce posiciones que tiene como máximo un número de pedido estándar.<br>Recuerde que debe solicitar a su entidad bancaria que activen esta configuración en su terminal antes de marcar esta opción.', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                        '0' => __( 'No', 'woocommerce' ),
                        '1' => __( 'Si', 'woocommerce' )
                    )
                ),
                'idioma' => array(
                    'title'       => __( 'Permitir al TPV usar el idioma configurado en el navegador del cliente', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Con esta opción activada, la pasarela se mostrará en el idioma de visualización que el cliente haya configurado en los ajustes de su navegador.', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                            '0' => __( 'No', 'woocommerce' ),
                            '1' => __( 'Si', 'woocommerce' )
                    ),
                    'desc_tip'    => true,
                ),
                'tabla_ordenes' => array(
                    'title'       => __( 'Tabla de Wordpress donde se guardan las órdenes de Woocommerce', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Configura aquí la tabla donde se guardan las órdenes de Woocommerce. Por defecto, se usa la tabla de entradas de Wordpress, pero en versiones más nuevas de Woocommerce, es posible que tengas configurado que se haga en una tabla propia de Woocommerce.<br>Puedes consultar tu configuración en Ajustes de Woocommerce > Avanzado > Almacenes de datos personalizado.', 'woocommerce' ),
                    'default'     => '0',
                    'options'     => array(
                            '0' => __( 'Tabla de Post/Entradas wp_posts (por defecto)', 'woocommerce' ),
                            '1' => __( 'Tabla de órdenes de WooCommerce wc_orders', 'woocommerce' )
                    ),
                ),
                'moneda_manual' => array(
                    'title'       => __( 'Moneda personalizada para operaciones', 'woocommerce' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Introduzca el código ISO de la moneda.', ' woocommerce '),
                    'description' => __( '<span style="color:#fa7878; font-weight:bold;">( ! )</span> Esta configuración sobreescribirá la detección automática de moneda, su terminal deberá estar configurado para usar la moneda que aquí establezca si es distinta al Euro.<br>Deje en blanco para usar la detección automática. Use esta configuración sí y sólo sí su comercio está recibiendo errores SIS0015 o SIS0027.', 'woocommerce' ),
                    'default'     => '',
                ),
                'activar_log' => array(
                    'title'       => __( 'Guardar registros de comportamiento', 'woocommerce' ),
                    'type'        => 'select',
                    'description' => __( 'Si activa esta opción, se guardarán registros (logs) de los procesos que realice el módulo. <br> A la hora de notificar cualquier incidencia, los logs completos son de gran utilidad para poder detectar el problema.', 'woocommerce' ),
                    'default'     => '2',
                    'options'     => array(
                            '0' => __( 'No', 'woocommerce' ),
                            '1' => __( 'Sí, sólo informativos', 'woocommerce' ),
                            '2' => __( 'Sí, todos los registros', 'woocommerce' )
                    ),
                    'desc_tip'    => true,
                ),               
                'urlOK' => array(
                    'title'       => __( 'URL para operaciones correctas', 'woocommerce' ),
                    'type'        => 'text',
                    'description' => __( 'Este campo, denominado URL_OK, establece a qué página se redirigirá al cliente al volver de Redsys una vez la operación haya finalizado y esta sea correcta. Si este campo se rellena, se ignorará la configuración del parámetro establecida en el Portal de Administración del TPV Virtual.', 'woocommerce' ),
                    'default'     => __( '', 'woocommerce' ),
                    'desc_tip'    => true,
                ),
                'urlKO' => array(
                    'title'       => __( 'URL para operaciones erróneas', 'woocommerce' ),
                    'type'        => 'text',
                    'description' => __( 'Este campo, denominado URL_KO, establece a qué página se redirigirá al cliente al volver de Redsys una vez la operación haya finalizado y esta haya tenido algún error. Si este campo se rellena, se ignorará la configuración del parámetro establecida en el Portal de Administración del TPV Virtual.', 'woocommerce' ),
                    'default'     => __( '', 'woocommerce' ),
                    'desc_tip'    => true,
                ));
				
				$tmp_estados=wc_get_order_statuses();
				foreach($tmp_estados as $est_id=>$est_na){
					$this->form_fields['estado']['options'][substr($est_id,3)]=$est_na;
                    $this->form_fields['estado_preautorizacion']['options'][substr($est_id,3)]=$est_na;
                    $this->form_fields['estado_autenticacion']['options'][substr($est_id,3)]=$est_na;
				}
    }

    function process_payment( $order_id ) {
        global $woocommerce;
        $order = new WC_Order($order_id);

        $orderIdLog = $order_id . $this->fuc;
        $idLog = generateIdLog($this->activar_log, $this->logString, $orderIdLog);

        $isLogged = is_user_logged_in();
		$userId = $order->get_customer_id();

        escribirLog("DEBUG", $idLog, "**************************");
		escribirLog("INFO ", $idLog, "****** NUEVO PEDIDO ******");
		escribirLog("DEBUG", $idLog, "**************************");

		escribirLog("INFO ", $idLog, "Pago con Tarjeta redirección", null, __METHOD__);
		escribirLog("INFO ", $idLog, "ID del usuario cargado: " . $userId, null, __METHOD__);

        if ($isLogged == true)
			escribirLog("INFO ", $idLog, "El usuario que hace el pedido está logueado en la página", null, __METHOD__);
		else
			escribirLog("INFO ", $idLog, "El usuario que hace el pedido no está logueado en la página", null, __METHOD__);

        $redirectUrl = $this->redirect_url . "&order_id=" . $order_id;

        escribirLog("DEBUG", $idLog, "Redireccionando a " . $redirectUrl . " para continuar...", null, __METHOD__);

        // Return redirect page
        return array(
            'result' 	=> 'success',
            'redirect'	=> $redirectUrl
        );
    }

    function get_redsys_object($order){
        $order_id = $order->get_id();

        $orderIdLog = $order_id . $this->fuc;
        $idLog = generateIdLog($this->activar_log, $this->logString, $orderIdLog);
        escribirLog("DEBUG", $idLog, "Generando formulario para el pedido " . $order_id, null, __METHOD__);
        
        $merchantModule = 'WO-PURv' . MODULE_VERSION;

        escribirLog("DEBUG", $idLog, "Versión del módulo: " . $merchantModule, null, __METHOD__);
        escribirLog("DEBUG", $idLog, "Versión de Wordpress: " . $GLOBALS['wp_version'], null, __METHOD__);
        escribirLog("DEBUG", $idLog, "Versión de WooCommerce: " . WC_VERSION, null, __METHOD__);
        escribirLog("DEBUG", $idLog, "Versión de PHP: " . phpversion(), null, __METHOD__);


        //Recuperamos los datos de config.
        $logActivo=$this->get_option('activar_log');
        $nombre=$this->get_option('name');
        $codigo=$this->get_option('fuc');
        $terminal=$this->get_option('terminal');

        $this->moneda = currency_code(get_option('woocommerce_currency'));
        $moneda=$this->moneda;

        if ( !empty( $this->get_option('moneda_manual') ) )
            $moneda = $this->get_option('moneda_manual');
        
        $clave256=$this->get_option('clave256');	
        $tipopago=intval($this->get_option('tipopago'));
        $idioma=$this->get_option('idioma');
        $entorno=$this->get_option('entorno');

        //Callback
        $urltienda = $this -> notify_url;
        $urlOK=$this->get_option('urlOK');
        $urlKO=$this->get_option('urlKO');

        //Calculo del precio total del pedido
        $currency_decimals = get_option('woocommerce_price_num_decimals');

        $transaction_amount = number_format( (float) ($order->get_total()), intval($currency_decimals), '.', '' );
        $transaction_amount = str_replace('.','',$transaction_amount);
        $transaction_amount = floatval($transaction_amount);

        // Descripción de los productos
        $productos="";
        $products = WC()->cart->cart_contents;
        foreach ($products as $product) {
            $productos .= $product['quantity'].'x'.$product['data']->get_title().'/';
        }

        $numpedido = generaNumeroPedido($order_id, $this->get_option('genPedido'), $this->get_option('pedidoExtendido') == 1); 
        escribirLog("INFO ", $idLog, "Numero de pedido enviado a Redsys ─ [Ds_Merchant_Order]: " . $numpedido, null, __METHOD__);

        if ($this->get_option( 'idioma' )) {

            $idioma_web = substr ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
            switch ($idioma_web) {
                case 'es':
                $idiomaFinal='001';
                break;
                case 'en':
                $idiomaFinal='002';
                break;
                case 'ca':
                $idiomaFinal='003';
                break;
                case 'fr':
                $idiomaFinal='004';
                break;
                case 'de':
                $idiomaFinal='005';
                break;
                case 'nl':
                $idiomaFinal='006';
                break;
                case 'it':
                $idiomaFinal='007';
                break;
                case 'sv':
                $idiomaFinal='008';
                break;
                case 'pt':
                $idiomaFinal='009';
                break;
                case 'pl':
                $idiomaFinal='011';
                break;
                case 'gl':
                $idiomaFinal='012';
                break;
                case 'eu':
                $idiomaFinal='013';
                break;
                default:
                $idiomaFinal='002';
            }     
        } else
            $idiomaFinal = '001';

        $merchantTitular = createMerchantTitular($order->get_billing_first_name(), $order->get_billing_last_name(), $order->get_billing_email());

        // Generamos la firma	
        $miObj = new RedsyspurAPI;
        $miObj->setParameter("DS_MERCHANT_AMOUNT",$transaction_amount);
        $miObj->setParameter("DS_MERCHANT_ORDER",$numpedido);
        $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$codigo);
        $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
        $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $tipopago);
        $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
        $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$urltienda);
        $miObj->setParameter("DS_MERCHANT_urlOK",$urlOK ? $urlOK : $this->get_return_url($order));
        $miObj->setParameter("DS_MERCHANT_urlKO",$urlKO ? $urlKO : $order->get_cancel_order_url());
        $miObj->setParameter("Ds_Merchant_ConsumerLanguage",$idiomaFinal);
        $miObj->setParameter("Ds_Merchant_ProductDescription",$productos);
        $miObj->setParameter("Ds_Merchant_Titular", $merchantTitular);
        $miObj->setParameter("Ds_Merchant_MerchantName",$nombre);
        $miObj->setParameter("Ds_Merchant_PayMethods","");
        $miObj->setParameter("Ds_Merchant_Module", $merchantModule);

        $merchantData = createMerchantData($this->moduleComent, $order_id);
        $miObj->setParameter ( "Ds_Merchant_MerchantData", b64url_encode($merchantData) );

        ///// 3DSecure
        if ($this->activar_3ds == 'si')
            include_once 'redsys_3ds.php';

        return $miObj;
    }

    function generate_redsys_form( $order_id ) {
        $order = new WC_Order($order_id);
        $miObj = $this->get_redsys_object($order);

        $orderIdLog = $order_id . $this->fuc;
        $idLog = generateIdLog($this->activar_log, $this->logString, $orderIdLog);
        
        //Datos de configuración
        $redsys_args = array(
            'Ds_SignatureVersion' => getVersionClave(),
            'Ds_MerchantParameters' => '',
            'Ds_Signature' => ''
        );

        //Se establecen los input del formulario con los datos del pedido y la redirección
        $redsys_args_array = array();
        foreach($redsys_args as $key => $value){
            $redsys_args_array[] = "<input type='hidden' name='$key' value='$value'/>";
        }

        //Clave del comercio que se extrae de la configuración del comercio
        // Se generan los parámetros de la petición
        $redsys_options_args['Ds_MerchantParameters_New'] = $miObj->createMerchantParameters();
        $redsys_options_args['Ds_Signature_New'] = $miObj->createMerchantSignature($this->clave256);

        $redsys_options_args['Ds_MerchantParameters_SaveRef'] = "";
        $redsys_options_args['Ds_Signature_SaveRef'] = "";
        $redsys_options_args['Ds_MerchantParameters_WithRef'] = "";
        $redsys_options_args['Ds_Signature_WithRef'] = "";

        $hasReference = false;
        $allowReference = $this->get_option('withref') == 1 && is_user_logged_in();
		$maskedCard = '';
        if($allowReference){
            $miObj->setParameter("Ds_Merchant_Identifier", "REQUIRED");
            $redsys_options_args['Ds_MerchantParameters_SaveRef'] = $miObj->createMerchantParameters();
            $redsys_options_args['Ds_Signature_SaveRef'] = $miObj->createMerchantSignature($this->clave256);

            $idCustomer = $order->get_customer_id();
            $ref=WC_Redsys_Ref::getCustomerRef($idCustomer, $idLog);

            $hasReference = ($ref != null);
            if($hasReference){
                $miObj->setParameter("Ds_Merchant_Identifier", $ref[0]);
                $redsys_options_args['Ds_MerchantParameters_WithRef'] = $miObj->createMerchantParameters();
                $redsys_options_args['Ds_Signature_WithRef'] = $miObj->createMerchantSignature($this->clave256);
				$maskedCard = $ref[1];
            }
        }

        if (!$hasReference) { //LOGGING DE PARÁMETROS
            if ($allowReference) { //Solicitud de referencia.
                escribirLog("DEBUG", $idLog, "Parámetros de la solicitud: " . $redsys_options_args['Ds_MerchantParameters_SaveRef'], null, __METHOD__ );
                escribirLog("DEBUG", $idLog, "Firma calculada y enviada : " . $redsys_options_args['Ds_Signature_SaveRef'], null, __METHOD__ );
            } else { // Pago normal con tarjeta.
                escribirLog("DEBUG", $idLog, "Parámetros de la solicitud: " . $redsys_options_args['Ds_MerchantParameters_New'], null, __METHOD__ );
                escribirLog("DEBUG", $idLog, "Firma calculada y enviada : " . $redsys_options_args['Ds_Signature_New'], null, __METHOD__ );
            }
        } else { //Pago con referencia.
            escribirLog("DEBUG", $idLog, "Parámetros de la solicitud: " . $redsys_options_args['Ds_MerchantParameters_WithRef'], null, __METHOD__ );
            escribirLog("DEBUG", $idLog, "Firma calculada y enviada : " . $redsys_options_args['Ds_Signature_WithRef'], null, __METHOD__ );
        }

        //Se establece el entorno del SIS
        if($this->entorno==0){
            $env="https://sis-t.redsys.es:25443/sis/realizarPago/utf-8";
        }else{
            $env="https://sis.redsys.es/sis/realizarPago/utf-8";
        }

        //Formulario que envía los datos del pedido y la redirección al formulario de acceso al TPV
        if($allowReference && $this->has_checkout_blocks()){
            $html = file_get_contents(ABSPATH.'/wp-content/plugins/redsyspur/pages/templates/redirect.html');

            $withRef = (bool) WC()->session->get( 'redirect_option_with_ref', false );
            $saveRef = (bool) WC()->session->get( 'redirect_option_save_ref', false );

            if($withRef){
                $redsys_args['Ds_MerchantParameters'] = $redsys_options_args['Ds_MerchantParameters_WithRef'];
                $redsys_args['Ds_Signature'] = $redsys_options_args['Ds_Signature_WithRef'];
            }else{
                if($saveRef){
                    $redsys_args['Ds_MerchantParameters'] = $redsys_options_args['Ds_MerchantParameters_SaveRef'];
                    $redsys_args['Ds_Signature'] = $redsys_options_args['Ds_Signature_SaveRef'];
                }else{
                    $redsys_args['Ds_MerchantParameters'] = $redsys_options_args['Ds_MerchantParameters_New'];
                    $redsys_args['Ds_Signature'] = $redsys_options_args['Ds_Signature_New'];
                }
            }

            $html=str_replace("{env}", $env, $html);
            $html=str_replace("{Ds_SignatureVersion}", $redsys_args['Ds_SignatureVersion'], $html);
            $html=str_replace("{Ds_MerchantParameters}", $redsys_args['Ds_MerchantParameters'], $html);
            $html=str_replace("{Ds_Signature}", $redsys_args['Ds_Signature'], $html);
        }else{
            $html = file_get_contents(ABSPATH.'/wp-content/plugins/redsyspur/pages/templates/payment.html');

            $html=str_replace("{env}", $env, $html);
            $html=str_replace("{Ds_SignatureVersion}", $redsys_args['Ds_SignatureVersion'], $html);
            $html=str_replace("{Ds_MerchantParameters}", $redsys_options_args['Ds_MerchantParameters_New'], $html);
            $html=str_replace("{Ds_Signature}", $redsys_options_args['Ds_Signature_New'], $html);
        }

        return $html;
    }

    function redirect_options(){
        if(array_key_exists('withRef', $_POST)){
            WC()->session->set( 'redirect_option_with_ref', $_POST['withRef'] == 'true' );
        }
        if(array_key_exists('saveRef', $_POST)){
            WC()->session->set( 'redirect_option_save_ref', $_POST['saveRef'] == 'true' );
        }
    }

    function redirect(){
        if ( isset( $_GET['order_id'] ) ) {
            $order_id = $_GET['order_id'];
            echo $this -> generate_redsys_form($order_id);
        }
        exit;
    }

    function get_modal_parameters($order){
        $miObj = $this->get_redsys_object($order);

        $params = array(
            'Ds_SignatureVersion' => getVersionClave(),
            'Ds_MerchantParameters' => $miObj->createMerchantParameters(),
            'Ds_Signature' => $miObj->createMerchantSignature($this->clave256)
        );        

        if($this->get_option('entorno')==0){
            $params['url_modal'] = "https://sis-t.redsys.es:25443/sis/redsys-modal/js/redsys-modal.js";
            $params['environment_modal'] = "test";
        }else{
            $params['url_modal'] = "https://sis.redsys.es/sis/redsys-modal/js/redsys-modal.js";
            $params['environment_modal'] = "prod";
        }

        $urlOK = $this->get_option('urlOK');
        $urlKO = $this->get_option('urlKO');
        
        $params['url_ok'] = $urlOK ? $urlOK : $this->get_return_url($order);
        $params['url_ko'] = $urlKO ? $urlKO : $order->get_cancel_order_url();

        return $params;
    }

    function get_next_order_id(){
        global $wpdb;
        global $woocommerce;

        $order = wc_get_order($woocommerce->session->order_awaiting_payment);
        if($woocommerce->session->order_awaiting_payment && $order->get_status() == 'pending'){
            return $woocommerce->session->order_awaiting_payment;
        }else{
            $statuses = array_keys(wc_get_order_statuses());
            $statuses = implode( "','", $statuses );
        
		// Getting last Order ID (max value)

            switch ($this->get_option('tabla_ordenes')) {

                case 0:
                    $results = $wpdb->get_col( "
                        SELECT MAX(ID)+1 FROM {$wpdb->prefix}posts
                        WHERE post_type LIKE 'shop_order'
                        AND post_status IN ('$statuses')
                    " );
                    break;
                    
                case 1:
                    $results = $wpdb->get_col( "
                        SELECT MAX(id)+1 FROM {$wpdb->prefix}wc_orders
                    " );
                    break;

                default:
                    return;

            }

            return reset($results);
        }
    }

	function payment_fields_api(){
		$this->payment_fields();
		exit;
	}

    function payment_fields(){
        $allowReference = $this->get_option('withref') == 1 && is_user_logged_in();

        if( $this->get_option('modal') == 1 ) {
            if(isset($_POST['post_data'])){
                parse_str($_POST['post_data'], $post_data);
            }

            //Create fake order
            $order = new WC_Order();
            try{
                $order_id = (is_array($post_data) && array_key_exists('order_id', $post_data)) ? $post_data['order_id'] : $this->get_next_order_id();
                $order->set_id($order_id);
                $order->set_total(WC()->cart->total);

                $order->set_billing_first_name($post_data['billing_first_name']);
                $order->set_billing_last_name($post_data['billing_last_name']);
                $order->set_billing_company($post_data['billing_company']);
                $order->set_billing_country($post_data['billing_country']);
                $order->set_billing_address_1($post_data['billing_address_1']);
                $order->set_billing_address_2($post_data['billing_address_2']);
                $order->set_billing_postcode($post_data['billing_postcode']);
                $order->set_billing_city($post_data['billing_city']);
                $order->set_billing_state($post_data['billing_state']);
                $order->set_billing_phone($post_data['billing_phone']);
                $order->set_billing_email($post_data['billing_email']);
                // $order->set_shipping_first_name($post_data['shipping_first_name']);
                // $order->set_shipping_last_name($post_data['shipping_last_name']);
                // $order->set_shipping_company($post_data['shipping_company']);
                // $order->set_shipping_country($post_data['shipping_country']);
                // $order->set_shipping_address_1($post_data['shipping_address_1']);
                // $order->set_shipping_address_2($post_data['shipping_address_2']);
                // $order->set_shipping_postcode($post_data['shipping_postcode']);
                // $order->set_shipping_city($post_data['shipping_city']);
                // $order->set_shipping_state($post_data['shipping_state']);
            }catch(Exception $e){
                echo("Por favor, rellene los datos de facturación");
                return;
            }

            $params = $this->get_modal_parameters($order);

            $html = file_get_contents(ABSPATH.'/wp-content/plugins/redsyspur/pages/templates/paymentmodal.html');

            $html = str_replace("{url_modal}", $params['url_modal'], $html);
            $html = str_replace("{environment_modal}", $params['environment_modal'], $html);
            $html = str_replace("{url_ok}", $params['url_ok'], $html);
            $html = str_replace("{url_ko}", $params['url_ko'], $html);

            $html = str_replace("{Ds_SignatureVersion}", $params['Ds_SignatureVersion'],$html);
            $html = str_replace("{Ds_MerchantParameters}", $params['Ds_MerchantParameters'], $html);
            $html = str_replace("{Ds_Signature}", $params['Ds_Signature'], $html);

            echo($html);
        }else if($allowReference && $this->has_checkout_blocks()){
            if(isset($_POST['post_data'])){
                parse_str($_POST['post_data'], $post_data);
            }

            //Create fake order
            $order = new WC_Order();
            try{
                $order_id = (is_array($post_data) && array_key_exists('order_id', $post_data)) ? $post_data['order_id'] : $this->get_next_order_id();
                $order->set_id($order_id);
                $order->set_total(WC()->cart->total);

                $order->set_billing_first_name($post_data['billing_first_name']);
                $order->set_billing_last_name($post_data['billing_last_name']);
                $order->set_billing_company($post_data['billing_company']);
                $order->set_billing_country($post_data['billing_country']);
                $order->set_billing_address_1($post_data['billing_address_1']);
                $order->set_billing_address_2($post_data['billing_address_2']);
                $order->set_billing_postcode($post_data['billing_postcode']);
                $order->set_billing_city($post_data['billing_city']);
                $order->set_billing_state($post_data['billing_state']);
                $order->set_billing_phone($post_data['billing_phone']);
                $order->set_billing_email($post_data['billing_email']);
                // $order->set_shipping_first_name($post_data['shipping_first_name']);
                // $order->set_shipping_last_name($post_data['shipping_last_name']);
                // $order->set_shipping_company($post_data['shipping_company']);
                // $order->set_shipping_country($post_data['shipping_country']);
                // $order->set_shipping_address_1($post_data['shipping_address_1']);
                // $order->set_shipping_address_2($post_data['shipping_address_2']);
                // $order->set_shipping_postcode($post_data['shipping_postcode']);
                // $order->set_shipping_city($post_data['shipping_city']);
                // $order->set_shipping_state($post_data['shipping_state']);
            }catch(Exception $e){
                echo("Por favor, rellene los datos de facturación");
                return;
            }

            $orderIdLog = $order_id . $this->fuc;
            $idLog = generateIdLog($this->activar_log, $this->logString, $orderIdLog);

            $hasReference = false;
            $allowReference = $this->get_option('withref') == 1 && is_user_logged_in();
            $maskedCard = '';
            $brand_front = 'Tarjeta';
            if($allowReference){    
                $idCustomer = get_current_user_id();
                $ref=WC_Redsys_Ref::getCustomerRef($idCustomer, $idLog);
    
                $hasReference = ($ref != null);
                if($hasReference){
                    $maskedCard = $ref[1];

                    switch($ref[2]) {
                        case 1:
                            $brand_front = 'VISA';
                            break;
                        case 2:
                            $brand_front = 'Mastercard';
                            break;
                    }        
                }
            }


            $html = file_get_contents(ABSPATH.'/wp-content/plugins/redsyspur/pages/templates/redirect_options.html');

            $html = str_replace("{maskedCard}", $maskedCard, $html);
            $html = str_replace("{brand}", $brand_front, $html);
            $html = str_replace("{description}", $this->get_option('description'), $html);
            $html = str_replace("{redirectOptionsUrl}", $this->redirect_options_url, $html);

            echo($html);
        }else{
            echo($this->get_description());
        }
    }

    function check_rds_response() {

        if (!empty( $_REQUEST ) ) {
            if (!empty( $_POST ) ) {//URL DE RESP. ONLINE

                /** Recoger datos de respuesta **/
                $version      = $_POST["Ds_SignatureVersion"];
                $datos        = $_POST["Ds_MerchantParameters"];
                $firma_remota = $_POST["Ds_Signature"];

                // Se crea Objeto
                $miObj = new RedsyspurAPI;

                /** Se decodifican los datos enviados y se carga el array de datos **/
                $decodec = $miObj->decodeMerchantParameters($datos);
                $miObj->stringToArray($decodec);

                $merchantData = b64url_decode($miObj->getParameter('Ds_MerchantData'));
                $merchantData = json_decode( $merchantData ); 

                $idCart = $merchantData->idCart;

                /** Comienzo de Logging */
                $orderIdLog = $idCart . $this->fuc;
                $idLog = generateIdLog($this->activar_log, $this->logString, $orderIdLog);

                escribirLog("INFO ", $idLog, "***** VALIDACIÓN DE LA NOTIFICACIÓN  ──  PEDIDO " . $miObj->getParameter('Ds_Order') . " *****");
                escribirLog("DEBUG", $idLog, "Parámetros de la respuesta del SIS: " . $datos);

                /** Clave **/
                $kc = $this->get_option( 'clave256' );
                escribirLog("DEBUG", $idLog, "Firmando con la clave SHA-256 " . substr($kc, 0, 3) . "*");

                /** Se calcula la firma **/
                $firma_local = $miObj->createMerchantSignatureNotif($kc,$datos);	

                escribirLog("DEBUG", $idLog, "Firma de los parámetros recibida  : " . $firma_remota);
                escribirLog("DEBUG", $idLog, "Firma de los parámetros calculada : " . $firma_local);             

                /** Extraer datos de la notificación **/
                $total     = $miObj->getParameter('Ds_Amount');
                $pedido    = $miObj->getParameter('Ds_Order');
                $codigo    = $miObj->getParameter('Ds_MerchantCode');
                $moneda    = $miObj->getParameter('Ds_Currency');
                $respuesta = $miObj->getParameter('Ds_Response');
                $id_trans  = $miObj->getParameter('Ds_AuthorisationCode');

                $merchantData = b64url_decode($miObj->getParameter('Ds_MerchantData'));
                $merchantData = json_decode( $merchantData );               

                escribirLog("DEBUG", $idLog, "Respuesta del SIS ─ [Ds_Response]: " . $respuesta, null, __METHOD__);

                $estadoFinal = $this->get_option( 'estado' );
                switch($miObj->getParameter('Ds_TransactionType')){
                    case 0:
                        $estadoFinal = $this->get_option( 'estado' );
                        break;
                    case 1:
                        $estadoFinal = $this->get_option( 'estado_preautorizacion' );
                        break;
                    case 7:
                        $estadoFinal = $this->get_option( 'estado_autenticacion' );
                        break;
                }

                $metodoOrder = "No hay respuesta, estado desconocido.";

                if ($respuesta < 101)
                    $metodoOrder = "Autorizada " . $id_trans;    
                else if ($respuesta >= 101)
                    $metodoOrder = "Denegada " . $respuesta;

                /** Análisis de respuesta del SIS. */
                $erroresSIS = array();
                $errorBackofficeSIS = "";

                include 'erroresSIS.php';

                if (array_key_exists($respuesta, $erroresSIS)) {
                    
                    $errorBackofficeSIS  = $respuesta;
                    $errorBackofficeSIS .= ' - '.$erroresSIS[$respuesta] . '.';
                
                } else {

                    $errorBackofficeSIS = "Código de respuesta " . $respuesta . " no registrado en el módulo. Consulte el Portal de Administración del TPV Virtual.";
                }

                escribirLog("INFO ", $idLog, $errorBackofficeSIS, null, __METHOD__);

                //Si no existe la orden, se intenta obtener la siguiente
                if(!wc_get_order($idCart)){
                    $idCart++;
                    escribirLog("INFO ", $idLog, "Se ha actualizado el idCart a $idCart al haber fallado la obtención de orden con el número enviado a Redsys.", null, __METHOD__);
                }

                $order = new WC_Order($idCart);

                if ($miObj->getParameter('Ds_TransactionType') == 1)
                    $order->add_order_note( __('[REDSYS] Esta orden se ha validado usando una PREAUTORIZACIÓN. Recuerde que para realizar la confirmación, deberá hacerlo desde el Portal de Administración del TPV Virtual', 'woocommerce'));

                if (($miObj->getParameter('Ds_TransactionType') == 0) && ($order->get_status() == $estadoFinal || $order->get_status() == "completed")) {

                    escribirLog("INFO ", $idLog, "El pedido con ID de carrito " . $idCart . " (" . $pedido . ") ya está marcado con un estado final, por lo que no se ha modificado su estado.");
                    $order->add_order_note( __('[REDSYS] ADVERTENCIA: Se ha recibido una notificación, pero el pedido ya está marcado con un estado final, por lo que no se ha modificado su estado con el resultado recibido. Respuesta del SIS: ', 'woocommerce') . $errorBackofficeSIS);
                
                } else if (checkFirma($firma_local, $firma_remota) 
                    && checkRespuesta($respuesta)
                    && checkMoneda($moneda)
                    && checkFuc($codigo)
                    && checkPedidoAlfaNum($pedido, $this->pedidoExtendido == 1)
                    && checkImporte($total)
                ) {
                    // Formatear variables
                    $respuesta = intval($respuesta);

                    if ($respuesta < 101 && checkAutCode($id_trans)) {
                        $order->add_order_note( __('[REDSYS] ', 'woocommerce') . $metodoOrder);
                        $order->add_order_note( __('[REDSYS] Respuesta del SIS: ', 'woocommerce') . $errorBackofficeSIS);
                        $order->update_status($estadoFinal,__( '[REDSYS] El pedido es válido y se ha registrado correctamente. Número de pedido enviado a Redsys: ', 'woocommerce' ) . $pedido);

                        WC_Redsys_Refund::saveOrderId($idCart, $pedido, $total);
                        
                        escribirLog("INFO ", $idLog, "El pedido con ID de carrito " . $idCart . " (" . $pedido . ") es válido y se ha registrado correctamente.", null, __METHOD__);

//                        $order->reduce_order_stock();
                        wc_reduce_stock_levels($order->id);
                        WC()->cart->empty_cart();

                        if($this->get_option('withref') == 1){
                            $reference = $miObj->getParameter('Ds_Merchant_Identifier');
                            if($reference!=null){
                                $idCustomer = $order->get_customer_id();
                                $cardNumber=$miObj->getParameter('Ds_Card_Number');
                                $brand=$miObj->getParameter('Ds_Card_Brand');
                                $cardType=$miObj->getParameter('Ds_Card_Type');
                    
                                WC_Redsys_Ref::saveReference($idCustomer, $reference, $cardNumber, $brand, $cardType, $idLog, $idCart);
                            }
                        }

                        //wp_redirect(WC()->plugin_url()."/includes/gateways/redsys/pages/sucess.php?pedido=".$idCart);
                    }
                    else {
                        $order = new WC_Order($idCart);
                        $order->add_order_note( __('[REDSYS] ', 'woocommerce') . $metodoOrder);
                        $order->add_order_note( __('[REDSYS] Respuesta del SIS: ', 'woocommerce') . $errorBackofficeSIS);
                        $order->update_status('cancelled',__( '[REDSYS] El pedido ha finalizado con errores. Número de pedido enviado a Redsys: ', 'woocommerce' ) . $pedido);
                        WC()->cart->empty_cart();
                        
                        //$this->log->add( 'redsys', 'Operación finalizada. PEDIDO ACEPTADO ');
                        escribirLog("INFO ", $idLog, "El pedido con ID de carrito " . $idCart . " (" . $pedido . ") es válido pero ha finalizado con errores.", null, __METHOD__);

                        //wp_redirect(WC()->plugin_url()."/includes/gateways/redsys/pages/failure.php?pedido=".$idCart);
                    }
                }// if (firma_local=firma_remota)
                else {
                    // Fallo de firma o algún otro parámetro
                    // Se vacía siempre el carro por motivos de seguridad
                    $order->add_order_note( __('[REDSYS] ', 'woocommerce') . $metodoOrder);
                    $order->add_order_note( __('[REDSYS] La validación del pedido no se ha realizado correctamente. Acceda al Portal de Administración del TPV Virtual para comprobar el estado del pago. Respuesta del SIS: ', 'woocommerce') . $errorBackofficeSIS);
                    
                    if ($respuesta < 101) {
                        if(WC_Redsys_Refund::cancellation($this, $pedido, $total, $miObj->getParameter('Ds_TransactionType') == 0, $idLog, $this->activar_anulaciones)){
                            $order->update_status('cancelled',__( '[REDSYS] Se ha producido un error al validar alguno de los parámetros. Compruebe el Log generado en wp_content/uploads/wc-logs/REDSYS{fecha}{id}.log o en el Backoffice de WooCommerce. Número de pedido enviado a Redsys: ', 'woocommerce' ) . $pedido);
                            escribirLog("ERROR", $idLog, "ERROR VALIDANDO EL PEDIDO, PERO SE HA RECIBIDO RESPUESTA OK POR PARTE DE REDSYS ── El pedido ha podido ser cobrado y anulado.", null, __METHOD__ );
                        }else{
                            $order->update_status('failed',__( '[REDSYS] ATENCIÓN: Se ha producido un error validando el pedido, pero la respuesta recibida de Redsys es OK - 0000. El pedido ha podido ser cobrado aunque figure como cancelado. Compruebe el Log generado en wp_content/uploads/wc-logs/REDSYS{fecha}{id}.log o en el Backoffice de WooCommerce. Número de pedido enviado a Redsys: ', 'woocommerce' ) . $pedido);
                            escribirLog("ERROR", $idLog, "ERROR VALIDANDO EL PEDIDO, PERO SE HA RECIBIDO RESPUESTA OK POR PARTE DE REDSYS ── El pedido ha podido ser cobrado aunque figure como cancelado.", null, __METHOD__ );
                        }
                    } else {
                        $order->update_status('cancelled',__( '[REDSYS] Se ha producido un error al validar alguno de los parámetros. Compruebe el Log generado en wp_content/uploads/wc-logs/REDSYS{fecha}{id}.log o en el Backoffice de WooCommerce. Número de pedido enviado a Redsys: ', 'woocommerce' ) . $pedido);                      
                    }

                    WC()->cart->empty_cart();
                    escribirLog("INFO ", $idLog, "La validación del pedido con ID de carrito " . $idCart . " (" . $pedido . ") no se ha realizado correctamente. Acceda al Portal de Administración del TPV Virtual para comprobar el estado del pago.", null, __METHOD__);
                    escribirLog("ERROR", $idLog, "Error validando el pedido con ID de carrito " . $idCart . " (" . $pedido . "). Resultado de las validaciones [Firma|Respuesta|Moneda|FUC|Pedido|Importe]: [" . checkFirma($firma_local, $firma_remota) . "|" . checkRespuesta($respuesta) . "|" . checkMoneda($moneda) . "|" . checkFuc($codigo) . "|" . checkPedidoAlfaNum($pedido, $this->pedidoExtendido == 1) . "|" . checkImporte($total) . "]", null, __METHOD__ );
                    //wp_redirect(WC()->plugin_url()."/includes/gateways/redsys/pages/failure.php?pedido=".$idCart);
                }		
            }
            else{
                wp_die( '<img src="'.home_url().'/wp-content/plugins/redsys/pages/assets/images/cross.png" alt="Desactivado" title="Desactivado" />
                Fallo en el proceso de pago.<br>Su pedido ha sido cancelado.' );
            }
        } 
        else{
            wp_die( '<img src="'.home_url().'/wp-content/plugins/redsys/pages/assets/images/cross.png" alt="Desactivado" title="Desactivado" />
            Fallo en el proceso de pago.<br>Su pedido ha sido cancelado.' );
        }

    }

    function receipt_page( $order_id ) {
        if( $this->get_option('modal') == 1 ) {
            $order = new WC_Order($order_id);
            $urlOK = $this->get_option('urlOK');
            header("Location: " . ($urlOK ? $urlOK : $this->get_return_url($order)));
            
            exit();
        }
    }

    function advertencia_sandbox() {
        if ( $this->entorno == 0 && $this->enabled == 'yes' ) {
            wc_print_notice( sprintf(
                __("%s El método de pago '%s' está configurado para operar en entorno de pruebas, por lo que los %s de esta orden no tendrán efecto contable si este método de pago es utilizado.", "woocommerce"),
                '<strong>' . __("Advertencia:", "woocommerce") . '</strong>',
                $this->title,
                strip_tags( wc_price( WC()->cart->get_subtotal() ) )
            ), 'notice' );
        }
    }

	public function process_refund($order_id, $amount = 0, $reason = '', $idLog = null){
        $idLog = generateIdLog($this->activar_log, $this->logString, $order_id);

		return WC_Redsys_Refund::refund($this, $order_id, $amount, $reason, $idLog);
    }

    public function has_checkout_blocks(){
        return WC_Blocks_Utils::has_block_in_page( wc_get_page_id('checkout'), 'woocommerce/checkout' );
    }
}