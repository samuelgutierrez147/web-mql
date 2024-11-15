<?php

if ( ! function_exists( 'etchy_set_404_page_inner_classes' ) ) {
	/**
	 * Function that return classes for the page inner div from header.php
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function etchy_set_404_page_inner_classes( $classes ) {
		
		if ( is_404() ) {
			$classes = 'qodef-content-full-width';
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_page_inner_classes', 'etchy_set_404_page_inner_classes' );
}

if ( ! function_exists( 'etchy_get_404_page_parameters' ) ) {
	/**
	 * Function that set 404 page area content parameters
	 */
	function etchy_get_404_page_parameters() {
		
		$params = array(
			'number'      => esc_html__( '404', 'etchy' ),
			'title'       => esc_html__( 'Page Not Found', 'etchy' ),
			'text'        => esc_html__( 'The page you are looking for does not exist. It may have been moved, or removed altogether. Perhaps you can return back to the site\'s homepage and see if you can find what you are looking for.', 'etchy' ),
			'button_text' => esc_html__( 'Back to home', 'etchy' ),
		);
		
		return apply_filters( 'etchy_filter_404_page_template_params', $params );
	}
}
