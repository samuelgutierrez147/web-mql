<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_images_small' ) ) {
	function etchy_core_add_portfolio_single_variation_images_small( $variations ) {
		
		$variations['images-small'] = esc_html__( 'Images - Small', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_images_small' );
}