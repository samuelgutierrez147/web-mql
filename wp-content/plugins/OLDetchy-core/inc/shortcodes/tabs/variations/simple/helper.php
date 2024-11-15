<?php

if ( ! function_exists( 'etchy_core_add_tabs_variation_simple' ) ) {
	function etchy_core_add_tabs_variation_simple( $variations ) {
		
		$variations['simple'] = esc_html__( 'Simple', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_tabs_layouts', 'etchy_core_add_tabs_variation_simple' );
}
