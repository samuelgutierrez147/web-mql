<?php

if ( ! function_exists( 'etchy_core_add_custom_font_variation_simple' ) ) {
	function etchy_core_add_custom_font_variation_simple( $variations ) {
		
		$variations['simple'] = esc_html__( 'Simple', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_custom_font_layouts', 'etchy_core_add_custom_font_variation_simple' );
}
