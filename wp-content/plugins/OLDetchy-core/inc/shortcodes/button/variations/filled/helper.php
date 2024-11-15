<?php

if ( ! function_exists( 'etchy_core_add_button_variation_filled' ) ) {
	function etchy_core_add_button_variation_filled( $variations ) {
		
		$variations['filled'] = esc_html__( 'Filled', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_button_layouts', 'etchy_core_add_button_variation_filled' );
}
