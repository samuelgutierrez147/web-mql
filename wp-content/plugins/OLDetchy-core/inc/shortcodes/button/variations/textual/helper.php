<?php

if ( ! function_exists( 'etchy_core_add_button_variation_textual' ) ) {
	function etchy_core_add_button_variation_textual( $variations ) {
		
		$variations['textual'] = esc_html__( 'Textual', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_button_layouts', 'etchy_core_add_button_variation_textual' );
}
