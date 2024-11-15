<?php

if ( ! function_exists( 'etchy_core_add_interactive_link_showcase_variation_interactive_list' ) ) {
	function etchy_core_add_interactive_link_showcase_variation_interactive_list( $variations ) {
		$variations['interactive-list'] = esc_html__( 'Interactive List', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_interactive_link_showcase_layouts', 'etchy_core_add_interactive_link_showcase_variation_interactive_list' );
}
