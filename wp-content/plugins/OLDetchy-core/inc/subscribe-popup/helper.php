<?php

if ( ! function_exists( 'etchy_core_get_subscribe_popup' ) ) {
	/**
	 * Loads subscribe popup HTML
	 */
	function etchy_core_get_subscribe_popup() {
		if ( etchy_core_get_option_value( 'admin', 'qodef_enable_subscribe_popup' ) === 'yes' && etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_contact_form' ) !== '' ) {
			etchy_core_load_subscribe_popup_template();
		}
	}

	// Get subscribe popup HTML
	add_action( 'etchy_action_before_wrapper_close_tag', 'etchy_core_get_subscribe_popup' );
}

if ( ! function_exists( 'etchy_core_load_subscribe_popup_template' ) ) {
	/**
	 * Loads HTML template with params
	 */
	function etchy_core_load_subscribe_popup_template() {
		$params                     = array();
		$params['title']            = etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_title' );
		$params['subtitle']         = etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_subtitle' );
		$background_image           = etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_background_image' );
		$params['content_style']    = ! empty( $background_image ) ? 'background-image: url(' . esc_url( wp_get_attachment_url( $background_image ) ) . ')' : '';
		$params['contact_form']     = etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_contact_form' );
		$params['enable_prevent']   = etchy_core_get_option_value( 'admin', 'qodef_enable_subscribe_popup_prevent' );
		$params['prevent_behavior'] = etchy_core_get_option_value( 'admin', 'qodef_subscribe_popup_prevent_behavior' );

		$holder_classes           = array();
		$holder_classes[]         = ! empty( $params['prevent_behavior'] ) ? 'qodef-sp-prevent-' . $params['prevent_behavior'] : 'qodef-sp-prevent-session';
		$params['holder_classes'] = implode( ' ', $holder_classes );

		echo etchy_core_get_template_part( 'subscribe-popup', 'templates/subscribe-popup', '', $params );
	}
}