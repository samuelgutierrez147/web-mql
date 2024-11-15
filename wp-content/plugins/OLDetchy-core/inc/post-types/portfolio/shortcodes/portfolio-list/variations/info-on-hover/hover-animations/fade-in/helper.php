<?php

if ( ! function_exists( 'etchy_core_filter_portfolio_list_info_on_hover_fade_in' ) ) {
	function etchy_core_filter_portfolio_list_info_on_hover_fade_in( $variations ) {
		$variations['fade-in'] = esc_html__( 'Fade In', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_info_on_hover_animation_options', 'etchy_core_filter_portfolio_list_info_on_hover_fade_in' );
}