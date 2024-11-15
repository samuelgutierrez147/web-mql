<?php

if ( ! function_exists( 'etchy_core_add_item_showcase_variation_list' ) ) {
	function etchy_core_add_item_showcase_variation_list( $variations ) {
		$variations['list'] = esc_html__( 'List', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_item_showcase_layouts', 'etchy_core_add_item_showcase_variation_list' );
}
