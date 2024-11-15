<?php

if ( ! function_exists( 'etchy_core_add_stacked_images_variation_default' ) ) {
	function etchy_core_add_stacked_images_variation_default( $variations ) {
		$variations['default'] = esc_html__( 'Default', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_stacked_images_layouts', 'etchy_core_add_stacked_images_variation_default' );
}
