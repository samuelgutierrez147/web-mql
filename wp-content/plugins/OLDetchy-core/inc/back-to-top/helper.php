<?php

if ( ! function_exists( 'etchy_core_is_back_to_top_enabled' ) ) {
	function etchy_core_is_back_to_top_enabled() {
		return etchy_core_get_post_value_through_levels( 'qodef_back_to_top' ) !== 'no';
	}
}

if ( ! function_exists( 'etchy_core_add_back_to_top_to_body_classes' ) ) {
	function etchy_core_add_back_to_top_to_body_classes( $classes ) {
		$classes[] = etchy_core_is_back_to_top_enabled() ? 'qodef-back-to-top--enabled' : '';
		
		return $classes;
	}
	
	add_filter( 'body_class', 'etchy_core_add_back_to_top_to_body_classes' );
}

if ( ! function_exists( 'etchy_core_load_back_to_top' ) ) {
	/**
	 * Loads Back To Top HTML
	 */
	function etchy_core_load_back_to_top() {
		
		if ( etchy_core_is_back_to_top_enabled() ) {
			$parameters = array();
			
			etchy_core_template_part( 'back-to-top', 'templates/back-to-top', '', $parameters );
		}
	}
	
	add_action( 'etchy_action_before_wrapper_close_tag', 'etchy_core_load_back_to_top' );
}