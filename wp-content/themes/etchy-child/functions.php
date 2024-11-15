<?php

if ( ! function_exists( 'etchy_child_theme_enqueue_scripts' ) ) {
	/**
	 * Function that enqueue theme's child style
	 */
	function etchy_child_theme_enqueue_scripts() {
		$main_style = 'etchy-main';
		
		wp_enqueue_style( 'etchy-child-style', get_stylesheet_directory_uri() . '/style.css', array( $main_style ) );
	}
	
	add_action( 'wp_enqueue_scripts', 'etchy_child_theme_enqueue_scripts' );
}

// Incluir Bootstrap CSS
function bootstrap_css() {
	wp_enqueue_style( 'bootstrap_css', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', 
  					array(), 
  					'4.1.3'
  					); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_css');


// Incluir Bootstrap JS y dependencia popper
function bootstrap_js() {
	wp_enqueue_script( 'popper_js', 
  					'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', 
  					array(), 
  					'1.14.3', 
  					true); 
	wp_enqueue_script( 'bootstrap_js', 
  					'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', 
  					array('jquery','popper_js'), 
  					'4.1.3', 
  					true); 
}
add_action( 'wp_enqueue_scripts', 'bootstrap_js');

function custom_loginlogo() {
	echo "<style type='text/css'>
	h1 a {background-image: url('https://masquelibrosdigital.com/wp-content/webp-express/webp-images/uploads/2023/09/logo-masquelibros.png.webp') !important;    width: 300px !important;
		height: 80px !important;
		background-size: 270px 79px !important; }
	</style>";
}
add_action('login_head', 'custom_loginlogo');

function cmplz_custom_banner_file($path, $filename){
    if ($filename === 'cookiebanner.php' ) {
      error_log("change path to ".'/wp-content/themes/etchy-child/cookiebanner.php');
      return 'wp-content/themes/etchy-child/cookiebanner/cookiebanner.php';
    }
    return $path;
} 
add_filter('cmplz_template_file', 'cmplz_custom_banner_file', 10, 2);

// * Eliminar comentario después de las notas
add_filter ( 'comment_form_defaults' , 'afn_custom_comment_form' );
function afn_custom_comment_form ( $fields ) {
$fields[ 'comment_notes_after' ] = '' ; // Elimina comentario después de las notas
return $fields ;
}

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

/*Eliminar el marcaje schema de las páginas Categoria y Tienda*/
function wc_remove_product_schema_product_archive() {
	remove_action( 'woocommerce_shop_loop', array( WC()->structured_data, 'generate_product_data' ), 10, 0 );
	}

add_action( 'woocommerce_init', 'wc_remove_product_schema_product_archive' );


function year_shortcode () {
	$year = date_i18n ('Y');
	return $year;
}

add_shortcode ('year', 'year_shortcode');

function bbloomer_only_one_in_cart( $passed ) {
	wc_empty_cart();
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 9999 );

function ocultar_section_en_pagina_61() {
    if (is_page(61)) {
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
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

// Añade código al header
add_action('wp_head', 'metas_header');
function metas_header(){
?>


<?php
}

// Añade código al principio del body
add_action( 'wp_body_open', 'scripts_comienzo_body' );
function scripts_comienzo_body() {
    ?>


    <?php
}

// Añade código al principio del body
add_action( 'wp_footer', 'scripts_footer' );
function scripts_footer() {
    ?>

    <?php
}

function remplazar_alts_vacios( $content ) {
    $post = new DOMDocument();
    $post->loadHTML( '<?xml encoding="utf-8" ?>' . $content );


    $images = $post->getElementsByTagName( 'img' );
    // var_dump($images);
    // die();
    foreach ( $images as $image ) {


        if ( empty( $image->getAttribute( 'alt' ) ) ) {


            $src = $image->getAttribute( 'src' );
            $alt = pathinfo( $src, PATHINFO_FILENAME );
            // $alt .= '';
            $image->setAttribute( 'alt', $alt );
           
        }
    }


    $content = $post->saveHTML();


    return $content;


}
add_filter( 'the_content', 'remplazar_alts_vacios',1000 );


function bloggerpilot_gravatar_alt($bloggerpilotGravatar) {
if (have_comments()) {
    $alt = get_comment_author();
}
else {
    $alt = get_the_author_meta('display_name');
}
$bloggerpilotGravatar = str_replace('alt=\'\'', 'alt=\'Avatar para ' . $alt . '\'', $bloggerpilotGravatar);
return $bloggerpilotGravatar;
}
add_filter('get_avatar', 'bloggerpilot_gravatar_alt');
  
function add_nofollow_enlaces( $content ) {
    $post = new DOMDocument();
    libxml_use_internal_errors(true);
    $post->loadHTML( '<?xml encoding="utf-8" ?>' . $content );
    libxml_clear_errors();
    $enlaces = $post->getElementsByTagName( 'a' );
  
  
    foreach ( $enlaces as $enlace ) {
      if ( !empty( $enlace->getAttribute( 'href' ) ) && ( $enlace->getAttribute( 'href' )!='#' )) {
        $addNofollow = $enlace->getAttribute( 'href' );
        $rel = $enlace->getAttribute('rel');
        if (!empty($rel)) {
            $rel .= ' nofollow';
        } else {
            $rel = 'nofollow';
        }
        if ( (strpos($addNofollow, "twitter")!==false) ||
        (strpos($addNofollow, "instagram")!==false) ||
        (strpos($addNofollow, "youtube")!==false) ||
        (strpos($addNofollow, "legal")!==false) ||
        (strpos($addNofollow, "privacidad")!==false) ||
        (strpos($addNofollow, "cookies")!==false) ||
        (strpos($addNofollow, "facebook")!==false) ||
        (strpos($addNofollow, "whatsapp")!==false) ||
        (strpos($addNofollow, "mailto:")!==false) ||
        (strpos($addNofollow, "tel")!==false)
        ) {
          $enlace->setAttribute( 'rel', $rel );
        }
      }
    }
    $content = $post->saveHTML();
    return $content;
}
add_filter( 'the_content', 'add_nofollow_enlaces',999 );

//api optimus
function sendXmlOverPost($url, $xml) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	// For xml, change the content-type.
	curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
	
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned
	
	// Send to remote and return data to caller.
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
//api optimus aniadir usuario
add_action( 'user_register', 'crearusuario' );
function crearusuario($user_id){

	// var_dump($user_id);
	// var_dump($_POST);


	$url_base = "http://81.42.209.224:8080/optwebsvcs";
	$url_db = "?db=pruebas";

	// obtener id
	$url_funcion = "/customer/nextCustomerCode";
	$url_nextCustomerCode = $url_base.$url_funcion.$url_db;
	$xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<nextCustomerCode>
	<prefix>CLI-</prefix>
	<numDigits>6</numDigits>
	</nextCustomerCode>';
	$response = sendXmlOverPost($url_nextCustomerCode, $xml);
	$response_xml = new SimpleXMLElement($response);
	$newCustomerCode = (array)$response_xml->customerCode;
	
	//crear usuario
	$url_funcion = "/customer/addCustomer";
	$addCustomer = $url_base.$url_funcion.$url_db;
	$xml = '<?xml version="1.0" encoding="UTF-8"?>
	<customer>
	 <customerCode>'.$newCustomerCode[0].'</customerCode>
	 <companyName>masquelibros</companyName>
	 <currencyCode></currencyCode>
	 <customerReference></customerReference>
	 <classificationCode>OTROS</classificationCode>
	 <taxReference></taxReference>
	 <countryCode>ES</countryCode>
	 <userCode1></userCode1>
	 <userCode2></userCode2>
	 <userCode3></userCode3>
	 <userCode4></userCode4>
	 <userCode5></userCode5>
	 <userCode6></userCode6>
	 <userCode7></userCode7>
	 <userCode8></userCode8>
	 <userCode9></userCode9>
	 <userCode10></userCode10>
	 <paymentCondition>TRF00</paymentCondition>
	 <retencion></retencion>
	 <bankId></bankId>
	 <bicCode></bicCode>
	 <bankName></bankName>
	 <paymentDay1>0</paymentDay1>
	 <paymentDay2>0</paymentDay2>
	 <paymentDay3>0</paymentDay3>
	 <settlementDiscount>0.0</settlementDiscount>
	 <holidayStartDay>0</holidayStartDay>
	 <holidayStartMonth>0</holidayStartMonth>
	 <holidayEndDay>0</holidayEndDay>
	 <holidayEndMonth>0</holidayEndMonth>
	</customer>';
	$response = sendXmlOverPost($addCustomer, $xml);
	$response_xml = new SimpleXMLElement($response);
	// $userdata = [	
	// 	'ID' => $user_id,
	// 	'customercode' => $newCustomerCode
	// ];
	// wp_update_user( $userdata );

	global $wpdb;
	$wpdb->update(
		'wp_users',
		[
			'api_id' => $newCustomerCode[0]
		],
		[
			'ID' => $user_id
		]
	);
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

add_action( 'woocommerce_payment_complete', 'so_payment_complete' );
function so_payment_complete( $order_id ){
	$url_base = "http://81.42.209.224:8080/optwebsvcs";
	$url_db = "?db=pruebas";
	
    $order = wc_get_order( $order_id );
	foreach ( $order->get_items() as $item ){
	}
	$producto = $order->get_product_from_item( $item );

    $user = $order->get_user();
	//todo obtener direccion
	$id_direccion_usuario = 1;

	global $wpdb;
	$api_id = $wpdb->get_results("SELECT api_id FROM wp_users WHERE ID = ".$user->ID);
	$customercode = $api_id[0]->api_id;
	
	$url_funcion = "/customer/addCustomerAddress";
	// $url_funcion = "/customer/updateCustomerAddress";
	$url = $url_base.$url_funcion.$url_db;
	$xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<customerAddress>
	 <customerCode>'.$customercode.'</customerCode>
	 <name>'.$order->get_billing_first_name().'</name>
	 <addressLine1>'.$order->get_billing_address_1().'</addressLine1>
	 <addressLine2>'.$order->get_billing_address_2().'</addressLine2>
	 <addressLine3>'.$order->get_billing_city().'</addressLine3>
	 <addressLine4></addressLine4>
	 <postcode>'.$order->get_billing_postcode().'</postcode>
	 <contact>'.$order->get_billing_phone().'</contact>
	 <email>'.$order->get_billing_email().'</email>
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
	if ($item->get_meta('ywapo-addon-7-0') && $item->get_meta('ywapo-addon-7-0')!="NULL") {
	 $encuadernacion = $item->get_meta('ywapo-addon-7-0');
	}

	if ($item->get_meta('ywapo-addon-7-1') && $item->get_meta('ywapo-addon-7-1')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-1');
	}

	if ($item->get_meta('ywapo-addon-7-2') && $item->get_meta('ywapo-addon-7-2')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-2');
	}

	if ($item->get_meta('ywapo-addon-7-3') && $item->get_meta('ywapo-addon-7-3')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-3');
	}

	if ($item->get_meta('ywapo-addon-7-4') && $item->get_meta('ywapo-addon-7-4')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-4');
	}

	if ($item->get_meta('ywapo-addon-7-5') && $item->get_meta('ywapo-addon-7-5')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-5');
	}

	if ($item->get_meta('ywapo-addon-7-6') && $item->get_meta('ywapo-addon-7-6')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-6');
	}

	if ($item->get_meta('ywapo-addon-7-7') && $item->get_meta('ywapo-addon-7-7')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-7');
	}

	if ($item->get_meta('ywapo-addon-7-8') && $item->get_meta('ywapo-addon-7-8')!="NULL") {
		$encuadernacion = $item->get_meta('ywapo-addon-7-8');
	}
	
	$tipoImpresionInterior = $item->get_meta('ywapo-addon-30-0');
	$codTipoPedido = getCodTipoPedido($encuadernacion, $tipoImpresionInterior);
		

	//var_dump($item);

	$addressNumber = (array)$response_xml->addressNumber;
	$addressNumber = $addressNumber[0];
	$url_funcion = "/enqbuilder";
	$url = $url_base.$url_funcion.$url_db;
	$xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<enquiry>
		<jobTemplateCode>PL_STD_PEDIDO</jobTemplateCode>
		<customerCode>'.$customercode.'</customerCode>
		<addressNumber>'.$addressNumber.'</addressNumber>
		<customerRef>'.$user->ID.'</customerRef>
		<contactName>'.$order->get_billing_first_name().'</contactName>
		<telephone>'.$order->get_billing_phone().'</telephone>
		<emailAddress>'.$order->get_billing_email().'</emailAddress>
		<repCode>FELIPE</repCode>
		<origCode>WEB</origCode>
		<currencyCode></currencyCode>
		<dueAt></dueAt>
		<prevEnqNumber>0</prevEnqNumber>
		<cancelPrevious>false</cancelPrevious>
		<jobVariable>
			<name>ep_fecha_entrega</name>
			<type>datetime</type>
			<value>'.date("Y-m-d").'T'.date("H:i").'</value>
		</jobVariable>
		<jobVariable>
			<name>ep_tipo_pedido</name>
			<type>string</type>
			<value>'.$codTipoPedido.'</value>
		</jobVariable>
		<jobVariable>
			<name>ep_titulo</name>
			<type>string</type>
			<value>'.$item->get_meta('ywapo-addon-2-0').'</value>
		</jobVariable>';
		if ($item->get_meta('ywapo-addon-9-0')) {
		$xml .='	
		<jobVariable>
			<name>ep_isbn</name>
			<type>string</type>
			<value>'.$item->get_meta('ywapo-addon-9-0').'</value>
		</jobVariable>
        <jobVariable>
        <name>ep_imprimirhr</name>
        <type>boolean</type>
        <value>1</value>
        </jobVariable>';
		}
		$xml .='<line>
			<productCode>GENERICO</productCode>
			<description>'.$item->get_meta('ywapo-addon-2-0').'</description>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-15-0') && $item->get_meta('ywapo-addon-15-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_elem</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-16-0') && $item->get_meta('ywapo-addon-16-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>3e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-20-0') && $item->get_meta('ywapo-addon-20-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>5e_elem</name>
					<type>integer</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-21-0') && $item->get_meta('ywapo-addon-21-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>6e_tipo</name>
					<type>string</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-22-0') && $item->get_meta('ywapo-addon-22-0')!="NULL") {
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
						<value>'.$e_ancho.'</value>
					</productVariable>';
					$xml .= '
					<productVariable>
						<name>e_alto</name>
						<type>integer</type>
						<value>'.$e_alto.'</value>
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
			if ($item->get_meta('ywapo-addon-15-0') && $item->get_meta('ywapo-addon-15-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-15-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-30-0') && $item->get_meta('ywapo-addon-30-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-30-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-30-1') && $item->get_meta('ywapo-addon-30-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-30-1').'</value>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-977-0') && $item->get_meta('ywapo-addon-977-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_paginas</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-977-0').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-970-0') && $item->get_meta('ywapo-addon-970-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-34-0') && $item->get_meta('ywapo-addon-34-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>0e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-16-0') && $item->get_meta('ywapo-addon-16-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-16-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-36-0') && $item->get_meta('ywapo-addon-36-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-36-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-36-1') && $item->get_meta('ywapo-addon-36-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-36-1').'</value>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-978-0') && $item->get_meta('ywapo-addon-978-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_paginas</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-978-0').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-971-1') && $item->get_meta('ywapo-addon-971-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-40-0') && $item->get_meta('ywapo-addon-40-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>1e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-17-0').'</value>
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
						<value>'.$meta_value.'</value>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-26-0') && $item->get_meta('ywapo-addon-26-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_solapas</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-26-0').'</value>
				</productVariable>';
			}
			for ($i = 0; $i <= 6; $i++) {
				$meta_key = 'ywapo-addon-5-' . $i;
				$meta_value = $item->get_meta($meta_key);
			
				if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-17-0')!="NULL") {
					$val = explode("/", $meta_value);
					$e_ancho = trim($val[0]);
					$e_alto = trim($val[1]);
			
                    if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0')!="NULL") {
					$xml .= '
					<productVariable>
						<name>2e_ancho</name>
						<type>integer</type>
						<value>'.$e_ancho.'</value>
					</productVariable>';
					$xml .= '
					<productVariable>
						<name>2e_alto</name>
						<type>integer</type>
						<value>'.$e_alto.'</value>
					</productVariable>';
                    }
				}
			}
			if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-17-0')!="NULL") {
				$e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
				$e_alto = trim($item->get_meta('ywapo-addon-975-1'));
			

                if ($item->get_meta('ywapo-addon-17-0') && $item->get_meta('ywapo-addon-17-0')!="NULL") {
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
            if ($item->get_meta('ywapo-addon-972-0') && $item->get_meta('ywapo-addon-972-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_ancho_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-972-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-972-1') && $item->get_meta('ywapo-addon-972-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_alto_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-972-1').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-27-0') && $item->get_meta('ywapo-addon-27-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-29-0') && $item->get_meta('ywapo-addon-29-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('yith-wapo-option-973-0') && $item->get_meta('ywapo-addon-973-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-79-0') && $item->get_meta('ywapo-addon-79-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_esta</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-79-1') && $item->get_meta('ywapo-addon-79-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_troq</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-79-2') && $item->get_meta('ywapo-addon-79-2')!="NULL") {
				$xml .= '
				<productVariable>
					<name>2e_golp</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>3e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-18-0').'</value>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-44-0') && $item->get_meta('ywapo-addon-44-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-44-1') && $item->get_meta('ywapo-addon-44-1')!="NULL") {
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
			
				if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-18-0')!="NULL") {
					$val = explode("/", $meta_value);
					$e_ancho = trim($val[0]);
					$e_alto = trim($val[1]);
			
                    if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0')!="NULL") {
					$xml .= '
					<productVariable>
						<name>3e_ancho</name>
						<type>integer</type>
						<value>'.$e_ancho.'</value>
					</productVariable>';
					$xml .= '
					<productVariable>
						<name>3e_alto</name>
						<type>integer</type>
						<value>'.$e_alto.'</value>
					</productVariable>';
                    }
				}
			}
			if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-18-0')!="NULL") {
				$e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
				$e_alto = trim($item->get_meta('ywapo-addon-975-1'));
			
				$e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
				$e_alto = preg_replace('/[^0-9]/', '', $e_alto);
			
                if ($item->get_meta('ywapo-addon-18-0') && $item->get_meta('ywapo-addon-18-0')!="NULL") {
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
            if ($item->get_meta('ywapo-addon-80-0') && $item->get_meta('ywapo-addon-80-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>3e_ancho_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-80-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-80-1') && $item->get_meta('ywapo-addon-80-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>3e_alto_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-80-1').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-19-0').'</value>
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
						<value>'.$meta_value.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-979-0') && $item->get_meta('ywapo-addon-979-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_solapas</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-979-0').'</value>
				</productVariable>';
			}
            for ($i = 0; $i <= 6; $i++) {
				$meta_key = 'ywapo-addon-5-' . $i;
				$meta_value = $item->get_meta($meta_key);

				if ($meta_value && $meta_value != "NULL") {
					$val = explode("/", $meta_value);
					$e_ancho = trim($val[0]);
					$e_alto = trim($val[1]);
                    
                    if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0')!="NULL") {
					$xml .= '
					<productVariable>
						<name>4e_ancho</name>
						<type>integer</type>
						<value>'.$e_ancho.'</value>
					</productVariable>';
					$xml .= '
					<productVariable>
						<name>4e_alto</name>
						<type>integer</type>
						<value>'.$e_alto.'</value>
					</productVariable>';
                    }
				}
			}
			if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL") {
				$e_ancho = trim($item->get_meta('ywapo-addon-975-0'));
				$e_alto = trim($item->get_meta('ywapo-addon-975-1'));
			
				$e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
				$e_alto = preg_replace('/[^0-9]/', '', $e_alto);
			
                if ($item->get_meta('ywapo-addon-19-0') && $item->get_meta('ywapo-addon-19-0')!="NULL") {
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
			if ($item->get_meta('ywapo-addon-49-0') && $item->get_meta('ywapo-addon-49-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_ancho_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-49-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-49-1') && $item->get_meta('ywapo-addon-49-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_alto_ab</name>
					<type>decimal</type>
					<value>'.$item->get_meta('ywapo-addon-49-1').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-51-0') && $item->get_meta('ywapo-addon-51-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-52-0') && $item->get_meta('ywapo-addon-52-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-82-0') && $item->get_meta('ywapo-addon-82-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_esta</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-82-1') && $item->get_meta('ywapo-addon-82-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_troq</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-82-2') && $item->get_meta('ywapo-addon-82-2')!="NULL") {
				$xml .= '
				<productVariable>
					<name>4e_golp</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-20-0') && $item->get_meta('ywapo-addon-20-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>5e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-20-0').'</value>
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
						<value>'.$meta_value.'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-56-0') && $item->get_meta('ywapo-addon-56-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>5e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-21-0') && $item->get_meta('ywapo-addon-21-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>6e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-21-0').'</value>
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
						<value>'.$meta_value.'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-63-0') && $item->get_meta('ywapo-addon-63-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>6e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-63-1') && $item->get_meta('ywapo-addon-63-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>6e_plast_2c</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-22-0') && $item->get_meta('ywapo-addon-22-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_tipo</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-22-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-68-0') && $item->get_meta('ywapo-addon-68-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-68-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-68-1') && $item->get_meta('ywapo-addon-68-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_tipo_imp</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-68-1').'</value>
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
						<value>'.$meta_value.'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-66-0') && $item->get_meta('ywapo-addon-66-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_sop_cliente</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-974-0') && $item->get_meta('ywapo-addon-974-0')!="NULL") {
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
            for ($i = 0; $i <= 6; $i++) {
				$meta_key = 'ywapo-addon-5-' . $i;
				$meta_value = $item->get_meta($meta_key);
			
				if ($meta_value && $meta_value != "NULL" && $item->get_meta('ywapo-addon-22-0')!="NULL") {
					$val = explode("/", $meta_value);
					$e_ancho = trim($val[0]);
					$e_alto = trim($val[1]);
			
					$xml .= '
					<productVariable>
						<name>7e_ancho</name>
						<type>integer</type>
						<value>'.$e_ancho.'</value>
					</productVariable>';
					$xml .= '
					<productVariable>
						<name>7e_alto</name>
						<type>integer</type>
						<value>'.$e_alto.'</value>
					</productVariable>';
				}
			}
			if ($item->get_meta('ywapo-addon-5-7') && $item->get_meta('ywapo-addon-5-7') != "NULL" && $item->get_meta('ywapo-addon-22-0')!="NULL") {
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
			if ($item->get_meta('ywapo-addon-59-0') && $item->get_meta('ywapo-addon-59-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_ancho_ab</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-59-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-59-1') && $item->get_meta('ywapo-addon-59-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>7e_alto_ab</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-59-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-84-0') && $item->get_meta('ywapo-addon-84-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_wr_color_otro</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-84-0').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-86-0') && $item->get_meta('ywapo-addon-86-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_es_color_otro</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-86-0').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
            if ($item->get_meta('ywapo-addon-70-0') && $item->get_meta('ywapo-addon-70-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_td_cab</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-87-0') && $item->get_meta('ywapo-addon-87-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_tc_cab_color</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-87-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-70-1') && $item->get_meta('ywapo-addon-70-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_td_csep</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-88-0') && $item->get_meta('ywapo-addon-88-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_tc_csep_color</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-88-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-70-2') && $item->get_meta('ywapo-addon-70-2')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_td_csep2</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-89-0') && $item->get_meta('ywapo-addon-89-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_tc_csep2_color</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-89-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-70-3') && $item->get_meta('ywapo-addon-70-3')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_td_csep3</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-90-0') && $item->get_meta('ywapo-addon-90-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_encu_tc_csep3_color</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-90-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-73-0') && $item->get_meta('ywapo-addon-73-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_emp_retrcol</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-74-0') && $item->get_meta('ywapo-addon-74-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_emp_retrcol_ctd</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-74-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-73-1') && $item->get_meta('ywapo-addon-73-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_emp_retruni</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-73-2') && $item->get_meta('ywapo-addon-73-2')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_emp_ensobrado</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-96-0') && $item->get_meta('ywapo-addon-96-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_extracostes</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-96-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-96-1') && $item->get_meta('ywapo-addon-96-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_comentario_cli</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-96-1').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-75-0') && $item->get_meta('ywapo-addon-75-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_emp_encaj</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-75-1') && $item->get_meta('ywapo-addon-75-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent</name>
					<type>boolean</type>
					<value>1</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-982-0') && $item->get_meta('ywapo-addon-982-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_04_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-982-0').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-76-0') && $item->get_meta('ywapo-addon-76-0')!="NULL") {
                
                $xml .= '
				<productVariable>
					<name>e_ent_00_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-76-0').'</value>
				</productVariable>';
			}		
			if ($item->get_meta('ywapo-addon-76-1') && $item->get_meta('ywapo-addon-76-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_00_cnt_01</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-76-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}

			if ($item->get_meta('ywapo-addon-92-0') && $item->get_meta('ywapo-addon-92-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_01_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-92-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-92-1') && $item->get_meta('ywapo-addon-92-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_01_cnt_01</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-92-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-93-0') && $item->get_meta('ywapo-addon-93-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_02_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-93-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-93-1') && $item->get_meta('ywapo-addon-93-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_02_cnt_01</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-93-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-94-0') && $item->get_meta('ywapo-addon-94-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_03_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-94-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-94-1') && $item->get_meta('ywapo-addon-94-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_03_cnt_01</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-94-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-95-0') && $item->get_meta('ywapo-addon-95-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_04_dir</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-95-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-95-1') && $item->get_meta('ywapo-addon-95-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_ent_04_cnt_01</name>
					<type>integer</type>
					<value>'.$item->get_meta('ywapo-addon-95-1').'</value>
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
					<value>'.$meta_value.'</value>
				</productVariable>';
                }
			}
			if ($item->get_meta('ywapo-addon-3-0') && $item->get_meta('ywapo-addon-3-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-3-0').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-3-1') && $item->get_meta('ywapo-addon-3-1')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-3-1').'</value>
				</productVariable>';
			}
            if ($item->get_meta('ywapo-addon-3-2') && $item->get_meta('ywapo-addon-3-2')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_pruebas</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-3-2').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-8-0') && $item->get_meta('ywapo-addon-8-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>e_observaciones_cli</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-8-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-1-0' ) && $item->get_meta('ywapo-addon-1-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>orientacion</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-1-0').'</value>
				</productVariable>';
			}
			if ($item->get_meta('ywapo-addon-1-1' ) && $item->get_meta('ywapo-addon-1-0')!="NULL") {
				$xml .= '
				<productVariable>
					<name>orientacion</name>
					<type>string</type>
					<value>'.$item->get_meta('ywapo-addon-1-0').'</value>
				</productVariable>';
			}
	
			$cantidad = str_replace("Cantidad 1: ", "", $item->get_meta('ywapo-addon-980-0'));
			$xml .= '
			<quantity>'.$cantidad.'</quantity>
		</line>
	</enquiry>';
	
	//var_dump($xml);
	//die();


	$url2_funcion = "/enqPrice";
	$url2 = $url_base.$url2_funcion.$url_db;
	
	$response = sendXmlOverPost($url2, $xml);
	$response_xml = new SimpleXMLElement($response);
	
	//var_dump($response);
	//die();
	
	$lineQtyPrice = (array)$response_xml->lineQtyPrice;
	$total = round($lineQtyPrice["price"], 2);
	
	$order->set_total( $total );
	$order->save();

	
	$response = sendXmlOverPost($url, $xml);
	$response_xml = new SimpleXMLElement($response);
	
	$success = (array)$response_xml->success;
	if ($success[0] == "false") {
		$error = (array)$response_xml->error;
		echo'
		{
			"result": "failure",
			"messages": "\n<ul class=\"woocommerce-error\" role=\"alert\">\n\t\t\t<li data-id=\"billing_first_name\">\n\t\t\t'.$error[0].'\t\t<\/li>\n\t<\/ul>\n",
			"refresh": false,
			"reload": false
		}';
		//var_dump($xml);
		exit();
	}else{
		$success = (array)$response_xml->success;
		echo'
		{
			"result": "true",
			"messages": "\n<ul class=\"woocommerce-error\" role=\"alert\">\n\t\t\t<li data-id=\"billing_first_name\" >\n\t\t\t'.$response.'\t\t<\/li>\n\t<\/ul>\n",
			"refresh": false,
			"reload": false,
			"redirect_url": "https://masquelibrosdigital.com/gracias-por-comprar/"
		}';

	}
	


	$url_funcion = "/enquiry/markSuccessful";
	$url = $url_base.$url_funcion.$url_db;
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

//envio ajax para traer el precio en cada cambio
add_action('wp_footer', 'tabla_precios');
function tabla_precios() {
	if (is_product()) {
	?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$("form.cart input").on("change", function() {
					var formdata = $('form.cart').serializeArray();
					var formdataarr = [];
					$.each(formdata, function(i, fd) {
						formdataarr[fd.name] = fd.value;
					});

					$.ajax({
						url: ajax_params.ajax_url + '?action=tabla_precios_controller&nonce=' + ajax_params.nonce,
						type: 'POST',
						data: formdata,
						dataType: 'json',
						success: function(response) {
							if (response["result"] == 'success' && response["cantidad"] && response["total"] && response["ppu"]) {
								var tabla = '<table><tr><td>Cantidad</td><td>Precio total</td><td>Precio por unidad</td></tr><tr><td>'+response["cantidad"]+'</td><td>'+response["total"]+'</td><td>'+response["ppu"]+'</td></tr></table>';
							} else {
								tabla = '';
							}
							$("#generar-presupuesto").html(tabla);
						},
						error: function(xhr, status, error) {
							$("#generar-presupuesto").html('');
						}
					});
				});
			});
		</script>
	<?php
	}
}


function enqueue_custom_ajax_script() {
    wp_enqueue_script('custom-ajax-script', get_template_directory_uri() . '/js/custom-ajax.js', array('jquery'), null, true);
    
    wp_localize_script('custom-ajax-script', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tabla_precios_controller_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_ajax_script');

function handle_tabla_precios_controller() {
	// Check nonce for security
    check_ajax_referer('tabla_precios_controller_nonce', 'nonce');

	$url_base = "http://81.42.209.224:8080/optwebsvcs";
	$url_db = "?db=pruebas";
	$url_funcion = "/enqPrice";
	$url = $url_base.$url_funcion.$url_db;
	// $info = yith_wapo_get_option_info( 76, 2);
	// var_dump($info);die();
	foreach ($_POST["yith_wapo"] as $key => $arr) {
		foreach ($arr as $key => $value) {
			// if ($key == "7-0"){
			// 	var_dump($value);die();
			// }
			// var_dump($_POST["yith_wapo"]);die();
			$yithdata = explode("-",$key);
			$info = yith_wapo_get_option_info( $yithdata[0], $yithdata[1]);
			if ($info['label'] && $info['label_in_cart_opt'] != "NULL") {
				if ($info['label'] == $value) {
					$data[$key] = $info["label_in_cart_opt"];
				} else {
					$data[$key] = $value;
				}
			} else {
				$data[$key] = $value;
			}
		}
	}

    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
	<enquiry>
		<jobTemplateCode>PL_STD_PEDIDO</jobTemplateCode>
		<customerCode>PRUEBAS</customerCode>
		<addressNumber>1</addressNumber>
		<repCode>FELIPE</repCode>
		<origCode>WEB</origCode>
		<currencyCode></currencyCode>
		<dueAt></dueAt>
		<prevEnqNumber>0</prevEnqNumber>
		<cancelPrevious>false</cancelPrevious>
		<jobVariable>
			<name>ep_fecha_entrega</name>
			<type>datetime</type>
			<value>'.date("Y-m-d").'T'.date("H:i").'</value>
		</jobVariable>
		<jobVariable>
			<name>ep_tipo_pedido</name>
			<type>string</type>
			<value>'.$codTipoPedido.'</value>
		</jobVariable>
		<jobVariable>
			<name>ep_titulo</name>
			<type>string</type>
			<value>'.$data["2-0"].'</value>
		</jobVariable>';

    if (!empty($data['9-0'])) {
        $xml .= '	
        <jobVariable>
            <name>ep_isbn</name>
            <type>string</type>
            <value>'.$data['9-0'].'</value>
        </jobVariable>
        <jobVariable>
            <name>ep_imprimirhr</name>
            <type>boolean</type>
            <value>1</value>
        </jobVariable>';
    }

    $xml .= '<line>
        <productCode>GENERICO</productCode>
        <description>'.$data["2-0"].'</description>
        <includeInQuote>true</includeInQuote>
        <productVariable>
            <name>e_elem_mod</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';


    for ($i = 0; $i <= 8; $i++) {
        $meta_key = '7-' . $i;
        $meta_value = $data[$meta_key] ?? null;

        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>e_encu</name>
                <type>string</type>
                <value>'.$meta_value.'</value>
            </productVariable>';
        }
    }


    if (!empty($data['15-0']) && $data['15-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_elem</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['16-0']) && $data['16-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_elem</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['17-0']) && $data['17-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_elem</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['18-0']) && $data['18-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_elem</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['19-0']) && $data['19-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_elem</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['20-0']) && $data['20-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>5e_elem</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['21-0']) && $data['21-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>6e_tipo</name>
            <type>string</type>
            <value>1</value>
        </productVariable>';
    }

    if (!empty($data['22-0']) && $data['22-0'] != "NULL") {
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
        $meta_key = '5-' . $i;
        $meta_value = $data[$meta_key] ?? null;

        if (!empty($meta_value) && $meta_value != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);

            $xml .= '
            <productVariable>
                <name>e_ancho</name>
                <type>integer</type>
                <value>'.$e_ancho.'</value>
            </productVariable>';
            
            $xml .= '
            <productVariable>
                <name>e_alto</name>
                <type>integer</type>
                <value>'.$e_alto.'</value>
            </productVariable>';
        }
    }

    if (!empty($data['5-7']) && $data['5-7'] != "NULL") {
        $e_ancho = trim($data['975-0']);
        $e_alto = trim($data['975-1']);
    
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
    
    if (!empty($data['15-0']) && $data['15-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_tipo</name>
            <type>string</type>
            <value>' . $data['15-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['30-0']) && $data['30-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['30-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['30-1']) && $data['30-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['30-1'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 15; $i++) {
        $meta_key = '31-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '89-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '32-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>0e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['977-0']) && $data['977-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_paginas</name>
            <type>integer</type>
            <value>' . $data['977-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '33-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>0e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['970-0']) && $data['970-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['34-0']) && $data['34-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>0e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['16-0']) && $data['16-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_tipo</name>
            <type>string</type>
            <value>' . $data['16-0'] . '</value>
        </productVariable>';
    }
    if (!empty($data['36-0']) && $data['36-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['36-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['36-1']) && $data['36-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['36-1'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '37-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '90-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '38-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>1e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['978-0']) && $data['978-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_paginas</name>
            <type>integer</type>
            <value>' . $data['978-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '41-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>1e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['971-1']) && $data['971-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['40-0']) && $data['40-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>1e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['17-0']) && $data['17-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_tipo</name>
            <type>string</type>
            <value>' . $data['17-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '24-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '1001-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '25-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>2e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = '1002-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>2e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['26-0']) && $data['26-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_solapas</name>
            <type>decimal</type>
            <value>' . $data['26-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = '5-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL" && !empty($data['17-0']) && $data['17-0'] != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);
    
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
    
    if (!empty($data['5-7']) && $data['5-7'] != "NULL" && !empty($data['17-0']) && $data['17-0'] != "NULL") {
        $e_ancho = trim($data['975-0']);
        $e_alto = trim($data['975-1']);
    
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
    
    if (!empty($data['972-0']) && $data['972-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_ancho_ab</name>
            <type>decimal</type>
            <value>' . $data['972-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['972-1']) && $data['972-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_alto_ab</name>
            <type>decimal</type>
            <value>' . $data['972-1'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['27-0']) && $data['27-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '28-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>2e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['29-0']) && $data['29-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['yith-wapo-option-973-0']) && $data['973-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_barn</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 15; $i++) {
        $meta_key = '78-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>2e_esta_color</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['79-0']) && $data['79-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_esta</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['79-1']) && $data['79-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_troq</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['79-2']) && $data['79-2'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>2e_golp</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['18-0']) && $data['18-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_tipo</name>
            <type>string</type>
            <value>' . $data['18-0'] . '</value>
        </productVariable>';
    }
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '42-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '43-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>3e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['44-0']) && $data['44-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '45-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>3e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['44-1']) && $data['44-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = '5-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL" && !empty($data['18-0']) && $data['18-0'] != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);
    
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
    
    if (!empty($data['5-7']) && $data['5-7'] != "NULL" && !empty($data['18-0']) && $data['18-0'] != "NULL") {
        $e_ancho = trim($data['975-0']);
        $e_alto = trim($data['975-1']);
    
        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);
    
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
    
    if (!empty($data['80-0']) && $data['80-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_ancho_ab</name>
            <type>decimal</type>
            <value>' . $data['80-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['80-1']) && $data['80-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>3e_alto_ab</name>
            <type>decimal</type>
            <value>' . $data['80-1'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['19-0']) && $data['19-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_tipo</name>
            <type>string</type>
            <value>' . $data['19-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '47-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '48-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>4e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    if (!empty($data['979-0']) && $data['979-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_solapas</name>
            <type>decimal</type>
            <value>' . $data['979-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = '5-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL" && !empty($data['19-0']) && $data['19-0'] != "NULL") {
            $val = explode("/", $meta_value);
            $e_ancho = trim($val[0]);
            $e_alto = trim($val[1]);
    
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
    
    if (!empty($data['5-7']) && $data['5-7'] != "NULL" && !empty($data['19-0']) && $data['19-0'] != "NULL") {
        $e_ancho = trim($data['975-0']);
        $e_alto = trim($data['975-1']);
    
        $e_ancho = preg_replace('/[^0-9]/', '', $e_ancho);
        $e_alto = preg_replace('/[^0-9]/', '', $e_alto);
    
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
    
    if (!empty($data['49-0']) && $data['49-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_ancho_ab</name>
            <type>decimal</type>
            <value>' . $data['49-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['49-1']) && $data['49-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_alto_ab</name>
            <type>decimal</type>
            <value>' . $data['49-1'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['51-0']) && $data['51-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '50-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>4e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['52-0']) && $data['52-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_barn</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '81-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>4e_esta_color</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['82-0']) && $data['82-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_esta</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['82-1']) && $data['82-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_troq</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['82-2']) && $data['82-2'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>4e_golp</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['20-0']) && $data['20-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>5e_tipo</name>
            <type>string</type>
            <value>' . $data['20-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '53-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '54-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>5e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '57-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>5e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['56-0']) && $data['56-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>5e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['21-0']) && $data['21-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>6e_tipo</name>
            <type>string</type>
            <value>' . $data['21-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '60-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '61-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>6e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '62-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>6e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['63-0']) && $data['63-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>6e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['63-1']) && $data['63-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>6e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['22-0']) && $data['22-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_tipo</name>
            <type>string</type>
            <value>' . $data['22-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['68-0']) && $data['68-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['68-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['68-1']) && $data['68-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_tipo_imp</name>
            <type>string</type>
            <value>' . $data['68-1'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 25; $i++) {
        $meta_key = '67-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '99-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
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
        $meta_key = '64-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>7e_tintas</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '65-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>7e_plast</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['66-0']) && $data['66-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_sop_cliente</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    if (!empty($data['974-0']) && $data['974-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_plast_2c</name>
            <type>boolean</type>
            <value>1</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '58-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>7e_formapleg</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    for ($i = 0; $i <= 6; $i++) {
        $meta_key = '5-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL" && !empty($data['22-0']) && $data['22-0'] != "NULL") {
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
    
    if (!empty($data['5-7']) && $data['5-7'] != "NULL" && !empty($data['22-0']) && $data['22-0'] != "NULL") {
        $e_ancho = trim($data['975-0']);
        $e_alto = trim($data['975-1']);
    
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
    
    if (!empty($data['59-0']) && $data['59-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_ancho_ab</name>
            <type>integer</type>
            <value>' . $data['59-0'] . '</value>
        </productVariable>';
    }
    
    if (!empty($data['59-1']) && $data['59-1'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>7e_alto_ab</name>
            <type>integer</type>
            <value>' . $data['59-1'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '83-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>e_encu_wr_color</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['84-0']) && $data['84-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>e_encu_wr_color_otro</name>
            <type>string</type>
            <value>' . $data['84-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '85-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>e_encu_es_color</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    if (!empty($data['86-0']) && $data['86-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>e_encu_es_color_otro</name>
            <type>string</type>
            <value>' . $data['86-0'] . '</value>
        </productVariable>';
    }
    
    for ($i = 0; $i <= 10; $i++) {
        $meta_key = '69-' . $i;
        $meta_value = $data[$meta_key] ?? null;
    
        if (!empty($meta_value) && $meta_value != "NULL") {
            $xml .= '
            <productVariable>
                <name>e_encu_td_carton</name>
                <type>string</type>
                <value>' . $meta_value . '</value>
            </productVariable>';
        }
    }
    
    $boolean_fields = [
        '70-0' => 'e_encu_td_cab',
        '70-1' => 'e_encu_td_csep',
        '70-2' => 'e_encu_td_csep2',
        '70-3' => 'e_encu_td_csep3',
        '73-0' => 'e_emp_retrcol',
        '73-1' => 'e_emp_retruni',
        '73-2' => 'e_emp_ensobrado',
        '75-0' => 'e_emp_encaj',
        '75-1' => 'e_ent'
    ];
    
    foreach ($boolean_fields as $key => $name) {
        if (!empty($data[$key]) && $data[$key] != "NULL") {
            $xml .= '
            <productVariable>
                <name>' . $name . '</name>
                <type>boolean</type>
                <value>1</value>
            </productVariable>';
        }
    }
    
    $string_fields = [
        '87-0' => 'e_encu_tc_cab_color',
        '88-0' => 'e_encu_tc_csep_color', 
        '89-0' => 'e_encu_tc_csep2_color',
        '90-0' => 'e_encu_tc_csep3_color',
        '74-0' => 'e_emp_retrcol_ctd',
        '96-0' => 'e_extracostes',
        '96-1' => 'e_comentario_cli',
        '982-0' => 'e_ent_04_dir'
    ];
    
    foreach ($string_fields as $key => $name) {
        if (!empty($data[$key]) && $data[$key] != "NULL") {
            $xml .= '
            <productVariable>
                <name>' . $name . '</name>
                <type>string</type>
                <value>' . $data[$key] . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['982-0']) && $data['982-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>e_ent_04_cnt_01</name>
            <type>integer</type>
            <value>1</value>
        </productVariable>';
    }
    $zona_fields = [
        '983' => 'e_ent_04_zona',
        '984' => 'e_ent_00_zona',
        '985' => 'e_ent_01_zona',
        '986' => 'e_ent_02_zona',
        '987' => 'e_ent_03_zona',
        '988' => 'e_ent_04_zona'
    ];
    
    foreach ($zona_fields as $addon_id => $field_name) {
        for ($i = 0; $i <= 50; $i++) {
            $meta_key = '' . $addon_id . '-' . $i;
            $meta_value = $data[$meta_key] ?? null;
            
            if (!empty($meta_value) && $meta_value != "NULL") {
                $xml .= '
                <productVariable>
                    <name>' . $field_name . '</name>
                    <type>string</type>
                    <value>' . $meta_value . '</value>
                </productVariable>';
            }
        }
    }
    
    $delivery_fields = [
        '76' => ['dir' => 'e_ent_00_dir', 'cnt' => 'e_ent_00_cnt_01'],
        '92' => ['dir' => 'e_ent_01_dir', 'cnt' => 'e_ent_01_cnt_01'],
        '93' => ['dir' => 'e_ent_02_dir', 'cnt' => 'e_ent_02_cnt_01'],
        '94' => ['dir' => 'e_ent_03_dir', 'cnt' => 'e_ent_03_cnt_01'],
        '95' => ['dir' => 'e_ent_04_dir', 'cnt' => 'e_ent_04_cnt_01']
    ];
    
    foreach ($delivery_fields as $addon_id => $fields) {
        $dir_key = '' . $addon_id . '-0';
        $cnt_key = '' . $addon_id . '-1';
        
        if (!empty($data[$dir_key]) && $data[$dir_key] != "NULL") {
            $xml .= '
            <productVariable>
                <name>' . $fields['dir'] . '</name>
                <type>string</type>
                <value>' . $data[$dir_key] . '</value>
            </productVariable>';
        }
        
        if (!empty($data[$cnt_key]) && $data[$cnt_key] != "NULL") {
            $xml .= '
            <productVariable>
                <name>' . $fields['cnt'] . '</name>
                <type>integer</type>
                <value>' . $data[$cnt_key] . '</value>
            </productVariable>';
        }
    }
    
    if (!empty($data['3-0']) && $data['3-0'] != "NULL") {
        $xml .= '
        <productVariable>
            <name>e_pruebas</name>
            <type>string</type>
            <value>' . $data['3-0'] . '</value>
        </productVariable>';
    }
    $pruebas_fields = ['3-1', '3-2'];

foreach ($pruebas_fields as $field) {
    if (!empty($data[$field]) && $data[$field] != "NULL") {
        $xml .= '
        <productVariable>
            <name>e_pruebas</name>
            <type>string</type>
            <value>' . $data[$field] . '</value>
        </productVariable>';
    }
}

$string_fields = [
    '8-0' => 'e_observaciones_cli',
    '1-0' => 'orientacion',
    '1-1' => 'orientacion'
];

foreach ($string_fields as $key => $name) {
    if (!empty($data[$key]) && $data[$key] != "NULL") {
        $xml .= '
        <productVariable>
            <name>' . $name . '</name>
            <type>string</type>
            <value>' . $data[$key] . '</value>
        </productVariable>';
    }
}

$cantidad = str_replace("Cantidad 1: ", "", $data['980-0'] ?? '');
$xml .= '
    <quantity>' . $cantidad . '</quantity>
</line>
</enquiry>';

	

	//var_dump($data);
	//var_dump($xml);
	$response = sendXmlOverPost($url, $xml);
	$response_xml = new SimpleXMLElement($response);
	//var_dump($response_xml);
	
	$lineQtyPrice = (array)$response_xml->lineQtyPrice;
	// if (!$lineQtyPrice["quantity"]) {
	// 	// $error = (array)$response_xml->error;
	// 	// echo'
	// 	// {
	// 	// 	"result": "error",
	// 	// 	"send": "'.$xml.'",
	// 	// 	"response": "'.$response.'"
	// 	// }';
	// 	exit();
	// }
	if ($lineQtyPrice["quantity"]){
		$total = round($lineQtyPrice["price"], 2);
		$ppu = round($lineQtyPrice["price"] / $lineQtyPrice["quantity"], 2);
		$lomo = $lineQtyPrice["variable"][4] ? $lineQtyPrice["variable"][4] : 0;
		$peso = $lineQtyPrice["variable"][6] ? $lineQtyPrice["variable"][6] : 0;
		echo'
		{
			"result": "success",
			"cantidad": '.$lineQtyPrice["quantity"].',
			"total": '.$total.',
			"ppu": '.$ppu.',
			"lomo": '.$lomo.',
			"peso": "'.$peso.'"
		}';
		exit();
	}

    wp_die(); // This is required to terminate immediately and return a proper response
}
add_action('wp_ajax_tabla_precios_controller', 'handle_tabla_precios_controller');
add_action('wp_ajax_nopriv_tabla_precios_controller', 'handle_tabla_precios_controller');

function redirect_non_logged_users_to_login() {
    if (!is_user_logged_in() && !is_admin()) {
        $login_page_url = 'https://masquelibrosdigital.com/iniciar-sesion/';
        wp_redirect($login_page_url);
        exit;
    }
}

add_action('woocommerce_before_shop_loop', 'redirect_non_logged_users_to_login');
add_action('woocommerce_before_single_product', 'redirect_non_logged_users_to_login');


add_action( 'woocommerce_thankyou', 'redirigir_a_pagina_personalizada' );
function redirigir_a_pagina_personalizada( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( $order->is_paid() ) {

        $url = 'https://masquelibrosdigital.com/gracias-por-comprar/';
        wp_safe_redirect( $url );
        exit;
    }
}


