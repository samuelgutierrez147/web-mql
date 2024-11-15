<?php

if ( ! function_exists( 'etchy_core_get_age_verification' ) ) {
	/**
	 * Loads age-verification HTML
	 */
	function etchy_core_get_age_verification() {
		if ( etchy_core_get_post_value_through_levels( 'qodef_enable_age_verification' ) === 'yes' ) {
			etchy_core_load_age_verification_template();
		}
	}
	
	// Get age-verification HTML
	add_action( 'etchy_action_before_page_header', 'etchy_core_get_age_verification' );
}

if ( ! function_exists( 'etchy_core_body_class_age_verification' ) ) {
	/**
	 * Adds body class
	 */
	function etchy_core_body_class_age_verification( $classes ) {
		if ( ! isset( $_COOKIE['disabledAgeVerification'] ) ) {
			$classes[] = 'qodef-age-verification--opened';
		}
		
		return $classes;
	}
	
	// Add plugin's body classes
	add_filter( 'body_class', 'etchy_core_body_class_age_verification' );
}

if ( ! function_exists( 'etchy_core_age_verification_set_admin_options_map_position' ) ) {
	/**
	 * Function that set dashboard admin options map position for this module
	 *
	 * @param int $position
	 * @param string $map
	 *
	 * @return int
	 */
	function etchy_core_age_verification_set_admin_options_map_position( $position, $map ) {
		
		if ( $map === 'age-verification' ) {
			$position = 98;
		}
		
		return $position;
	}
	
	add_filter( 'etchy_core_filter_admin_options_map_position', 'etchy_core_age_verification_set_admin_options_map_position', 10, 2 );
}

if ( ! function_exists( 'etchy_core_load_age_verification_template' ) ) {
	/**
	 * Loads HTML template with params
	 */
	function etchy_core_load_age_verification_template() {
		$logo_image = etchy_core_get_option_value( 'admin', 'qodef_age_verification_logo_image' );
		
		$params                     = array();
		$params['logo_image']       = ! empty( $logo_image ) ? $logo_image : etchy_core_get_post_value_through_levels( 'qodef_logo_main' );
		$params['title']            = etchy_core_get_option_value( 'admin', 'qodef_age_verification_title' );
		$params['subtitle']         = etchy_core_get_option_value( 'admin', 'qodef_age_verification_subtitle' );
		$params['note']             = etchy_core_get_option_value( 'admin', 'qodef_age_verification_note' );
		$params['link']             = etchy_core_get_option_value( 'admin', 'qodef_age_verification_link' );
		$background_image           = etchy_core_get_option_value( 'admin', 'qodef_age_verification_background_image' );
		$params['content_style']    = ! empty( $background_image ) ? 'background-image: url(' . esc_url( wp_get_attachment_url( $background_image ) ) . ')' : '';
		$params['prevent_behavior'] = etchy_core_get_option_value( 'admin', 'qodef_age_verification_prevent_behavior' );
		
		$holder_classes           = array();
		$holder_classes[]         = ! empty( $params['prevent_behavior'] ) ? 'qodef-prevent--' . $params['prevent_behavior'] : 'qodef-prevent--cookies';
		$params['holder_classes'] = implode( ' ', $holder_classes );
		
		echo etchy_core_get_template_part( 'age-verification', 'templates/age-verification', '', $params );
	}
}