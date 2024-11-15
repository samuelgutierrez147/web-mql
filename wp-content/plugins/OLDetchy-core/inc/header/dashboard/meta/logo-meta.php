<?php

if ( ! function_exists( 'etchy_core_add_page_logo_meta_box' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_page_logo_meta_box( $page ) {

		if ( $page ) {

			$logo_tab = $page->add_tab_element(
				array(
					'name'        => 'tab-logo',
					'icon'        => 'fa fa-cog',
					'title'       => esc_html__( 'Logo Settings', 'etchy-core' ),
					'description' => esc_html__( 'Logo settings', 'etchy-core' )
				)
			);

			$header_logo_section = $logo_tab->add_section_element(
				array(
					'name'  => 'qodef_header_logo_section',
					'title' => esc_html__( 'Header Logo Options', 'etchy-core' ),
				)
			);

			$header_logo_section->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_logo_height',
					'title'       => esc_html__( 'Logo Height', 'etchy-core' ),
					'description' => esc_html__( 'Enter logo height', 'etchy-core' ),
					'args'        => array(
						'suffix' => esc_html__( 'px', 'etchy-core' )
					)
				)
			);

			$header_logo_section->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_logo_main',
					'title'       => esc_html__( 'Logo - Main', 'etchy-core' ),
					'description' => esc_html__( 'Choose main logo image', 'etchy-core' ),
					'multiple'    => 'no'
				)
			);

			$header_logo_section->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_logo_dark',
					'title'       => esc_html__( 'Logo - Dark', 'etchy-core' ),
					'description' => esc_html__( 'Choose dark logo image', 'etchy-core' ),
					'multiple'    => 'no'
				)
			);

			$header_logo_section->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_logo_light',
					'title'       => esc_html__( 'Logo - Light', 'etchy-core' ),
					'description' => esc_html__( 'Choose light logo image', 'etchy-core' ),
					'multiple'    => 'no'
				)
			);

			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_page_logo_meta_map', $logo_tab, $header_logo_section );
		}
	}

	add_action( 'etchy_core_action_after_general_meta_box_map', 'etchy_core_add_page_logo_meta_box' );
}

if ( ! function_exists( 'etchy_core_add_general_logo_meta_box_callback' ) ) {
	/**
	 * Function that set current meta box callback as general callback functions
	 *
	 * @param array $callbacks
	 *
	 * @return array
	 */
	function etchy_core_add_general_logo_meta_box_callback( $callbacks ) {
		$callbacks['logo'] = 'etchy_core_add_page_logo_meta_box';
		
		return $callbacks;
	}
	
	add_filter( 'etchy_core_filter_general_meta_box_callbacks', 'etchy_core_add_general_logo_meta_box_callback' );
}