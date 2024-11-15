<?php

if ( ! function_exists( 'etchy_core_add_portfolio_list_variation_info_bottom_left' ) ) {
	function etchy_core_add_portfolio_list_variation_info_bottom_left( $variations ) {
		
		$variations['info-bottom-left'] = esc_html__( 'Info Bottom Left', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_layouts', 'etchy_core_add_portfolio_list_variation_info_bottom_left' );
}