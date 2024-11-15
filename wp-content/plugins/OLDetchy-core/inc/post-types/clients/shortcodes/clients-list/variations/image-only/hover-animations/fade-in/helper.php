<?php
if ( ! function_exists( 'etchy_core_filter_clients_list_image_only_fade_in' ) ) {
	function etchy_core_filter_clients_list_image_only_fade_in( $variations ) {
		
		$variations['fade-in'] = esc_html__( 'Fade In', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_clients_list_image_only_animation_options', 'etchy_core_filter_clients_list_image_only_fade_in' );
}