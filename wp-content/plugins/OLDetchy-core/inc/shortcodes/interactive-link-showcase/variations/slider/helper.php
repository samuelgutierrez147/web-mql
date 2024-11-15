<?php

if ( ! function_exists( 'etchy_core_add_interactive_link_showcase_variation_slider' ) ) {
	function etchy_core_add_interactive_link_showcase_variation_slider( $variations ) {
		$variations['slider'] = esc_html__( 'Slider', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_interactive_link_showcase_layouts', 'etchy_core_add_interactive_link_showcase_variation_slider' );
}
