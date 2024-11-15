<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_images_big' ) ) {
	function etchy_core_add_portfolio_single_variation_images_big( $variations ) {
		$variations['images-big'] = esc_html__( 'Images - Big', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_images_big' );
}

if ( ! function_exists( 'etchy_core_set_default_portfolio_single_variation_compact' ) ) {
	function etchy_core_set_default_portfolio_single_variation_compact() {
		return 'images-big';
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_default_value', 'etchy_core_set_default_portfolio_single_variation_compact' );
}