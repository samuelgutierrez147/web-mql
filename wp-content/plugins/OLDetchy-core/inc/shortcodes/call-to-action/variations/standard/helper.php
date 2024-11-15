<?php

if ( ! function_exists( 'etchy_core_add_call_to_action_variation_standard' ) ) {
	function etchy_core_add_call_to_action_variation_standard( $variations ) {
		
		$variations['standard'] = esc_html__( 'Standard', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_call_to_action_layouts', 'etchy_core_add_call_to_action_variation_standard' );
}
