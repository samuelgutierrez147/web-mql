<?php

if ( ! function_exists( 'etchy_core_add_button_variation_outlined' ) ) {
	function etchy_core_add_button_variation_outlined( $variations ) {
		
		$variations['outlined'] = esc_html__( 'Outlined', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_button_layouts', 'etchy_core_add_button_variation_outlined' );
}
