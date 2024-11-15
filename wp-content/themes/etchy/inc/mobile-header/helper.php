<?php

if ( ! function_exists( 'etchy_load_page_mobile_header' ) ) {
	/**
	 * Function which loads page template module
	 */
	function etchy_load_page_mobile_header() {
		// Include mobile header template
		echo apply_filters( 'etchy_filter_mobile_header_template', etchy_get_template_part( 'mobile-header', 'templates/mobile-header' ) );
	}
	
	add_action( 'etchy_action_page_header_template', 'etchy_load_page_mobile_header' );
}

if ( ! function_exists( 'etchy_register_mobile_navigation_menus' ) ) {
	/**
	 * Function which registers navigation menus
	 */
	function etchy_register_mobile_navigation_menus() {
		$navigation_menus = apply_filters( 'etchy_filter_register_mobile_navigation_menus', array( 'mobile-navigation' => esc_html__( 'Mobile Navigation', 'etchy' ) ) );
		
		if ( ! empty( $navigation_menus ) ) {
			register_nav_menus( $navigation_menus );
		}
	}
	
	add_action( 'etchy_action_after_include_modules', 'etchy_register_mobile_navigation_menus' );
}