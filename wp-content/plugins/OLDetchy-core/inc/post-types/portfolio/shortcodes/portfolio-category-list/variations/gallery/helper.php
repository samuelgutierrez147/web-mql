<?php

if ( ! function_exists( 'etchy_core_add_portfolio_category_list_variation_gallery' ) ) {
	function etchy_core_add_portfolio_category_list_variation_gallery( $variations ) {
		$variations['gallery'] = esc_html__( 'Gallery', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_category_list_layouts', 'etchy_core_add_portfolio_category_list_variation_gallery' );
}
