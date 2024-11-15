<?php

if ( ! function_exists( 'etchy_core_add_mobile_logo_options' ) ) {
	/**
	 * Function that add mobile header options for this module
	 */
	function etchy_core_add_mobile_logo_options( $page, $header_tab ) {

		if ( $page ) {
			
			$mobile_header_tab = $page->add_tab_element(
				array(
					'name'        => 'tab-mobile-header',
					'icon'        => 'fa fa-cog',
					'title'       => esc_html__( 'Mobile Header Logo Options', 'etchy-core' ),
					'description' => esc_html__( 'Set options for mobile headers', 'etchy-core' )
				)
			);
			
			$mobile_header_tab->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_mobile_logo_height',
					'title'       => esc_html__( 'Mobile Logo Height', 'etchy-core' ),
					'description' => esc_html__( 'Enter mobile logo height', 'etchy-core' ),
					'args'        => array(
						'suffix' => esc_html__( 'px', 'etchy-core' )
					)
				)
			);
			
			$mobile_header_tab->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_mobile_logo_main',
					'title'       => esc_html__( 'Mobile Logo - Main', 'etchy-core' ),
					'description' => esc_html__( 'Choose main mobile logo image', 'etchy-core' ),
					'default_value' => defined( 'ETCHY_ASSETS_ROOT' ) ? ETCHY_ASSETS_ROOT . '/img/logo-mobile.png' : '',
					'multiple'    => 'no'
				)
			);
			
			do_action( 'etchy_core_action_after_mobile_logo_options_map', $page );
		}
	}
	
	add_action( 'etchy_core_action_after_header_logo_options_map', 'etchy_core_add_mobile_logo_options', 10, 2 );
}