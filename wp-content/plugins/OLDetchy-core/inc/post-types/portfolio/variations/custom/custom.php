<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_custom' ) ) {
	function etchy_core_add_portfolio_single_variation_custom( $variations ) {
		$variations['custom'] = esc_html__( 'Custom', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_custom' );
}