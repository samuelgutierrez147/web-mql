<?php

if ( ! function_exists( 'etchy_core_add_icon_with_text_variation_before_title' ) ) {
	function etchy_core_add_icon_with_text_variation_before_title( $variations ) {
		
		$variations['before-title'] = esc_html__( 'Before Title', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_icon_with_text_layouts', 'etchy_core_add_icon_with_text_variation_before_title' );
}
