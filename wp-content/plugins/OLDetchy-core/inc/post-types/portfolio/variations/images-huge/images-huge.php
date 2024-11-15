<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_variation_images_huge' ) ) {
	function etchy_core_add_portfolio_single_variation_images_huge( $variations ) {
		$variations['images-huge'] = esc_html__( 'Images - Huge', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_options', 'etchy_core_add_portfolio_single_variation_images_huge' );
}

if ( ! function_exists( 'etchy_core_set_default_portfolio_single_variation_compact' ) ) {
	function etchy_core_set_default_portfolio_single_variation_compact() {
		return 'images-huge';
	}
	
	add_filter( 'etchy_core_filter_portfolio_single_layout_default_value', 'etchy_core_set_default_portfolio_single_variation_compact' );
}

if ( ! function_exists( 'etchy_core_set_portfolio_single_variation_images_huge_holder_width' ) ) {
	function etchy_core_set_portfolio_single_variation_images_huge_holder_width( $classes ) {

		if ( etchy_core_get_post_value_through_levels( 'qodef_portfolio_single_layout' ) == 'images-huge' ) {
			$classes = 'qodef-content-full-width';
		}

		return $classes;
	}

	add_filter( 'etchy_filter_page_inner_classes', 'etchy_core_set_portfolio_single_variation_images_huge_holder_width' );
}