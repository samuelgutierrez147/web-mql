<?php

if ( ! function_exists( 'etchy_core_add_fixed_header_option' ) ) {
	/**
	 * This function set header scrolling appearance value for global header option map
	 */
	function etchy_core_add_fixed_header_option( $options ) {
		$options['fixed'] = esc_html__( 'Fixed', 'etchy-core' );

		return $options;
	}

	add_filter( 'etchy_core_filter_header_scroll_appearance_option', 'etchy_core_add_fixed_header_option' );
}