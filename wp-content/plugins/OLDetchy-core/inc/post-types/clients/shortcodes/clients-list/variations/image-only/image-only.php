<?php

if ( ! function_exists( 'etchy_core_add_clients_list_variation_image_only' ) ) {
	function etchy_core_add_clients_list_variation_image_only( $variations ) {
		
		$variations['image-only'] = esc_html__( 'Image Only', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_clients_list_layouts', 'etchy_core_add_clients_list_variation_image_only' );
}