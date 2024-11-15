<?php

if ( ! function_exists( 'etchy_core_is_page_spinner_enabled' ) ) {
	function etchy_core_is_page_spinner_enabled() {
		return etchy_core_get_post_value_through_levels( 'qodef_enable_page_spinner' ) === 'yes';
	}
}

if ( ! function_exists( 'etchy_core_load_page_spinner' ) ) {
	/**
	 * Loads Spinners HTML
	 */
	function etchy_core_load_page_spinner() {
		
		if ( etchy_core_is_page_spinner_enabled() ) {
			$parameters = array();
			
			etchy_core_template_part( 'spinner', 'templates/spinner', '', $parameters );
		}
	}
	
	add_action( 'etchy_action_after_body_tag_open', 'etchy_core_load_page_spinner' );
}

if ( ! function_exists( 'etchy_core_get_spinners_type' ) ) {
	function etchy_core_get_spinners_type() {
		$html = '';
		$type = etchy_core_get_post_value_through_levels( 'qodef_page_spinner_type' );
		
		if ( ! empty( $type ) ) {
			$html = etchy_core_get_template_part( 'spinner', 'layouts/' . $type . '/templates/' . $type );
		}
		
		echo qode_framework_wp_kses_html( 'svg custom', $html );
	}
}

if ( ! function_exists( 'etchy_core_set_page_spinner_classes' ) ) {
	/**
	 * Function that return classes for page spinner area
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function etchy_core_set_page_spinner_classes( $classes ) {
		$type = etchy_core_get_post_value_through_levels( 'qodef_page_spinner_type' );
		
		if ( ! empty( $type ) ) {
			$classes[] = 'qodef-layout--' . esc_attr( $type );
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_core_filter_page_spinner_classes', 'etchy_core_set_page_spinner_classes' );
}

if ( ! function_exists( 'etchy_core_set_page_spinner_styles' ) ) {
	/**
	 * Function that generates module inline styles
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	function etchy_core_set_page_spinner_styles( $style ) {
		$spinner_styles = array();
		
		$spinner_background_color = etchy_core_get_post_value_through_levels( 'qodef_page_spinner_background_color' );
		$spinner_background_image = etchy_core_get_post_value_through_levels( 'qodef_page_spinner_background_image' );
		$spinner_color            = etchy_core_get_post_value_through_levels( 'qodef_page_spinner_color' );
		
		if ( ! empty( $spinner_background_color ) ) {
			$spinner_styles['background-color'] = $spinner_background_color;
		}
		
		if ( ! empty( $spinner_background_image ) ) {
			$spinner_styles['background-image'] = 'url(' . esc_url( wp_get_attachment_image_url( $spinner_background_image, 'full' ) ) . ');';
		}
		
		if ( ! empty( $spinner_color ) ) {
			$spinner_styles['color'] = $spinner_color;
		}
		
		if ( ! empty( $spinner_styles ) ) {
			$style .= qode_framework_dynamic_style( '#qodef-page-spinner .qodef-m-inner', $spinner_styles );
		}
		
		return $style;
	}
	
	add_filter( 'etchy_filter_add_inline_style', 'etchy_core_set_page_spinner_styles' );
}