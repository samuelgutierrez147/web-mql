<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_slider' ) ) {
	function etchy_core_add_portfolio_single_variation_slider( $variations ) {
		$variations['slider'] = esc_html__( 'Slider', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_slider' );
}

if ( ! function_exists( 'etchy_core_add_portfolio_single_slider' ) ) {
	function etchy_core_add_portfolio_single_slider() {
		if ( etchy_core_get_post_value_through_levels( 'qodef_portfolio_single_layout' ) == 'slider' ) {
			etchy_core_template_part( 'post-types/portfolio', 'variations/slider/layout/parts/slider' );
		}
	}
	
	add_action( 'etchy_action_before_page_inner', 'etchy_core_add_portfolio_single_slider' );
}