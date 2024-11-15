<?php

if ( ! function_exists( 'etchy_core_add_social_share_variation_dropdown' ) ) {
	function etchy_core_add_social_share_variation_dropdown( $variations ) {
		
		$variations['dropdown'] = esc_html__( 'Dropdown', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_social_share_layouts', 'etchy_core_add_social_share_variation_dropdown' );
}
