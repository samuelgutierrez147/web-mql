<?php

if ( ! function_exists( 'etchy_core_add_icon_with_text_variation_before_content' ) ) {
	function etchy_core_add_icon_with_text_variation_before_content( $variations ) {
		
		$variations['before-content'] = esc_html__( 'Before Content', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_icon_with_text_layouts', 'etchy_core_add_icon_with_text_variation_before_content' );
}
