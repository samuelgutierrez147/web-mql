<?php

if ( ! function_exists( 'etchy_core_add_product_categories_list_variation_info_on_image' ) ) {
	function etchy_core_add_product_categories_list_variation_info_on_image( $variations ) {
		$variations['info-on-image'] = esc_html__( 'Info On Image', 'etchy-core' );

		return $variations;
	}

	add_filter( 'etchy_core_filter_product_categories_list_layouts', 'etchy_core_add_product_categories_list_variation_info_on_image' );
}