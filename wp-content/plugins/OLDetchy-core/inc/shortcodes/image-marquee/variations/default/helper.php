<?php

if ( ! function_exists( 'etchy_core_add_image_marquee_variation_default' ) ) {
	function etchy_core_add_image_marquee_variation_default( $variations ) {
		$variations['default'] = esc_html__( 'Default', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_image_marquee_layouts', 'etchy_core_add_image_marquee_variation_default' );
}
