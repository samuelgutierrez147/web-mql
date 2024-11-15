<?php

if ( ! function_exists( 'etchy_core_add_author_list_variation_info_below' ) ) {
	function etchy_core_add_author_list_variation_info_below( $variations ) {
		
		$variations['info-below'] = esc_html__( 'Info Below', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_author_list_layouts', 'etchy_core_add_author_list_variation_info_below' );
}
