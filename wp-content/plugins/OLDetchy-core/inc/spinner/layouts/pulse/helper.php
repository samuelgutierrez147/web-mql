<?php

if ( ! function_exists( 'etchy_core_add_pulse_spinner_layout_option' ) ) {
	/**
	 * Function that set new value into page spinner layout options map
	 *
	 * @param array $layouts  - module layouts
	 *
	 * @return array
	 */
	function etchy_core_add_pulse_spinner_layout_option( $layouts ) {
		$layouts['pulse'] = esc_html__( 'Pulse', 'etchy-core' );
		
		return $layouts;
	}
	
	add_filter( 'etchy_core_filter_page_spinner_layout_options', 'etchy_core_add_pulse_spinner_layout_option' );
}

if ( ! function_exists( 'etchy_core_set_pulse_spinner_layout_as_default_option' ) ) {
	/**
	 * Function that set default value for page spinner layout options map
	 *
	 * @param string $default_value
	 *
	 * @return string
	 */
	function etchy_core_set_pulse_spinner_layout_as_default_option( $default_value ) {
		return 'pulse';
	}
	
	add_filter( 'etchy_core_filter_page_spinner_default_layout_option', 'etchy_core_set_pulse_spinner_layout_as_default_option' );
}