<?php

if ( ! function_exists( 'etchy_core_add_banner_variation_link_button' ) ) {
	function etchy_core_add_banner_variation_link_button( $variations ) {
		
		$variations['link-button'] = esc_html__( 'Link Button', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_banner_layouts', 'etchy_core_add_banner_variation_link_button' );
}
