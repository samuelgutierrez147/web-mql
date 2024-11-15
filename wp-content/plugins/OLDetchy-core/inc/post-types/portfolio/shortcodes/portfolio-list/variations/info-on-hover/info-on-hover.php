<?php

if ( ! function_exists( 'etchy_core_add_portfolio_list_variation_info_on_hover' ) ) {
	function etchy_core_add_portfolio_list_variation_info_on_hover( $variations ) {
		
		$variations['info-on-hover'] = esc_html__( 'Info On Hover', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_layouts', 'etchy_core_add_portfolio_list_variation_info_on_hover' );
}