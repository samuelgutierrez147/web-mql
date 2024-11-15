<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_masonry_small' ) ) {
	function etchy_core_add_portfolio_single_variation_masonry_small( $variations ) {
		$variations['masonry-small'] = esc_html__( 'Masonry - Small', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_masonry_small' );
}

if ( ! function_exists( 'etchy_core_include_masonry_for_portfolio_single_variation_masonry_small' ) ) {
	function etchy_core_include_masonry_for_portfolio_single_variation_masonry_small( $post_type ) {
		$portfolio_template = etchy_core_get_post_value_through_levels( 'qodef_portfolio_single_layout' );
		
		if ( $portfolio_template === 'masonry-small' ) {
			$post_type = 'portfolio-item';
		}
		
		return $post_type;
	}
	
	add_filter( 'etchy_filter_allowed_post_type_to_enqueue_masonry_scripts', 'etchy_core_include_masonry_for_portfolio_single_variation_masonry_small' );
}