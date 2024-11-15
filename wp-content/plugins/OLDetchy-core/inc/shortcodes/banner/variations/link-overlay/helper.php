<?php

if ( ! function_exists( 'etchy_core_add_banner_variation_link_overlay' ) ) {
	function etchy_core_add_banner_variation_link_overlay( $variations ) {
		
		$variations['link-overlay'] = esc_html__( 'Link Overlay', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_banner_layouts', 'etchy_core_add_banner_variation_link_overlay' );
}
