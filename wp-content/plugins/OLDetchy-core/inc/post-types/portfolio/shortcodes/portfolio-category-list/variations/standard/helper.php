<?php

if ( ! function_exists( 'etchy_core_add_portfolio_category_list_variation_standard' ) ) {
	function etchy_core_add_portfolio_category_list_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_category_list_layouts', 'etchy_core_add_portfolio_category_list_variation_standard' );
}
