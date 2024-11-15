<?php

if ( ! function_exists( 'etchy_core_add_image_with_text_variation_text_below' ) ) {
	function etchy_core_add_image_with_text_variation_text_below( $variations ) {
		
		$variations['text-below'] = esc_html__( 'Text Below', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_image_with_text_layouts', 'etchy_core_add_image_with_text_variation_text_below' );
}
