<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_gallery_big' ) ) {
	function etchy_core_add_portfolio_single_variation_gallery_big( $variations ) {
		$variations['gallery-big'] = esc_html__( 'Gallery - Big', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_gallery_big' );
}