<?php

include_once ETCHY_CORE_PLUGINS_PATH . '/woocommerce/woocommerce.php';

if ( ! function_exists( 'etchy_core_include_product_tax_fields' ) ) {
	function etchy_core_include_product_tax_fields() {
		include_once ETCHY_CORE_PLUGINS_PATH . '/woocommerce/dashboard/taxonomy/taxonomy-options.php';
	}

	add_action( 'etchy_core_action_include_cpt_tax_fields', 'etchy_core_include_product_tax_fields' );
}