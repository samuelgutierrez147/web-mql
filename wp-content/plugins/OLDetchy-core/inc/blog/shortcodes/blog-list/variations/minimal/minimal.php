<?php

if ( ! function_exists( 'etchy_core_add_blog_list_variation_minimal' ) ) {
	function etchy_core_add_blog_list_variation_minimal( $variations ) {
		$variations['minimal'] = esc_html__( 'Minimal', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_blog_list_layouts', 'etchy_core_add_blog_list_variation_minimal' );
}