<?php

if ( ! function_exists( 'etchy_core_add_social_share_variation_text' ) ) {
	function etchy_core_add_social_share_variation_text( $variations ) {
		
		$variations['text'] = esc_html__( 'Text', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_social_share_layouts', 'etchy_core_add_social_share_variation_text' );
}
