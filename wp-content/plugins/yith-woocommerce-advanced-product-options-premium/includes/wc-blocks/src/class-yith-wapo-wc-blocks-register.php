<?php
/**
 * This class register the special meta for the cart
 *
 * @package YITH\Addons\Classes
 * @since 4.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema;

class YITH_WAPO_WC_Blocks_Register {

	public function __construct() {
		add_action( 'woocommerce_blocks_loaded', array( $this, 'register_endpoint' ) );
	}

	/**
	 * Register the endpoint
	 *
	 * @return void
	 */
	public function register_endpoint() {
		woocommerce_store_api_register_endpoint_data(
			array(
				'endpoint'        => CartItemSchema::IDENTIFIER,
				'namespace'       => 'yith_wapo_wc_block_manager',
				'data_callback'   => array( $this, 'my_data_callback' ),
				'schema_callback' => array( $this, 'my_schema_callback' ),
				'schema_type'     => ARRAY_A,
			)
		);
	}

	/**
	 * Return the data
	 *
	 * @return string[]
	 */
	public function my_data_callback( $cart_item ) {
		return array(
			'replace_image'   => wp_get_attachment_image_url( $cart_item['yith_wapo_product_img'] ) ?? '',
		);
	}

	/**
	 * The schema callback
	 *
	 * @return array[]
	 */
	public function my_schema_callback() {
		return array(
			'replace_image' => array(
				'description' => _x( 'The image URL to replace in the cart item thumbnail', 'The image URL to replace', 'yith-woocommerce-product-add-ons' ),
				'type'        => 'string',
				'readonly'    => true,
			),
		);
	}
}

new YITH_WAPO_WC_Blocks_Register();